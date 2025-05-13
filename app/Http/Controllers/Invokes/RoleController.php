<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $roles = Role::query()
            ->where('name', '<>', 'super-admin')
            ->where('name', '<>', 'crew')
            ->pluck('name');

        return response()->json([
            'roles' => $roles,
        ], 200);
    }
}
