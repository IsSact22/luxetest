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
    public function __invoke(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $validated = $request->validate([
                'id' => ['required', 'exists:camo_activities,id'],
                'images' => ['required', 'array', 'max:10'],
                'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            ]);

            $camoActivity = \App\Models\CamoActivity::query()->findOrFail($validated['id']);
            $camoActivity->addMultipleMediaFromRequest(['images'])
                ->each(static fn ($fileAdder) => $fileAdder->toMediaCollection($camoActivity->mediaCollectionName));

            return redirect()->route('camo_activities.edit', $validated['id'])->with('success', 'Images uploaded successfully');

        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['error' => 'Activity not found']);
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'Error uploading images. Please try again.']);
        }
    }
}
