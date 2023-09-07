<?php

namespace Aybarsm\Laravel\Support;

use Aybarsm\Laravel\Support\Console\Commands\MakeMixinCommand;
use Aybarsm\Laravel\Support\Contracts\ExtendedSupportInterface;
use Illuminate\Support\ServiceProvider;

class ExtendedSupportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/extended-support.php',
            'extended-support'
        );

        $this->publishes([
            __DIR__.'/../config/extended-support.php' => config_path('extended-support.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../stubs/mixin.stub' => base_path('stubs/mixin.stub'),
        ], 'stubs');

        $providerExtendedSupport = sconfig('extended-support.concretes.ExtendedSupport', \Aybarsm\Laravel\Support\ExtendedSupport::class);

        $this->app->singleton(ExtendedSupportInterface::class,
            fn ($app) => new $providerExtendedSupport(
                config('extended-support.mixins.load', []),
                config('extended-support.mixins.replace', true),
                config('extended-support.runtime.class_autoload', true),
            )
        );

        $this->app->alias(ExtendedSupportInterface::class, 'extended-support');
    }

    public function boot(): void
    {
        app('extended-support')->loadMissing(true);

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeMixinCommand::class,
            ]);
        }
    }

    public function provides(): array
    {
        return [
            ExtendedSupportInterface::class, 'extended-support',
        ];
    }
}
