<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

/** @mixin \Illuminate\Foundation\Application */
class ApplicationMixin
{
    public static function classesUse(): \Closure
    {

        return function (string $trait, bool $autoload = true, bool $recursive = false, bool $keepKeys = true): array {
            if (! trait_exists($trait, $autoload)) {
                return [];
            }

            $using = Arr::where(get_declared_classes(), function ($val, $key) use ($trait, $autoload, $recursive) {
                return Arr::exists($recursive ? class_uses_recursive($val) : class_uses($val, $autoload), $trait);
            });

            return $keepKeys ? $using : array_values($using);
        };
    }

    public static function getMacroables(): \Closure
    {
        return fn (bool $recursive = false, bool $keepKeys = true): array => App::classesUse('Illuminate\Support\Traits\Macroable', true, $recursive, $keepKeys);
    }
}
