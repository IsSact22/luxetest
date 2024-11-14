<?php

namespace App\Http\Controllers;

use App\Helpers\InertiaResponse;
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
    public function addImages(Request $request): Response
    {
        try {
            if ($request->has('model_id') && $request->has('model_name')) {
                $id = $request->get('model_id');
                $modelName = $request->get('model_name');

                // Llamar al método getModelRecord para obtener el registro del modelo
                $record = $this->getModelRecord($modelName, $id);

                if ($record === null) {
                    throw new ModelNotFoundException;
                }

                return InertiaResponse::content('Media/AddImage', ['modelName' => $modelName, 'id' => $id, 'record' => $record]);
            }

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_BAD_REQUEST]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function getModelRecord($modelName, $id)
    {
        $modelClass = 'App\\Models\\' . $modelName;

        if (!class_exists($modelClass)) {
            return null;
        }

        return $modelClass::findOrFail($id);
    }

    public function addImageToModel(Request $request, string $modelName): JsonResponse
    {
        try {
            $model = $this->getModelInstance($modelName);
            if ($model instanceof HasMedia) {
                $model->addMultipleMediaFromRequest($request->get('images'))->toMediaCollection('image-support');

                return response()->json([
                    'message' => 'Image added successfully',
                ], 201);
            }

            return response()->json([
                'message' => 'Invalid model',
            ], 400);

        } catch (FileDoesNotExist $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 404);
        } catch (FileIsTooBig $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 413);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }

    }

    private function getModelInstance(string $modelName): CamoActivity|Camo|null
    {
        return match ($modelName) {
            'Camo' => new Camo,
            'CamoActivity' => new CamoActivity,
            default => null,
        };
    }

    public function hasImagesInActivities(Camo $camo): JsonResponse
    {
        // Obtener todas las actividades relacionadas con el modelo Camo
        $activities = $camo->camoActivity()->with('media')->get();

        // Verificar si alguna actividad tiene imágenes
        $hasImages = $activities->contains(function ($activity) {
            return $activity->getMedia('image-support')->isNotEmpty();
        });

        return response()->json(['hasImages' => $hasImages]);
    }

    /**
     * @throws FileDoesNotExist
     */
    public function getMedia(Camo $camo): Response
    {
        $results = collect();
        $camoId = $camo->id;
        $activities = CamoActivity::query()
            ->with('camo')
            ->where('camo_id', $camoId)
            ->get();
        foreach ($activities as $activity) {
            if ($activity instanceof CamoActivity && $activity->getMedia('*')) {
                foreach ($activity->getMedia('*') as $key => $media) {
                    $results->push([
                        'id' => $media->id,
                        'camo_id' => $camoId,
                        'camo' => $activity->camo,
                        'title' => $media->name,
                        'file_name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'size' => $media->size,
                        'url' => $media->getUrl(),
                    ]);
                }
            } else {
                throw new FileDoesNotExist;
            }
        }

        return InertiaResponse::content('Camos/Gallery', ['resource' => $results]);
    }
}
