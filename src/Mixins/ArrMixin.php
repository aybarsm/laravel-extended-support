<?php

namespace Aybarsm\Laravel\Support\Mixins;

/** @mixin \Illuminate\Support\Arr */
class ArrMixin
{
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

    public static function diff(): \Closure
    {
        return fn (array ...$arrays): array => call_user_func_array('array_diff', $arrays);
    }
}
