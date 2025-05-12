<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasModelName
{
    protected string $modelName;

    public function __construct()
    {
        parent::__construct();
        $this->modelName = $this->getModelName();
    }

    protected function getModelName(): string
    {
        $className = static::class;

        return Str::replaceLast('Controller', '', class_basename($className));
    }
}
