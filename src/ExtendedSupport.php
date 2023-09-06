<?php

namespace Aybarsm\Laravel\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;

class ExtendedSupport
{
    use Macroable;

    protected string $requiredTrait = 'Illuminate\Support\Traits\Macroable';

    protected string $bindPattern = '/@mixin\s*([^\s*]+)/';

    protected string $bindReplace = '/.*@mixin\s*([^\s*]+)[\s\S]*/';

    protected static array $loaded = [];

    public function __construct(
        protected array $load,
        protected bool $replace,
        protected bool $classAutoload,
    ) {

    }

    public function addMixin(string|array $class): static
    {
        $add = Arr::where(array_diff(Arr::wrap($class), $this->load), fn ($val, $key) => class_exists($val, $this->classAutoload));

        if (! empty($add)) {
            $this->load = array_merge($this->load, $add);
        }

        return $this;
    }

    public function setReplace(bool $replace = true): static
    {
        $this->replace = $replace;

        return $this;
    }

    public function setClassAutoload(bool $autoload = true): static
    {
        $this->classAutoload = $autoload;

        return $this;
    }

    public function setBindPattern(string $pattern): static
    {
        $this->bindPattern = $pattern;

        return $this;
    }

    public function loadMissing(bool $force = false): static
    {
        $missing = $force ? $this->load : array_diff($this->load, array_keys(static::$loaded));

        foreach ($missing as $class) {

            if (is_null($bind = $this->resolveBind($class))) {
                continue;
            }

            $bind::mixin(new $class(), $this->replace);

            self::$loaded[$class] = [
                'bind' => $bind,
                'methods' => Arr::where(get_class_methods($class), fn ($val, $key): bool => $bind::hasMacro($val)),
            ];
        }

        return $this;
    }

    protected function isValidMixin(string $class): bool
    {
        return strlen($class) > 0 && class_exists($class, $this->classAutoload) && ! empty(get_class_methods($class));
    }

    protected function isValidBind(string $class): bool
    {
        return strlen($class) > 0 && class_exists($class, $this->classAutoload) && Arr::exists(class_uses($class), $this->requiredTrait);
    }

    /**
     * @throws \ReflectionException
     */
    public function resolveBind(string $class): ?string
    {
        if (! $this->isValidMixin($class) || ($docComment = (new ReflectionClass($class))?->getDocComment()) === false) {
            return null;
        }

        $bind = str($docComment)->match($this->bindPattern);

        return $bind->isEmpty() || ! $this->isValidBind($bind->value()) ? null : $bind->value();
    }

    public function getLoadedMixins(): array
    {
        return self::$loaded;
    }
}
