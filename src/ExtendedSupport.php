<?php

namespace Aybarsm\Laravel\Support;

use Aybarsm\Laravel\Support\Contracts\ExtendedSupportContract;
use Aybarsm\Laravel\Support\Exceptions\ExtendedSupportException;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;

final class ExtendedSupport implements ExtendedSupportContract
{
    use Conditionable, Macroable;

    public function __construct(
        #[Config('extended-support', [])] public readonly array $config
    ) {
        Event::listen('extended-support.booting', fn () => self::resolve());
    }

    protected function resolve(): void
    {

    }

    protected function registerMixins(array $mixins): void
    {
        foreach ($mixins as $mixin => $bind) {
            if (! class_exists($mixin) || ! class_exists($bind) || ! method_exists($bind, 'mixin')) {
                continue;
            }

            $bind::mixin($mixin);
        }
    }

    public static function isMethodCallable(string $method, object|string $instance, ?\ReflectionClass $ref = null): true|string
    {
        $class = is_object($instance) ? get_class($instance) : $instance;

        if (blank($method)) {
            return "Method not provided for `{$class}`.";
        }

        $ref = $ref ?? new \ReflectionClass($class);

        if (! $ref->hasMethod($method)) {
            return "Method `{$method}` does not exist in `{$class}` instance.";
        }

        if (! $ref->getMethod($method)->isPublic()) {
            return "Method `{$method}` is not public in `{$class}` instance.";
        }

        return true;
    }

    public static function with(string|callable $callback, mixed $value, bool $parameters = false, bool $first = false): mixed
    {
        $useParams = $parameters && is_array($value);

        if (is_callable($callback)) {
            return $useParams ? $callback(...$value) : $callback($value);
        }

        $val = $first && $useParams ? $value[array_key_first($value)] : $value;

        $segments = preg_split('#@#', $callback, 2, PREG_SPLIT_NO_EMPTY);
        $class = $segments[0];

        throw_if(
            ! class_exists($class),
            ExtendedSupportException::class,
            "Class `{$class}` does not exist."
        );

        $method = $segments[1] ?? '__invoke';
        $ref = new ReflectionClass($class);
        $instance = app()->has($class) ? app()->get($class) : null;

        if ($instance !== null && self::isMethodCallable($method, $instance, $ref) === true) {
            if ($ref->getMethod($method)->isStatic()) {
                return $useParams ? $instance::{$method}(...$value) : $instance::{$method}($value);
            } else {
                return $useParams ? $instance->{$method}(...$value) : $instance->{$method}($value);
            }
        }

        throw_if(
            ! $ref->isInstantiable(),
            ExtendedSupportException::class,
            "Class `{$class}` is not instantiable."
        );

        throw_if(
            ($errMsg = self::isMethodCallable($method, $class, $ref)) !== true,
            ExtendedSupportException::class,
            $errMsg
        );

        if ($ref->getMethod($method)->isStatic()) {
            return $useParams ? $class::{$method}(...$value) : $callback::{$method}($value);
        }

        $instance = app()->makeWith($class, ($useParams ? $value : []));

        return $useParams ? $instance->{$method}(...$value) : $instance->{$method}($value);
    }
}
