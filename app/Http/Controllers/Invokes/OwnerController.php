<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $ownerRole = \Spatie\Permission\Models\Role::query()->where('name', 'owner')->first();

        $users = $ownerRole->users;

        return response()->json([
            'owners' => $users,
        ], 200);
    }
}
