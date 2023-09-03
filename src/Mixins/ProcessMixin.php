<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Illuminate\Process\ProcessResult;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/** @mixin \Illuminate\Process\Factory */
class ProcessMixin
{
    public static function resultOutput(): \Closure
    {
        return function (ProcessResult $processResult): object {
            $output = Str::removeEmptyLines($processResult->output());
            $errorOutput = Str::removeEmptyLines($processResult->errorOutput());

            $return = match (true) {
                $processResult->exitCode() === 0 && empty($output) && ! empty($errorOutput) => ['output' => $errorOutput, 'errorOutput' => $output],
                default => ['output' => $output, 'errorOutput' => $errorOutput]
            };

            return Arr::toObject($return);
        };
    }
}
