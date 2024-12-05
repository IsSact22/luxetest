<?php

namespace App;

enum ApprovalStatus: string
{
    case pending = 'pending';
    case approved = 'approved';
    case canceled = 'canceled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
