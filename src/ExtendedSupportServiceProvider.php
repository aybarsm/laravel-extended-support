<?php

namespace Aybarsm\Laravel\Support;

use Aybarsm\Laravel\Support\Console\Commands\MakeMixinCommand;
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

        $this->app->singleton('extended-support', function ($app) {
            return new ExtendedSupport(
                config('extended-support.mixins.load', []),
                config('extended-support.mixins.replace', true),
            );
        });
    }

    public function boot(): void
    {
        app('extended-support')->loadMixins();

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeMixinCommand::class,
            ]);
        }
    }
}
