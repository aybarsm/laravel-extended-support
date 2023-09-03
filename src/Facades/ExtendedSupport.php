<?php

namespace Aybarsm\Laravel\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ExtendedSupport extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'extended-support';
    }
}
