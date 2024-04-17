<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\CamoActivity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class MediaActivityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): \Illuminate\Http\RedirectResponse|\Inertia\Response
    {
        try {
            $validated = $request->validateWithBag('post', [
                'id' => ['required', 'exists:camo_activities,id'],
                'images' => ['required', 'array'],
                'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            ]);

            if (isset($validated['errors'])) {
                return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY]);
            }

            $camoActivities = CamoActivity::findOrFail($validated['id']);
            $camoActivities->addMultipleMediaFromRequest(['images'])
                ->each(fn ($fileAdder) => $fileAdder->toMediaCollection());

            return to_route('camo_activities.edit', $camoActivities->id)
                ->with(__('The images have been uploaded successfully.'));
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (\Throwable $e) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
