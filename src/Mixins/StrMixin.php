<?php

namespace Aybarsm\Laravel\Support\Mixins;

/** @mixin \Illuminate\Support\Str */
class StrMixin
{
    public static function pathDir(): \Closure
    {
        return fn (string $path, bool $trailingSlash = false): string => DIRECTORY_SEPARATOR.trim($path, DIRECTORY_SEPARATOR).($trailingSlash ? DIRECTORY_SEPARATOR : '');
    }

    public static function shuffle(): \Closure
    {
        return fn (string $string): string => str_shuffle($string);
    }

    public static function semVer(): \Closure
    {
        return function (string $ver) {
            $concrete = sconfig('extended-support.concretes.Supplements.Str.SemVer', \Aybarsm\Laravel\Support\Supplements\Str\SemVer::class);

            return new $concrete($ver);
        };
    }

    public static function isSemVer(): \Closure
    {
        return function (string $ver): bool {
            $concrete = sconfig('extended-support.concretes.Supplements.Str.SemVer', \Aybarsm\Laravel\Support\Supplements\Str\SemVer::class);

            return $concrete::validate($ver);
        };
    }

    public static function wrapSafe(): \Closure
    {
        return function (string $value, string $before = '', string $after = ''): string {
            if (empty($before) && empty($after)) {
                return $value;
            }

            return str($value)->start($before)->finish($after)->value();
        };
    }
}
