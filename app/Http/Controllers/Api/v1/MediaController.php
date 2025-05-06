<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\InertiaResponse;
use App\Http\Controllers\Controller;
use App\Models\Camo;
use App\Models\CamoActivity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class MediaController extends Controller
{
    /**
     * Muestra el formulario para agregar imágenes o retorna información del modelo.
     *
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function upload(Request $request): Response|JsonResponse
    {
        try {
            if (!$request->has('model_id') || !$request->has('model_name')) {
                throw new \InvalidArgumentException('Model ID and name are required');
            }

            $id = $request->get('model_id');
            $modelName = $request->get('model_name');
            $record = $this->getModelRecord($modelName, $id);

            if ($record === null) {
                throw new ModelNotFoundException;
            }

            // Si es una petición AJAX o API, retornar JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'model_name' => $modelName,
                    'id' => $id,
                    'record' => $record
                ]);
            }

            // Si no, retornar vista Inertia
            return InertiaResponse::content('Media/AddImage', [
                'modelName' => $modelName,
                'id' => $id,
                'record' => $record
            ]);

        } catch (ModelNotFoundException) {
            return $this->errorResponse('Model not found', ResponseAlias::HTTP_NOT_FOUND, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Agrega imágenes a un modelo específico.
     *
     * @param Request $request
     * @param string $modelName
     * @return JsonResponse
     */
    public function addImageToModel(Request $request, string $modelName): JsonResponse
    {
        try {
            $model = $this->getModelInstance($modelName);
            if (!$model instanceof HasMedia) {
                throw new \InvalidArgumentException('Invalid model');
            }

            $model->addMultipleMediaFromRequest($request->get('images'))
                  ->toMediaCollection('image-support');

            return response()->json([
                'message' => 'Images added successfully',
                'status' => 'success'
            ], ResponseAlias::HTTP_CREATED);

        } catch (FileDoesNotExist $e) {
            return response()->json([
                'message' => 'File not found',
                'error' => $e->getMessage()
            ], ResponseAlias::HTTP_NOT_FOUND);
        } catch (FileIsTooBig $e) {
            return response()->json([
                'message' => 'File is too big',
                'error' => $e->getMessage()
            ], ResponseAlias::HTTP_REQUEST_ENTITY_TOO_LARGE);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error processing request',
                'error' => $e->getMessage()
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Verifica si un CAMO tiene imágenes en sus actividades.
     *
     * @param Camo $camo
     * @return JsonResponse
     */
    public function hasImagesInActivities(Camo $camo): JsonResponse
    {
        try {
            $hasImages = $camo->camoActivity()
                ->whereHas('media')
                ->exists();

            return response()->json([
                'has_images' => $hasImages
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error checking images',
                'error' => $e->getMessage()
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtiene todas las imágenes de las actividades de un CAMO.
     *
     * @param Camo $camo
     * @return Response|JsonResponse
     */
    public function getMedia(Camo $camo): Response|JsonResponse
    {
        try {
            // Obtener todas las actividades que tienen imágenes
            $activities = $camo->camoActivity()
                ->with('media')
                ->whereHas('media')
                ->get();

            if ($activities->isEmpty()) {
                return $this->errorResponse('No images found', ResponseAlias::HTTP_NOT_FOUND, request());
            }

            // Formatear los datos para la vista
            $mediaByActivity = $activities->map(function ($activity) {
                return [
                    'activity_id' => $activity->id,
                    'activity_name' => $activity->name ?? 'Sin nombre',
                    'media' => $activity->getMedia($activity->mediaCollectionName)->map(function ($media) {
                        try {
                            $url = $media->getUrl();
                            $previewUrl = $media->hasGeneratedConversion('preview') ? $media->getUrl('preview') : $url;
                            $thumbnailUrl = $media->hasGeneratedConversion('thumbnail') ? $media->getUrl('thumbnail') : $previewUrl;

                            return [
                                'id' => $media->id,
                                'name' => $media->name,
                                'file_name' => $media->file_name,
                                'mime_type' => $media->mime_type,
                                'size' => $media->size,
                                'url' => str_replace('https:', 'http:', $url),
                                'thumbnail' => str_replace('https:', 'http:', $thumbnailUrl),
                                'preview' => str_replace('https:', 'http:', $previewUrl),
                                'created_at' => $media->created_at->format('Y-m-d H:i:s')
                            ];
                        } catch (Throwable $e) {
                            Log::warning('Error processing media item: ' . $e->getMessage());
                            return null;
                        }
                    })->filter()->values()
                ];
            })->filter(function ($activity) {
                return $activity['media']->isNotEmpty();
            })->values();

            if ($mediaByActivity->isEmpty()) {
                return $this->errorResponse('No valid images found', ResponseAlias::HTTP_NOT_FOUND, request());
            }

            // Si es una petición AJAX o API, retornar JSON
            if (request()->expectsJson()) {
                return response()->json(['media' => $mediaByActivity]);
            }

            // Si no, retornar vista Inertia
            return InertiaResponse::content('Media/Show', [
                'media' => $mediaByActivity,
                'camo' => $camo
            ]);

        } catch (Throwable $e) {
            Log::error('Error getting media: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR, request());
        }
    }

    /**
     * Obtiene una instancia del modelo basado en el nombre.
     *
     * @param string $modelName
     * @return CamoActivity|Camo|null
     */
    private function getModelInstance(string $modelName): CamoActivity|Camo|null
    {
        return match ($modelName) {
            'Camo' => new Camo,
            'CamoActivity' => new CamoActivity,
            default => null,
        };
    }

    /**
     * Obtiene un registro del modelo basado en el nombre y ID.
     *
     * @param string $modelName
     * @param mixed $id
     * @return mixed
     */
    private function getModelRecord(string $modelName, mixed $id): mixed
    {
        $modelClass = 'App\\Models\\' . $modelName;

        if (!class_exists($modelClass)) {
            return null;
        }

        return $modelClass::findOrFail($id);
    }

    /**
     * Genera una respuesta de error basada en el tipo de request.
     *
     * @param string $message
     * @param int $status
     * @param Request $request
     * @return Response|JsonResponse
     */
    private function errorResponse(string $message, int $status, Request $request): Response|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message
            ], $status);
        }

        return Inertia::render('Errors/Error', ['status' => $status]);
    }



}



