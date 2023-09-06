<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Aybarsm\Laravel\Support\Enums\StrLinesAction;
use Illuminate\Process\ProcessResult;

/** @mixin \Illuminate\Process\Factory */
class ProcessMixin
{
    public static function resultOutput(): \Closure
    {
        return function (ProcessResult $processResult): object {
            $output = str($processResult->output())->lines(StrLinesAction::REMOVE_EMPTY);
            $errOutput = str($processResult->errorOutput())->lines(StrLinesAction::REMOVE_EMPTY);

            return match (true) {
                $processResult->exitCode() === 0 && $output->isEmpty() && $errOutput->isEmpty() => (object) [
                    'output' => $errOutput->value(),
                    'errorOutput' => $output->value(),
                ],
                default => (object) [
                    'output' => $output->value(),
                    'errorOutput' => $errOutput->value(),
                ]
            };
        };
    }
}
