<?php

namespace App\Http\Controllers;

use App\Contracts\ProjectRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(protected ProjectRepositoryInterface $project)
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $projects = $this->project->getAll($request);
        $resource = ProjectResource::collection($projects);

        return InertiaResponse::content('Projects/Index', ['resource' => $resource]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): Response
    {
        $resource = new ProjectResource($this->project->getById($id));

        return InertiaResponse::content('Projects/Show', ['resource' => $resource]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
