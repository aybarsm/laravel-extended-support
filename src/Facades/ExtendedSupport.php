<?php

namespace Aybarsm\Laravel\Support\Facades;

use Aybarsm\Laravel\Support\ExtendedSupport as ExtendedSupportManager;
use Illuminate\Support\Facades\Facade;

class ExtendedSupport extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ExtendedSupportManager::class;
    }
}
