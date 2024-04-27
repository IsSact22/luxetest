<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CamController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $camRole = \Spatie\Permission\Models\Role::query()->where('name', 'cam')->first();

        $users = $camRole->users;

        return response()->json([
            'cams' => $users,
        ], 200);
    }
}
