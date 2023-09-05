<?php

namespace Aybarsm\Laravel\Support\Mixins;

/** @mixin \Illuminate\Support\Str */
class StrMixin
{
    public static function normaliseLines(): \Closure
    {
        return fn (?string $str): string => is_null($str) ? '' : static::replaceLines($str, "\n");
    }

    public static function removeEmptyLines(): \Closure
    {
        return fn (?string $str): string => trim(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $str), "\n");
    }

    public static function explodeLines(): \Closure
    {
        return fn (string $str): array => preg_split("/((\r?\n)|(\r\n?))/", $str);
    }

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
            $provider = config('extended-support.providers.supplements.str.semver', \Aybarsm\Laravel\Support\Supplements\Str\SemVer::class);

            return new $provider($ver);
        };
    }

    public static function isSemVer(): \Closure
    {
        return function (string $ver): bool {
            $provider = config('extended-support.providers.supplements.str.semver', \Aybarsm\Laravel\Support\Supplements\Str\SemVer::class);

            return $provider::validate($ver);
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
