<?php

namespace Aybarsm\Laravel\Support;

use Aybarsm\Laravel\Support\Contracts\ExtendedSupportInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;

class ExtendedSupport implements ExtendedSupportInterface
{
    use Macroable;

    protected static bool $init;

    protected static array $failed = [];

    protected static array $loaded = [];

    public function __construct(
        public readonly bool $replaceExisting,
        public readonly bool $classAutoload,
        public readonly string $requiredTrait,
        public readonly string $bindPattern,
        public readonly array $loadList
    ) {

    }

    public function loadMixins(): void
    {
        if (isset(static::$init) || empty($this->loadList)) {
            return;
        }
        foreach ($this->loadList as $mixin) {
            if (is_null($bind = $this->resolveBind($mixin))) {
                static::$failed[] = $mixin;

                continue;
            }

            $this->addMixin($mixin, $bind);
        }

        static::$init = true;
    }

    protected function addMixin(string $mixin, string $bind): void
    {
        $bind::mixin(new $mixin(), $this->replaceExisting);

        static::$loaded[$mixin] = [
            'bind' => $bind,
            'methods' => Arr::where(get_class_methods($mixin), fn ($val, $key): bool => $bind::hasMacro($val)),
        ];
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
    protected function resolveBind(string $class): ?string
    {
        if (! $this->isValidMixin($class) || ($docComment = (new ReflectionClass($class))?->getDocComment()) === false) {
            return null;
        }

        $bind = str($docComment)->match($this->bindPattern);

        return $bind->isEmpty() || ! $this->isValidBind($bind->value()) ? null : $bind->value();
    }

    public static function getLoaded(): array
    {
        return static::$loaded;
    }

    public static function getFailed(): array
    {
        return static::$failed;
    }
}
