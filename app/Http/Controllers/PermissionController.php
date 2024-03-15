<?php

namespace App\Http\Controllers;

use App\Helpers\InertiaResponse;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Nette\Schema\ValidationException;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $permissions = Permission::query()
            ->paginate()
            ->withQueryString();
        $resource = PermissionResource::collection($permissions);

        return InertiaResponse::content('Permissions/Index', ['resource' => $resource]);
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
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse|ResponseAlias|RedirectResponse
    {
        try {
            $permission->update($request->all());

            return to_route('permissions.index');
        } catch (ValidationException $e) {
            return Inertia::render('Errors/Error')
                ->toResponse($request)
                ->setStatusCode(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return Inertia::render('Errors/Error')->toResponse($request)->setStatusCode(ResponseAlias::HTTP_ACCEPTED);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
