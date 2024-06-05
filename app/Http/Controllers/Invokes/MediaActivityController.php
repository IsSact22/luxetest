<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

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
                'images' => ['required', 'array', 'max:10'],
                'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            ]);

            if (isset($validated['errors'])) {
                return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY]);
            }

            $camoActivity = \App\Models\CamoActivity::query()->findOrFail($validated['id']);
            $camoActivity->addMultipleMediaFromRequest(['images'])
                ->each(static fn ($fileAdder) => $fileAdder->toMediaCollection($camoActivity->mediaCollectionName));

            return to_route('camo_activities.edit', $camoActivity->id)
                ->with(__('The images have been uploaded successfully.'));
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
