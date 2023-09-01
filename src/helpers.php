<?php

if (! function_exists('senv')) {
    // Safe & base64 decoding env function
    function senv(string $key, mixed $default = null): mixed
    {
        $rtr = env($key);

        if ($rtr === null || str($rtr)->length() === 0) {
            return $default;
        }

        if (str($rtr)->startsWith('base64:')) {
            return base64_decode(str($rtr)->after('base64:'));
        }

        return $rtr;
    }
}

if (! function_exists('ts')) {
    // ISO8601Zulu Timestamp function - Replaceable separators for safe file names
    function ts(string $timezone = 'UTC', string $separatorDate = '', string $separatorHour = ''): string
    {
        $ts = now($timezone)->toIso8601ZuluString();

        $searchReplace = match (true) {
            ! empty($separatorDate) && ! empty($separatorHour) => [['-', ':'], [$separatorDate, $separatorHour]],
            ! empty($separatorDate) => ['-', $separatorDate],
            ! empty($separatorHour) => [':', $separatorHour],
            default => []
        };

        return (boolval(count($searchReplace)) ? str($ts)->replace($searchReplace[0], $searchReplace[1]) : $ts)->value();
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
