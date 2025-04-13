<?php

namespace Aybarsm\Laravel\Support;

use Aybarsm\Laravel\Support\Contracts\ExtendedSupportContract;
use Illuminate\Support\ServiceProvider;

final class ExtendedSupportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/extended-support.php',
            'extended-support'
        );

        $this->app->singletonIf(ExtendedSupportContract::class, ExtendedSupport::class);

        $this->app->alias(ExtendedSupportContract::class, 'extended-support');
        $this->booting(fn (ExtendedSupportContract $es) => event('extended-support.booting'));
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/extended-support.php' => config_path('extended-support.php'),
            ], 'config');
        }
    }
}
