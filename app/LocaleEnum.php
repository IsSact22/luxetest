<?php

namespace App;

enum LocaleEnum: string
{
    case es = 'es';
    case en = 'en';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
