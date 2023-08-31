<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Illuminate\Support\Facades\File;

class MixinFile
{
    const BIND = \Illuminate\Filesystem\Filesystem::class;
    public static function hashContent(): \Closure
    {
        return function (string $path, string $algorithm = 'md5', bool $binary = false, array $options = []): string
        {
            if (!File::isReadable($path) || File::isDirectory($path)) return false;

            return hash(File::get($path), $algorithm, $binary, $options);
        };
    }

    public static function hasSameContentHash(): \Closure
    {
        return function (string $path, string $userString, string $algorithm = 'md5', bool $binary = false, array $options = []): string
        {
            if (!File::isReadable($path) || File::isDirectory($path)) return false;

            return hash_equals(hash(File::get($path), $algorithm, $binary, $options), $userString);
        };
    }

}
