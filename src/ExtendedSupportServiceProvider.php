<?php

namespace Aybarsm\Laravel\Support;

use Illuminate\Support\ServiceProvider;
class ExtendedSupportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/extended-support.php',
            'extended-support'
        );

        $this->publishes([
            __DIR__ . '/../config/extended-support.php' => config_path('extended-support.php'),
        ], 'config');
    }

    public function boot(): void
    {
        foreach(config('extended-support.mixins.load') as $class){
            if (! class_exists($class) || ! defined("{$class}::BIND")) continue;
            if (! class_exists($bind = $class::BIND) || ! method_exists($bind, 'mixin')) continue;

            $bind::mixin(new $class());
        }
    }
}
