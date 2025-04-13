<?php

namespace Aybarsm\Laravel\Support\Facades;

use Aybarsm\Laravel\Support\Contracts\ExtendedSupportContract;
use Illuminate\Support\Facades\Facade;

class ExtendedSupport extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ExtendedSupportContract::class;
    }
}
