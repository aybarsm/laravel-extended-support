<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

/** @mixin \Illuminate\Filesystem\Filesystem */
class FileMixin
{
    public static function hashContent(): \Closure
    {
        return function (string $path, string $algorithm = 'md5', bool $binary = false, array $options = []): string {
            throw_if(! File::isReadable($path) || File::isDirectory($path), \InvalidArgumentException::class,
                "Path [{$path}] is not readable or is a directory.");

            return hash(File::get($path), $algorithm, $binary, $options);
        };
    }

    public static function hasSameContentHash(): \Closure
    {
        return function (string $path, string $userString, string $algorithm = 'md5', bool $binary = false, array $options = []): string {
            throw_if(! File::isReadable($path) || File::isDirectory($path), \InvalidArgumentException::class,
                "Path [{$path}] is not readable or is a directory.");

            return hash_equals(hash(File::get($path), $algorithm, $binary, $options), $userString);
        };
    }

    public static function firstExists(): \Closure
    {
        return fn (...$paths): string => Arr::first($paths, fn ($val, $key): bool => File::exists($val));
    }
}
