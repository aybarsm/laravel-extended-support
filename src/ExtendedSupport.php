<?php

namespace Aybarsm\Laravel\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;

class ExtendedSupport
{
    use Macroable;

    protected array $loadedMixins = [];

    public function __construct(
        protected array $mixins,
        protected bool $replaceMixins
    ) {

    }

    public function loadMixins(): array
    {
        foreach ($this->mixins as $class) {
            if (! class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);
            $bind = '\\'.trim(
                preg_replace('/.*@mixin\s*([^\s*]+)[\s\S]*/', '$1',
                    preg_replace('/\s+/', ' ', $reflection->getDocComment())
                ), ' \\');

            if (! class_exists($bind) || ! Arr::exists(class_uses($bind), 'Illuminate\Support\Traits\Macroable')) {
                continue;
            }

            $bind::mixin(new $class(), ! ($this->replaceMixins === false));

            $this->loadedMixins[$class] = ['bind' => $bind, 'macros' => []];
            foreach (get_class_methods($class) as $method) {
                if (! $bind::hasMacro($method)) {
                    continue;
                }
                $this->loadedMixins[$class]['macros'][] = $method;
            }
        }

        return $this->loadedMixins;
    }

    public function getLoadedMixins(): array
    {
        return $this->loadedMixins;
    }
}
