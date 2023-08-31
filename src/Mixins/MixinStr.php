<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Illuminate\Support\Str;
class MixinStr
{
    const BIND = \Illuminate\Support\Str::class;

    public static function cleanWhitespace(): \Closure
    {
        return fn (string $str): string => preg_replace('/\s+/', ' ', $str);
    }

    public static function normaliseLines(): \Closure
    {
        return fn (string $str): string => preg_replace("/((\r?\n)|(\r\n?))/", "\n", $str);
    }

    public static function removeEmptyLines(): \Closure
    {
        return fn (string $str): string => trim(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $str), "\n");
    }

    public static function explodeLines(): \Closure
    {
        return fn (string $str): array => preg_split("/((\r?\n)|(\r\n?))/", $str);
    }

    public static function pathDir(): \Closure
    {
        return fn(string $path, bool $final = false): string => DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR) . ($final ? DIRECTORY_SEPARATOR : '');
    }

    public static function spread(): \Closure
    {
        return function(string $source, string $target, bool $leftOver = true): string
        {
            if (! $source || ! $target || ! Str::length($source) || ! Str::length($target)) return $target;

            $lenSource = Str::length($source);
            $lenTarget = Str::length($target);
            $rtr = '';

            for($i=1; $i <= $lenSource; $i++){
                $rtr .= Str::substr($target, $i-1, 1) . Str::substr($source, $i-1, 1);

                if ($i == $lenTarget || $i == $lenSource){
                    if ($leftOver && $i < $lenSource) $rtr .= Str::substr($source, $i, $lenSource);
                    if ($leftOver && $i < $lenTarget) $rtr .= Str::substr($target, $i, $lenTarget);
                    break;
                }
            }

            return $rtr;
        };
    }
}
