<?php

namespace App\Http\Controllers;

use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreCamoActivityRequest;
use App\Http\Requests\UpdateCamoActivityRequest;
use App\Http\Resources\CamoActivityResource;
use App\Http\Resources\CamoResource;
use App\Models\Camo;
use App\Models\CamoActivity;
use App\Repositories\CamoActivityRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class CamoActivityController extends Controller
{
    public function __construct(protected CamoActivityRepository $activity)
    {
        parent::__construct();
        $this->middleware(HandlePrecognitiveRequests::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        try {
            $this->authorize('viewAny', CamoActivity::class);
            $activities = $this->activity->getAll($request);
            $resource = CamoActivityResource::collection($activities);

            return InertiaResponse::content('CamoActivities/Index', ['resource' => $resource]);
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'description' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        try {
            $this->authorize('create', CamoActivity::class);

            return InertiaResponse::content('CamoActivities/Create');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCamoActivityRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', CamoActivity::class);
            $payload = precognitive(static fn($bail) => $request->validated());
            $this->activity->newModel($payload);

            return to_route('camos.show', $payload['camo_id'])->with('success', 'CAMO Activity created successfully');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('view', $camoActivity);
            $resource = new CamoActivityResource($camoActivity);

            return InertiaResponse::content('CamoActivities/Show', ['resource' => $resource]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('update', $camoActivity);
            $camo = Camo::query()->findOrFail($camoActivity->camo_id);
            $camoResource = new CamoResource($camo);
            $resource = new CamoActivityResource($camoActivity);

            return InertiaResponse::content('CamoActivities/Edit', [
                'resource' => $resource,
                'camo' => $camoResource,
            ]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCamoActivityRequest $request, string $id): RedirectResponse|Response
    {
        try {
            //dd($request->validated());
            $camoActivity = $this->activity->getById($id);
            $this->authorize('update', $camoActivity);
            $payload = precognitive(static fn($bail) => $request->validated());
            $this->activity->updateModel($payload, $id);

            return to_route('camos.show', $id)->with('success', 'Activity update successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Exception|Throwable|QueryException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response|RedirectResponse
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('delete', $camoActivity);
            $this->activity->deleteModel($id);

            return to_route('camo_activities.index')->with('success', 'Activity deleted successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
