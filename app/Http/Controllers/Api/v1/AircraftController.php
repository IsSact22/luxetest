<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\AircraftRepositoryInterface;
use App\Http\Requests\StoreAircraftRequest;
use App\Http\Requests\UpdateAircraftRequest;
use App\Http\Resources\AircraftResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Aircraft;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Throwable;
use Inertia\Inertia;

class AircraftController extends Controller
{
    public function __construct(protected AircraftRepositoryInterface $aircraftRepository)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', Aircraft::class);
            $aircrafts = $this->aircraftRepository->getAll($request);
            $resource = AircraftResource::collection($aircrafts);
    
            // Verifica si la petición es de Inertia
            if ($request->hasHeader('X-Inertia')) {
                return Inertia::render('Aircrafts/Index', [
                    'resource' => $resource
                ]);
            }
    
            // Si la petición es una API (por ejemplo, desde Postman o fetch en frontend)
            if ($request->expectsJson()) {
                return new ApiSuccessResponse($resource, []);
            }
    
            // En caso de acceso directo desde el navegador, redirigir a Inertia
            return redirect()->route('aircrafts.index');
        } catch (AuthorizationException) {
            return new ApiErrorResponse(null, 'Unauthorized', Response::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new ApiErrorResponse($e, 'Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function store(StoreAircraftRequest $request)
    {
        try {
            $this->authorize('create', Aircraft::class);
            
            // Crear un nuevo Aircraft con los datos validados
            $aircraft = $this->aircraftRepository->newModel($request->validated());
            $resource = new AircraftResource($aircraft);
    
           // Verifica si la petición es de Inertia
        if (request()->hasHeader('X-Inertia')) {
            return redirect()->route('aircrafts.index')->with('success', 'Aircraft create successfully');
        }
    
            return new ApiSuccessResponse($resource, [], Response::HTTP_CREATED);
        } catch (AuthorizationException) {
            return new ApiErrorResponse(null, 'Unauthorized', Response::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new ApiErrorResponse($e, 'Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    public function show(Request $request, int $id)
    {
        try {
            $aircraft = $this->aircraftRepository->getById($id);
            $resource = new AircraftResource($aircraft);
            $this->authorize('s', $aircraft);
    
          // Verifica si la petición es de Inertia
        if (request()->hasHeader('X-Inertia')) {
            return redirect()->route('aircrafts.index')->with('success', 'Aircraft ac successfully');
        }
    
            return new ApiSuccessResponse($resource, []);
        } catch (ModelNotFoundException) {
            return new ApiErrorResponse(null, 'Aircraft Not Found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new ApiErrorResponse($e, 'Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function update(UpdateAircraftRequest $request, int $id)
    {
        try {
            $aircraft = $this->aircraftRepository->getById($id);
            $this->authorize('update', $aircraft);
    
            // Actualizar el modelo con los datos validados
            $this->aircraftRepository->updateModel($request->validated(), $id);
            $updatedAircraft = $this->aircraftRepository->getById($id);
            $resource = new AircraftResource($updatedAircraft);
    
           // Verifica si la petición es de Inertia
        if (request()->hasHeader('X-Inertia')) {
            return redirect()->route('aircrafts.index')->with('success', 'Aircraft update successfully');
        }
    
            return new ApiSuccessResponse($resource, [], Response::HTTP_OK);
        } catch (AuthorizationException) {
            return new ApiErrorResponse(null, 'Unauthorized', Response::HTTP_UNAUTHORIZED);
        } catch (ModelNotFoundException) {
            return new ApiErrorResponse(null, 'Aircraft Not Found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new ApiErrorResponse($e, 'Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function destroy(int $id)
{
    try {
        $aircraft = $this->aircraftRepository->getById($id);
        $this->authorize('delete', $aircraft);

        $this->aircraftRepository->deleteModel($id);

        // Verifica si la petición es de Inertia
        if (request()->hasHeader('X-Inertia')) {
            return redirect()->route('aircrafts.index')->with('success', 'Aircraft deleted successfully');
        }

        return new ApiSuccessResponse(['message' => 'Aircraft deleted successfully'], []);
    } catch (AuthorizationException) {
        return new ApiErrorResponse(null, 'Unauthorized', Response::HTTP_UNAUTHORIZED);
    } catch (ModelNotFoundException) {
        return new ApiErrorResponse(null, 'Aircraft Not Found', Response::HTTP_NOT_FOUND);
    } catch (Throwable $e) {
        Log::error($e->getMessage());
        return new ApiErrorResponse($e, 'Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

}

