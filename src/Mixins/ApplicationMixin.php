<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Illuminate\Support\Arr;

/** @mixin \Illuminate\Foundation\Application */
class ApplicationMixin
{
    public static function findClasses(): \Closure
    {
        return function (\Closure $callback, bool $keepKeys = true): array {
            $using = Arr::where(get_declared_classes(), function ($val, $key) use ($callback) {
                return $callback($val, $key);
            });

            return $keepKeys ? $using : array_values($using);
        };
    }

    public static function classesUse(): \Closure
    {
        return function (string $trait, bool $autoload = true, bool $recursive = false, bool $keepKeys = true): array {
            if (! trait_exists($trait, $autoload)) {
                return [];
            }

            return static::findClasses(fn ($val, $key) => Arr::exists($recursive ? class_uses_recursive($val) : class_uses($val, $autoload), $trait), $keepKeys);
        };
    }

    public static function classesHasMethod(): \Closure
    {
        return fn (string $method, bool $keepKeys = true): array => static::findClasses(fn ($val, $key) => method_exists($val, $method), $keepKeys);
    }

    public static function classesOfSubclass(): \Closure
    {
        return function (string $class, bool $autoload = true, bool $allowString = true, bool $keepKeys = true): array {
            if (! class_exists($class, $autoload)) {
                return [];
            }

            return static::findClasses(fn ($val, $key) => is_subclass_of($val, $class, $allowString), $keepKeys);
        };
    }

    public static function getMacroables(): \Closure
    {
        return fn (bool $recursive = false, bool $keepKeys = true): array => static::classesUse('Illuminate\Support\Traits\Macroable', true, $recursive, $keepKeys);
    }
}
