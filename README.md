## What It Does
It contains custom mixin classes for built-in macroable facades and also can be configured to load external mixins dynamically.

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
        'load' => [
            Aybarsm\Laravel\Support\Mixins\MixinStr::class,
            Aybarsm\Laravel\Support\Mixins\MixinArr::class,
            Aybarsm\Laravel\Support\Mixins\MixinFile::class,
            Aybarsm\Laravel\Support\Mixins\MixinRule::class,
        ]
    ]
];
```

## Custom Mixins

You can create any new mixin and define the macroable class by BIND constant:

```php
class MixinArr
{
    const BIND = \Illuminate\Support\Arr::class;

    public static function toObject(): \Closure
    {
        return fn (array|object $arr, int $flags = JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT): object => json_decode(json_encode($arr, $flags));
    }
}
```
