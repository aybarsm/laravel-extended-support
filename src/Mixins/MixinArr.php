<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Aybarsm\Laravel\Support\Enums\StrTrimSide;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MixinArr
{
    const BIND = \Illuminate\Support\Arr::class;

    public static function toObject(): \Closure
    {
        return fn (array|object $arr, int $flags = JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT): object => json_decode(json_encode($arr, $flags));
    }

    public static function toArray(): \Closure
    {
        return fn (object|array $obj, int $flags = JSON_NUMERIC_CHECK): array => json_decode(json_encode($obj, $flags), true);
    }

    public static function contains(): \Closure
    {
        return fn (mixed $needle, array $haystack, bool $strict = false): bool => in_array($needle, $haystack, $strict);
    }

    // Backwards compatibility
    public static function hasValue(): \Closure
    {
        return static::contains();
    }

    public static function strTrim(): \Closure
    {
        return fn (array $arr, $chars = " \t\n\r\0\x0B", StrTrimSide $side = StrTrimSide::BOTH): array => Arr::map($arr, function ($val, $key) use($chars, $side) {
            if (! is_string($val)) return $val;
            return ($side === StrTrimSide::BOTH ? trim($val, $chars) : ($side === StrTrimSide::LEFT ? ltrim($val, $chars) : rtrim($val, $chars)));
        });
    }

    public static function strCall(): \Closure
    {
        return fn (array $arr, string $method, bool $appendValue = true, ...$args): array => Arr::map($arr, function ($val, $key) use($method, $appendValue, $args) {
            if (! is_string($val)) return $val;

            match($appendValue){
                false => array_unshift($args, $val),
                default => $args[] = $val
            };

            return Str::{$method}(...$args);
        });
    }

}
