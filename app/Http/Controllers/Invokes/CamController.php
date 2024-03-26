<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CamController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $camRole = Role::where('name', 'cam')->first();

        $users = $camRole->users;

        return response()->json([
            'cams' => $users,
        ], 200);
    }
}
