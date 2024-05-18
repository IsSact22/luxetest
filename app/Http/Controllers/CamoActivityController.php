<?php

namespace App\Http\Controllers;

use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreCamoActivityRequest;
use App\Http\Requests\UpdateCamoActivityRequest;
use App\Http\Resources\CamoActivityResource;
use App\Repositories\CamoActivityRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
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
    public function index(Request $request): \Inertia\Response
    {
        $activities = $this->activity->getAll($request);
        $resource = CamoActivityResource::collection($activities);

        return InertiaResponse::content('CamoActivities/Index', ['resource' => $resource]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return InertiaResponse::content('CamoActivities/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCamoActivityRequest $request): RedirectResponse
    {
        $this->activity->newActivity($request->all());

        return to_route('camo_activities.index')->with('success', 'CAMO Activity created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): \Inertia\Response
    {
        try {
            $camo = $this->activity->getById($id);
            $resource = new CamoActivityResource($camo);

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
    public function edit(string $id): \Inertia\Response
    {
        try {
            $camo = $this->activity->getById($id);
            $resource = new CamoActivityResource($camo);

            return InertiaResponse::content('CamoActivities/Edit', ['resource' => $resource]);
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
    public function update(UpdateCamoActivityRequest $request, string $id): RedirectResponse|\Inertia\Response
    {
        try {
            $camoId = $request->get('camo_id');
            $this->activity->updateActivity($request->all(), $id);

            return to_route('camos.show', $camoId)->with('success', 'Activity update successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Inertia\Response|RedirectResponse
    {
        try {
            $this->activity->deleteActivity($id);

            return to_route('camo_activities.index')->with('success', 'Activity deleted successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
