<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Illuminate\Support\Facades\File;

/** @mixin \Illuminate\Validation\Rule */
class RuleMixin
{
    public static function fileDirectory(): \Closure
    {
        return function ($attribute, $value, $fail): void {
            if (! File::isDirectory($value)) {
                $fail('The :attribute path must be a directory.');
            }
        };
    }

    public static function fileExists(): \Closure
    {
        return function ($attribute, $value, $fail): void {
            if (! File::exists($value)) {
                $fail('The :attribute file does not exist.');
            }
        };
    }

    public static function fileReadable(): \Closure
    {
        return function ($attribute, $value, $fail): void {
            if (! File::isReadable($value)) {
                $fail('The :attribute is not readable.');
            }
        };
    }

    public static function fileWritable(): \Closure
    {
        return function ($attribute, $value, $fail): void {
            if (! File::isWritable($value)) {
                $fail('The :attribute is not writable.');
            }
        };
    }
}
