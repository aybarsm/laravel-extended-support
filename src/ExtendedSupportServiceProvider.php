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

        $concrete = sconfig('extended-support.concretes.ExtendedSupport', \Aybarsm\Laravel\Support\ExtendedSupport::class);

        $this->app->singleton(ExtendedSupportInterface::class,
            fn ($app) => new $concrete(
                sconfig('extended-support.runtime.replace_existing', true),
                sconfig('extended-support.runtime.class_autoload', true),
                sconfig('extended-support.runtime.required_trait', 'Illuminate\Support\Traits\Macroable'),
                sconfig('extended-support.runtime.bind_pattern', '/@mixin\s*([^\s*]+)/'),
                sconfig('extended-support.runtime.load', []),
            )
        );

        $this->app->alias(ExtendedSupportInterface::class, 'extended-support');
    }

    public function boot(ExtendedSupportInterface $contract): void
    {
        $contract->loadMixins();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/extended-support.php' => config_path('extended-support.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../stubs/mixin.stub' => base_path('stubs/mixin.stub'),
            ], 'stubs');

            $this->commands([
                MakeMixinCommand::class,
            ]);
        }
    }
}
