<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Aybarsm\Laravel\Support\Supplements\Str\SemVer;
use Illuminate\Support\Str;

/** @mixin \Illuminate\Support\Str */
class StrMixin
{
    public static function cleanWhitespace(): \Closure
    {
        return fn (?string $str): string => is_null($str) ? '' : preg_replace('/\s+/', ' ', $str);
    }

    public static function replaceLines(): \Closure
    {
        return fn (?string $str, string|float|int $replace): string => is_null($str) ? '' : preg_replace("/((\r?\n)|(\r\n?))/", $replace, $str);
    }

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

    public static function spread(): \Closure
    {
        return function (string $source, string $target, bool $leftOver = true): string {
            if (! $source || ! $target || ! Str::length($source) || ! Str::length($target)) {
                return $target;
            }

            $lenSource = Str::length($source);
            $lenTarget = Str::length($target);
            $rtr = '';

            for ($i = 1; $i <= $lenSource; $i++) {
                $rtr .= Str::substr($target, $i - 1, 1).Str::substr($source, $i - 1, 1);

                if ($i == $lenTarget || $i == $lenSource) {
                    if ($leftOver && $i < $lenSource) {
                        $rtr .= Str::substr($source, $i, $lenSource);
                    }
                    if ($leftOver && $i < $lenTarget) {
                        $rtr .= Str::substr($target, $i, $lenTarget);
                    }
                    break;
                }
            }

            return $rtr;
        };
    }

    public static function semVer(): \Closure
    {
        return fn (string $ver): SemVer => new SemVer($ver);
    }

    public static function isSemVer(): \Closure
    {
        return fn (string $semVer): bool => SemVer::validate($semVer);
    }

    public static function wrapSafe(): \Closure
    {
        return function (string $value, string $before, string $after = null): string {
            $before = ! empty($before) && Str::startsWith($value, $before) ? '' : $before;
            $after = ! empty($after) && Str::endsWith($value, $after) ? '' : $after;

            return ($before === '' && $after === '') ? $value : Str::wrap($value, $before, $after);
        };
    }
}
