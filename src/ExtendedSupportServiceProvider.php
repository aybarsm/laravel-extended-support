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

        $providerExtendedSupport = config('extended-support.providers.extended_support', \Aybarsm\Laravel\Support\ExtendedSupport::class);

        $this->app->singleton('extended-support',
            fn ($app) => new $providerExtendedSupport(
                config('extended-support.mixins.load', []),
                config('extended-support.mixins.replace', true),
                config('extended-support.patterns.mixin_bind', '/.*@mixin\s*([^\s*]+)[\s\S]*/'),
                config('extended-support.runtime.class_autoload', true),
            )
        );
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
}
