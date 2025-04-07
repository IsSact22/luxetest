<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;

use App\Exceptions\RepositoryException;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreCamoActivityRequest;
use App\Http\Requests\UpdateCamoActivityRequest;
use App\Http\Resources\CamoActivityResource;
use App\Http\Resources\CamoResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Camo;
use App\Models\CamoActivity;
use App\Repositories\CamoActivityRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class CamoActivityController extends Controller
{
    public function __construct(protected CamoActivityRepository $activity)
    {
        parent::__construct();
        $this->middleware(HandlePrecognitiveRequests::class)->only(['store', 'update']);
        $this->middleware('auth:api')->only(['apiIndex', 'apiStore', 'apiShow', 'apiUpdate', 'apiDestroy']);
    }

    /**
     * Determina si la solicitud es una API request
     */
    protected function isApiRequest(Request $request): bool
    {
        return $request->is('api/*') || $request->wantsJson();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', CamoActivity::class);
            $activities = $this->activity->getAll($request);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: CamoActivityResource::collection($activities),
                    metaData: [
                        'total' => $activities->total(),
                        'per_page' => $activities->perPage(),
                        'current_page' => $activities->currentPage()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('CamoActivities/Index', [
                'resource' => CamoActivityResource::collection($activities)
            ]);

        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $this->authorize('create', CamoActivity::class);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                    message: 'Create form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('CamoActivities/Create');
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCamoActivityRequest $request)
    {
        try {
            $this->authorize('create', CamoActivity::class);
            $payload = precognitive(static fn($bail) => $request->validated());
            $activity = $this->activity->newModel($payload);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new CamoActivityResource($activity),
                    metaData: [
                        'action' => 'created',
                        'camo_id' => $activity->camo_id
                    ],
                    statusCode: HttpResponse::HTTP_CREATED
                );
            }

            return to_route('camos.show', $payload['camo_id'])->with('success', 'CAMO Activity created successfully');
        } catch (AuthorizationException $e) {
            Log::error('Authorization error: '.$e->getMessage());
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request);
        } catch (Throwable $e) {
            Log::error('Error creating CAMO Activity: '.$e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('view', $camoActivity);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new CamoActivityResource($camoActivity),
                    metaData: ['camo_id' => $camoActivity->camo_id],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('CamoActivities/Show', [
                'resource' => new CamoActivityResource($camoActivity)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request);
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('update', $camoActivity);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new CamoActivityResource($camoActivity),
                    message: 'Edit form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            $camo = Camo::query()->findOrFail($camoActivity->camo_id);
            $camoResource = new CamoResource($camo);

            return InertiaResponse::content('CamoActivities/Edit', [
                'resource' => new CamoActivityResource($camoActivity),
                'camo' => $camoResource,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCamoActivityRequest $request, string $id)
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('update', $camoActivity);
            $payload = precognitive(static fn($bail) => $request->validated());
            $updatedActivity = $this->activity->updateModel($payload, $id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new CamoActivityResource($updatedActivity),
                    metaData: [
                        'action' => 'updated',
                        'camo_id' => $updatedActivity->camo_id,
                        'updated_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            Session::flash('success', 'Actividad actualizada correctamente');
            return to_route('camos.show', $camoActivity->camo->id);
        } catch (ModelNotFoundException $e) {
            Log::error('Modelo no encontrado: ' . $e->getMessage());
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request);
        } catch (QueryException $e) {
            Log::error('Error de base de datos: ' . $e->getMessage());
            return $this->handleErrorResponse('Database Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        } catch (RepositoryException $e) {
            Log::error('Repository error: ' . $e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        } catch (Throwable $e) {
            Log::error('Error general: ' . $e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('delete', $camoActivity);
            $this->activity->deleteModel($id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                    metaData: [
                        'action' => 'deleted',
                        'camo_id' => $camoActivity->camo_id,
                        'deleted_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_NO_CONTENT
                );
            }

            return to_route('camo_activities.index')->with('success', 'Activity deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Maneja las respuestas de error de forma unificada
     */
    protected function handleErrorResponse(
        string $message, 
        int $statusCode, 
        Request $request,
        ?Throwable $exception = null
    ) {
        if ($this->isApiRequest($request)) {
            return new ApiErrorResponse(
                message: $message,
                statusCode: $statusCode,
                exception: $exception
            );
        }

        return Inertia::render('Errors/Error', [
            'status' => $statusCode,
            'message' => $message
        ]);
    }
}