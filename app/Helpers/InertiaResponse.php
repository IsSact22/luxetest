<?php

namespace App\Helpers;

use Inertia\Inertia;

class InertiaResponse
{
    public static function content($component, $props = [], $options = []): \Inertia\Response
    {
        return Inertia::render($component, $props);
    }
}
