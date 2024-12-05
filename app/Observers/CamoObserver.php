<?php

namespace App\Observers;

use App\Models\Aircraft;
use App\Models\Camo;
use App\Models\User;

class CamoObserver
{
    /**
     * Handle the Camo "created" event.
     */
    public function created(Camo $camo): void
    {
        // Obtener el avión y el dueño
        $aircraft = Aircraft::find($camo->aircraft_id);
        $owner = User::find($camo->owner_id);
        if ($aircraft && $owner) {
            // Verificar si ya existe la relación en la tabla pivote
            $exists = $aircraft->aircraftOwner()
                ->where('owner_aircraft.owner_id', $owner->id)
                ->exists();

            if ($exists) {
                // Si existe, actualiza el campo updated_at del pivote
                $aircraft->aircraftOwner()->updateExistingPivot($owner->id, [], [
                    'updated_at' => now(),
                ]);
            } else {
                // Si no existe, crea la nueva relación
                $aircraft->aircraftOwner()->attach($owner);
            }
        }
    }

    /**
     * Handle the Camo "updated" event.
     */
    public function updated(Camo $camo): void
    {
        //
    }

    /**
     * Handle the Camo "deleted" event.
     */
    public function deleted(Camo $camo): void
    {
        //
    }

    /**
     * Handle the Camo "restored" event.
     */
    public function restored(Camo $camo): void
    {
        //
    }

    /**
     * Handle the Camo "force deleted" event.
     */
    public function forceDeleted(Camo $camo): void
    {
        //
    }
}
