<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $roles = \Spatie\Permission\Models\Role::query()->where('name', '<>', 'super-admin')->pluck('name');

        return response()->json([
            'roles' => $roles,
        ], 200);
    }
}
