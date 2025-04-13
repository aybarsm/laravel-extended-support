<?php

namespace Aybarsm\Laravel\Support\Contracts;

interface ExtendedSupportContract
{
    public static function with(string|callable $callback, mixed $value, bool $parameters = false, bool $first = false): mixed;
}
