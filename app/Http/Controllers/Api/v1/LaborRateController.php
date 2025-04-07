<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;

use App\Contracts\LaborRateRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreLaborRateRequest;
use App\Http\Requests\UpdateLaborRateRequest;
use App\Http\Resources\LaborRateResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\CamoActivity;
use App\Models\LaborRate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Precognitive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class LaborRateController extends Controller
{
    public function __construct(protected LaborRateRepositoryInterface $laborRateRepository)
    {
        parent::__construct();
        $this->middleware([Precognitive::class])->only(['store', 'update']);
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
            $this->authorize('viewAny', LaborRate::class);
            $laborRates = $this->laborRateRepository->getAll($request);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: LaborRateResource::collection($laborRates),
                    metaData: [
                        'total' => $laborRates->total(),
                        'per_page' => $laborRates->perPage(),
                        'current_page' => $laborRates->currentPage()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('LaborRates/Index', [
                'resource' => LaborRateResource::collection($laborRates)
            ]);

        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $this->authorize('create', LaborRate::class);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                    message: 'Create form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('LaborRates/Create');
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaborRateRequest $request)
    {
        try {
            $this->authorize('create', LaborRate::class);
            $payload = precognitive(static fn($bail) => $request->validated());
            $laborRate = $this->laborRateRepository->newModel($payload);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new LaborRateResource($laborRate),
                    metaData: ['action' => 'created'],
                    statusCode: HttpResponse::HTTP_CREATED
                );
            }

            return to_route('labor-rates.index')->with('success', 'Camo Rate has been created.');
        } catch (AuthorizationException $e) {
            Log::error('Authorization error: '.$e->getMessage());
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
        } catch (Throwable $e) {
            Log::error('Error creating Labor Rate: '.$e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        try {
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('view', $laborRate);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new LaborRateResource($laborRate),
                    metaData: ['fetched' => now()->toDateTimeString()],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('LaborRates/Show', [
                'resource' => new LaborRateResource($laborRate)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request, $e);
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $id)
    {
        try {
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('view', $laborRate);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new LaborRateResource($laborRate),
                    message: 'Edit form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('LaborRates/Edit', [
                'resource' => new LaborRateResource($laborRate)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request, $e);
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaborRateRequest $request, int $id)
    {
        try {
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('update', $laborRate);
            $payload = precognitive(static fn($bail) => $request->validated());
            $updatedLaborRate = $this->laborRateRepository->updateModel($payload, $id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new LaborRateResource($updatedLaborRate),
                    metaData: [
                        'action' => 'updated',
                        'updated_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            // Lógica especial para redirección desde Camo Activity
            $camoActivityId = Session::get('camo_activity_id');
            if ($camoActivityId !== null) {
                Session::forget('camo_activity_id');
                Session::flash('success', 'La tarifa de la actividad se ha actualizado correctamente.');

                $camoActivity = CamoActivity::findOrFail($camoActivityId);
                $camoId = $camoActivity->camo_id;

                return to_route('camos.show', $camoId);
            }

            return to_route('labor-rates.index')->with('success', 'Camo Rate has been updated.');

        } catch (ModelNotFoundException $e) {
            Log::error('Model not found: '.$e->getMessage());
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request, $e);
        } catch (AuthorizationException $e) {
            Log::error('Authorization error: '.$e->getMessage());
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
        } catch (Throwable $e) {
            Log::error('Error updating Labor Rate: '.$e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('delete', $laborRate);
            $this->laborRateRepository->deleteModel($id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                    metaData: [
                        'action' => 'deleted',
                        'deleted_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_NO_CONTENT
                );
            }

            return to_route('labor-rates.index')->with('success', 'Camo Rate has been deleted.');
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request, $e);
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
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
            'message' => $message,
            'description' => $exception ? $exception->getMessage() : null
        ]);
    }
}