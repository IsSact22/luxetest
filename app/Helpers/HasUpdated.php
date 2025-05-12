<?php

namespace App\Helpers;

class HasUpdated
{
    public static function getModel($model): array
    {
        if ($model->isDirty()) {
            return [
                'update' => true,
                'model' => $model,
            ];
        } else {
            return [
                'update' => false,
                'model' => $model,
            ];
        }
    }
}
