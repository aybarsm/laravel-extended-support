## What It Does
It contains custom mixin classes for built-in macroable facades and also can be configured to load external mixins dynamically. It also includes some custom functions and helper traits.

## Installation

You can install the package via composer:

```bash
composer require aybarsm/laravel-extended-support
```

You can publish the config file by:

```bash
php artisan vendor:publish --provider="Aybarsm\Laravel\Support\ExtendedSupportServiceProvider" --tag=config
```

## Configure Mixins

You can remove or add new mixins to the load list.

```php
return [
    'mixins' => [
        'replace' => true,
        'load' => [
            Aybarsm\Laravel\Support\Mixins\StrMixin::class,
            Aybarsm\Laravel\Support\Mixins\ArrMixin::class,
            Aybarsm\Laravel\Support\Mixins\FileMixin::class,
            Aybarsm\Laravel\Support\Mixins\RuleMixin::class,
            Aybarsm\Laravel\Support\Mixins\ApplicationMixin::class,
            Aybarsm\Laravel\Support\Mixins\CommandMixin::class,
        ],
    ],
];
```

## Custom Mixins

You can create any new mixin by artisan command. The command will ask the class name and also provide the full list of classes that uses macroable trait to select easily.

```bash
php artisan make:mixin ArrMixin
```

[![Easy selection of classes using Macroable trait](https://i.postimg.cc/rsKWSq99/Screenshot-2023-09-01-at-21-22-23-2.png)](https://postimg.cc/bSfsPcWG)

You can publish the stubs by:

```bash
php artisan vendor:publish --provider="Aybarsm\Laravel\Support\ExtendedSupportServiceProvider" --tag=stubs
```

Or you can manually create a class for mixin and identify the macroable class by @mixin annotation and add it to configuration to be loaded:

```php
<?php

namespace App\Mixins;

/** @mixin \Illuminate\Support\Arr */

class ArrMixin
{
    public static function toObject(): \Closure
    {
        return fn (array|object $arr, int $flags = JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT): object => json_decode(json_encode($arr, $flags));
    }
}
```

```php
return [
    'mixins' => [
        'replace' => true,
        'load' => [
            Aybarsm\Laravel\Support\Mixins\StrMixin::class,
            Aybarsm\Laravel\Support\Mixins\ArrMixin::class,
            Aybarsm\Laravel\Support\Mixins\FileMixin::class,
            Aybarsm\Laravel\Support\Mixins\RuleMixin::class,
            Aybarsm\Laravel\Support\Mixins\ApplicationMixin::class,
            Aybarsm\Laravel\Support\Mixins\CommandMixin::class,
            App\Mixins\ArrMixin::class,
        ],
    ],
];
```

## Helper Traits:

### EnumHelper:
```php
use Aybarsm\Laravel\Support\Traits\EnumHelper;

enum StrTrimSide: int
{
    use EnumHelper;

    case BOTH = 0;
    case LEFT = 1;
    case RIGHT = 2;
}
```
