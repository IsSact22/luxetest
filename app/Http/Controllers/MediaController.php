<?php

namespace App\Http\Controllers;

use App\Helpers\InertiaResponse;
use App\Models\Camo;
use App\Models\CamoActivity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class MediaController extends Controller
{
    public function addImages(Request $request): \Inertia\Response
    {
        try {
            if ($request->has('model_id') && $request->has('model_name')) {
                $id = $request->get('model_id');
                $modelName = $request->get('model_name');

                // Llamar al método getModelRecord para obtener el registro del modelo
                $record = $this->getModelRecord($modelName, $id);

                if ($record === null) {
                    throw new ModelNotFoundException();
                }

                return InertiaResponse::content('Media/AddImage', ['modelName' => $modelName, 'id' => $id, 'record' => $record]);
            } else {
                return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_BAD_REQUEST]);
            }
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (\Throwable $e) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function getModelRecord($modelName, $id)
    {
        $modelClass = 'App\\Models\\'.$modelName;

        if (! class_exists($modelClass)) {
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
            } else {
                return response()->json([
                    'message' => 'Invalid model',
                ], 400);
            }

        } catch (FileDoesNotExist $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 404);
        } catch (FileIsTooBig $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 413);
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }

    }

    private function getModelInstance(string $modelName): CamoActivity|Camo|null
    {
        return match ($modelName) {
            'Camo' => new Camo(),
            'CamoActivity' => new CamoActivity(),
            default => null,
        };
    }
}
