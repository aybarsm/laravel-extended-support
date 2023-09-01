<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
/** @mixin \Illuminate\Console\Command */
class CommandMixin
{
    public static function stubNormalise(): \Closure
    {
        return function (string $stub): string {
            $stub = File::isFile($stub) ? File::get($stub) : $stub;

            return preg_replace_callback('/{{\s*([^}]+)\s*}}/', fn ($matches) => Str::wrap(trim($matches[1]), '{{ ', ' }}'), $stub);
        };
    }
}
