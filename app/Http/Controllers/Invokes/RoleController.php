<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $roles = Role::where('name', '<>', 'super-admin')->pluck('name');

        return response()->json([
            'roles' => $roles,
        ], 200);
    }
}
