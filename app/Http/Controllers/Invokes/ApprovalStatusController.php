<?php

namespace App\Http\Controllers\Invokes;

use App\ApprovalStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprovalStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $statuses = ApprovalStatus::cases();
        $response = array_map(static function ($status) {
            return [
                'value' => $status->value,
                'label' => ucfirst($status->name), // Capitaliza el nombre del status
                'selected' => false, // Puedes manejar esto como desees
            ];
        }, $statuses);

        return response()->json($response);
    }
}
