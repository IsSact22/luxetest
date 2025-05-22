<?php

namespace App;

enum ActivityStatus: string
{
    case pending = 'pending';
    case in_progress = 'in_progress';
    case completed = 'completed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
