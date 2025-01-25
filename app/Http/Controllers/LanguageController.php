<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function changeLocale(Request $request): JsonResponse
    {
        $locale = $request->input('locale');
        //Guardar el local en la sesión
        session(['locale' => $locale]);
        App::setLocale($locale);

        return response()->json(['success' => true, 'locale' => $locale]);
    }
}
