<?php

namespace Aybarsm\Laravel\Support\Contracts;

interface ExtendedSupportInterface
{
    public function loadMixins(): void;

    public static function getLoaded(): array;

    public static function getFailed(): array;
}
