<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;

use App\Contracts\ModelAircraftRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreModelAircraftRequest;
use App\Http\Requests\UpdateModelAircraftRequest;
use App\Http\Resources\ModelAircraftResource;
use App\Models\ModelAircraft;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Precognitive;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response as InertiaResponseAlias;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class ModelAircraftController extends Controller
{
    public function __construct(protected ModelAircraftRepositoryInterface $modelAircraftRepository)
    {
        parent::__construct();
        $this->middleware(Precognitive::class)->only(['store', 'update']);
    }

    public function index(Request $request): InertiaResponseAlias|JsonResponse
    {
        try {
            $this->authorize('viewAny', ModelAircraft::class);
            $modelAircraft = $this->modelAircraftRepository->getAll($request);
            $resource = ModelAircraftResource::collection($modelAircraft);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'data' => $resource,
                    'meta' => [
                        'total' => $modelAircraft->total(),
                        'per_page' => $modelAircraft->perPage(),
                        'current_page' => $modelAircraft->currentPage()
                    ]
                ], HttpResponse::HTTP_OK);
            }

            return InertiaResponse::content('ModelAircrafts/Index', ['resource' => $resource]);
        } catch (AuthorizationException) {
            return $this->handleError(HttpResponse::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleError(HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function create(Request $request): InertiaResponseAlias|JsonResponse
    {
        try {
            $this->authorize('create', ModelAircraft::class);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Create form is rendered',
                ], HttpResponse::HTTP_OK);
            }

            return InertiaResponse::content('ModelAircrafts/Create');
        } catch (AuthorizationException) {
            return $this->handleError(HttpResponse::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleError(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreModelAircraftRequest $request): InertiaResponseAlias|JsonResponse
    {
        try {
            $this->authorize('create', ModelAircraft::class);
            $payload = $request->validated();
            $modelAircraft = $this->modelAircraftRepository->newModelAircraft($payload);

            if ($request->wantsJson()) {
                return response()->json(new ModelAircraftResource($modelAircraft), HttpResponse::HTTP_CREATED);
            }

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Model Aircraft has been created.'], HttpResponse::HTTP_CREATED);
            }

            return Inertia::render('ModelAircrafts/Index', ['success' => 'Model Aircraft has been created.']);
        } catch (AuthorizationException) {
            return $this->handleError(HttpResponse::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleError(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): InertiaResponseAlias|JsonResponse
    {
        try {
            $modelAircraft = $this->modelAircraftRepository->getById($id);
            $this->authorize('view', $modelAircraft);
            $resource = new ModelAircraftResource($modelAircraft);

            if ($request->wantsJson()) {
                return response()->json(new ModelAircraftResource($modelAircraft), HttpResponse::HTTP_OK);
            }

            return InertiaResponse::content('ModelAircrafts/Show', ['resource' => $resource]);
        } catch (AuthorizationException) {
            return $this->handleError(HttpResponse::HTTP_UNAUTHORIZED);
        } catch (ModelNotFoundException) {
            return $this->handleError(HttpResponse::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleError(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateModelAircraftRequest $request, int $id): InertiaResponseAlias|JsonResponse
    {
        try {
            $modelAircraft = $this->modelAircraftRepository->getById($id);
            $this->authorize('update', $modelAircraft);
            $payload = $request->validated();
            $updatedModel = $this->modelAircraftRepository->updateModelAircraft($payload, $id);

            if ($request->wantsJson()) {
                return response()->json(new ModelAircraftResource($updatedModel), HttpResponse::HTTP_OK);
            }

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Model Aircraft has been updated.'], HttpResponse::HTTP_OK);
            }

            return Inertia::render('ModelAircrafts/Index', ['success' => 'Model Aircraft has been updated.']);
        } catch (AuthorizationException) {
            return $this->handleError(HttpResponse::HTTP_UNAUTHORIZED);
        } catch (ModelNotFoundException) {
            return $this->handleError(HttpResponse::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleError(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): InertiaResponseAlias|JsonResponse
    {
        try {
            $modelAircraft = $this->modelAircraftRepository->getById($id);
            $this->authorize('delete', $modelAircraft);
            $this->modelAircraftRepository->deleteModelAircraft($id);

            if (request()->wantsJson()) {
                return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
            }

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Model Aircraft has been deleted.'], HttpResponse::HTTP_OK);
            }

            return Inertia::render('ModelAircrafts/Index', ['success' => 'Model Aircraft has been deleted.']);
        } catch (ModelNotFoundException) {
            return $this->handleError(HttpResponse::HTTP_NOT_FOUND);
        } catch (Throwable) {
            return $this->handleError(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método común para manejar errores
    protected function handleError(int $statusCode, string $description = null): JsonResponse|InertiaResponseAlias
    {
        if (request()->wantsJson()) {
            return response()->json(['error' => $description], $statusCode);
        }
        return Inertia::render('Errors/Error', ['status' => $statusCode, 'description' => $description]);
    }
}