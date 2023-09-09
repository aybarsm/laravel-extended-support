<?php

use Aybarsm\Laravel\Support\Enums\ProcessReturnType;
use Illuminate\Process\ProcessResult;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

if (! function_exists('senv')) {
    // Safe & base64 decoding env function
    function senv(string $key, mixed $default = null): mixed
    {
        $val = str(env($key));

        return $val->isEmpty() ? $default : ($val->startsWith('base64:') ? $val->after('base64:')->value() : $val->value());
    }
}

if (! function_exists('ts')) {
    // ISO8601Zulu Timestamp function - Replaceable separators for safe file names
    function ts(string $timezone = 'UTC', string $separatorDate = null, string $separatorHour = null): string
    {
        $ts = now($timezone)->toIso8601ZuluString();

        $searchReplace = match (true) {
            ! is_null($separatorDate) && ! is_null($separatorHour) => [['-', ':'], [$separatorDate, $separatorHour]],
            ! is_null($separatorDate) => ['-', $separatorDate],
            ! is_null($separatorHour) => [':', $separatorHour],
            default => []
        };

        return boolval(count($searchReplace)) ? str($ts)->replace($searchReplace[0], $searchReplace[1])->value() : $ts;
    }
}

if (! function_exists('getRequestIP')) {
    // Get real request IP if behind cloudflare
    function getRequestIP($request = null): string
    {
        $useRequest = $request ?? request();

        return $useRequest->header('cf-connecting-ip', $useRequest->ip());
    }
}

if (! function_exists('pathDir')) {
    function pathDir(string $path, bool $trailingSlash = false, bool $safe = true): string
    {
        if ($safe && empty($path)) {
            return $trailingSlash ? DIRECTORY_SEPARATOR : '';
        }

        return DIRECTORY_SEPARATOR.trim($path, DIRECTORY_SEPARATOR).($trailingSlash ? DIRECTORY_SEPARATOR : '');
    }
}

if (! function_exists('vendor_path')) {
    // Safe & base64 decoding env function
    function vendor_path(string $author = '', string $package = '', string $path = ''): string
    {
        return base_path('vendor'.pathDir($author).pathDir($package).pathDir($path));
    }
}

if (! function_exists('process_return')) {
    function process_return(ProcessResult $processResult, ProcessReturnType $returnType): bool|object|string
    {
        return match ($returnType) {
            ProcessReturnType::FAILED => $processResult->failed(),
            ProcessReturnType::EXIT_CODE => $processResult->exitCode(),
            ProcessReturnType::OUTPUT => Process::resultOutput($processResult)->output,
            ProcessReturnType::ERROR_OUTPUT => Process::resultOutput($processResult)->errorOutput,
            ProcessReturnType::INSTANCE => $processResult,
            ProcessReturnType::ALL_OUTPUT => Process::resultOutput($processResult),
            default => $processResult->successful()
        };
    }
}

if (! function_exists('array_change_key_case_recursive')) {
    function array_change_key_case_recursive(array $arr, int $case = CASE_LOWER): array
    {
        return array_map(function ($item) use ($case) {
            return is_array($item) ? array_change_key_case_recursive($item, $case) : $item;
        }, array_change_key_case($arr, $case));
    }
}

if (! function_exists('sconfig')) {
    // Key case safe config function
    function sconfig(string $key, mixed $default = null): mixed
    {
        $confBase = array_change_key_case_recursive(config()->all());

        return Arr::get($confBase, Str::lower($key), $default);
    }
}

if (! function_exists('str_case')) {
    function str_case(string $str, int $case = null): string
    {
        return match ($case) {
            CASE_LOWER => Str::lower($str),
            CASE_UPPER => Str::upper($str),
            default => $str
        };
    }
}
