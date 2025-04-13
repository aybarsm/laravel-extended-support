<?php

use function Illuminate\Filesystem\join_paths;

if (! function_exists('senv')) {
    // Safe & base64 decoding .env function
    function senv(string $key, mixed $default = null): mixed
    {
        $val = str(env($key));

        return $val->isEmpty() ? $default : ($val->startsWith('base64:') ? $val->after('base64:')->value() : $val->value());
    }
}

if (! function_exists('ts')) {
    // ISO8601Zulu Timestamp function - Replaceable separators for safe file names
    function ts(string $timezone = 'UTC', ?string $separatorDate = null, ?string $separatorHour = null): string
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

if (! function_exists('resolve_path')) {
    function resolve_path(string $basePath, ...$args): string
    {
        $bp = str($basePath)->trim();
        $segments = $bp->split(pquote('#%s#', null, DIRECTORY_SEPARATOR), -1, PREG_SPLIT_NO_EMPTY);

        if ($segments->isNotEmpty()) {
            $bp = str($segments->shift());

            foreach (array_reverse($segments->all()) as $segment) {
                array_unshift($args, $segment);
            }

            if ($bp->contains('::')) {
                if ($bp->after('::')->isNotEmpty()) {
                    $segment = $bp->after('::')->value();
                    array_unshift($args, $segment);
                    $bp = $bp->chopEnd($segment);
                }
            }

            $bp = $bp->value();
        }

        $bp = match (true) {
            $bp === '~' => $_SERVER['HOME'],
            $bp === '.' => getcwd(),
            $bp === 'bp::' => base_path(),
            $bp === 'sp::' => storage_path(),
            $bp === 'dp::' => database_path(),
            $bp === 'rp::' => resource_path(),
            default => (PHP_OS_FAMILY === 'Windows' ? $bp.DIRECTORY_SEPARATOR : DIRECTORY_SEPARATOR.$bp),
        };

        return join_paths($bp, ...$args);
    }
}

if (! function_exists('vendor_path')) {
    function vendor_path(...$args): string
    {
        return resolve_path(base_path(), 'vendor', ...$args);
    }
}

if (! function_exists('truthy')) {
    function truthy(mixed $value): bool
    {
        $value = is_string($value) ? strtolower($value) : $value;

        return in_array($value, ['yes', 'on', '1', 1, true, 'true'], true);
    }
}

if (! function_exists('falsy')) {
    function falsy(mixed $value): bool
    {
        $value = is_string($value) ? strtolower($value) : $value;

        return in_array($value, ['no', 'off', '0', 0, false, 'false'], true);
    }
}

if (! function_exists('request_ip')) {
    function request_ip($request = null): string
    {
        $useRequest = $request ?? request();

        return $useRequest->header('cf-connecting-ip', $useRequest->ip());
    }
}
