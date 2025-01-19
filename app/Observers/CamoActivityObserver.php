<?php

namespace App\Observers;

use App\Mail\CamoActivityCreated;
use App\Mail\CamoActivityUpdated;
use App\Models\CamoActivity;
use Illuminate\Support\Facades\Mail;

class CamoActivityObserver
{
    /**
     * Handle the CamoActivity "created" event.
     */
    public function created(CamoActivity $camoActivity): void
    {
        $camo = $camoActivity->camo;
        $owner = $camo->owner;
        Mail::to($owner)->send(new CamoActivityCreated($camoActivity));
    }

    /**
     * Handle the CamoActivity "updated" event.
     */
    public function updated(CamoActivity $camoActivity): void
    {
        $camo = $camoActivity->camo;
        $owner = $camo->owner;
        Mail::to($owner)->send(new CamoActivityUpdated($camoActivity));
    }

    /**
     * Handle the CamoActivity "deleted" event.
     */
    public function deleted(CamoActivity $camoActivity): void
    {
        //
    }

    /**
     * Handle the CamoActivity "restored" event.
     */
    public function restored(CamoActivity $camoActivity): void
    {
        //
    }

    /**
     * Handle the CamoActivity "force deleted" event.
     */
    public function forceDeleted(CamoActivity $camoActivity): void
    {
        //
    }
}
