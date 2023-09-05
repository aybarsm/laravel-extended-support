<?php

namespace Aybarsm\Laravel\Support\Supplements\Foundation;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\Support\Traits\Macroable;

class Annotation
{
    use Macroable;

    protected \ReflectionClass $reflection;

    protected ?Collection $document = null;

    protected ?Collection $methods = null;

    public function __construct(
        public readonly string $class
    ) {
        $this->reflection = new \ReflectionClass($this->class);
    }

    public static function make(string $class, bool $autoload = true): ?static
    {
        if (! class_exists($class, $autoload)) {
            return null;
        }

        return new static($class);
    }

    public function document(): Collection
    {
        if ($this->document === null) {
            $this->document = self::parseDocument($this->reflection->getDocComment());
        }

        return $this->document;
    }

    public static function parseDocumentMethod(string $method): ?array
    {
        $untilFuncArgs = str($method)->squish()->before('(')->value();
        $parsed = [
            'type' => 'method',
            'name' => Str::afterLast($untilFuncArgs, ' '),
            'static' => Str::startsWith($untilFuncArgs, 'static'),
            'returns' => str($untilFuncArgs)
                ->after(' ')
                ->before(' ')
                ->when(
                    fn (Stringable $string): bool => $string->startsWith('?'),
                    fn (Stringable $string): Stringable => $string->replace('?', 'null|')
                )
                ->split('/\|/', -1, PREG_SPLIT_NO_EMPTY)
                ->toArray(),
            'args' => str($method)
                ->squish()
                ->between('(', ')')
                ->split('/,/', -1, PREG_SPLIT_NO_EMPTY)
                ->map(function ($item, $key) {
                    $item = Str::squish($item);
                    $var = str($item)
                        ->after('$')
                        ->before('=')
                        ->trim()
                        ->value();

                    $accepts = str($item)
                        ->unless(
                            fn (Stringable $string): bool => $string->startsWith('$'),
                            fn (Stringable $string): Stringable => $string->before(' '),
                        )
                        ->when(
                            fn (Stringable $string): bool => $string->startsWith('?'),
                            fn (Stringable $string): Stringable => $string->replace('?', 'null|')
                        )
                        ->split('/\|/', -1, PREG_SPLIT_NO_EMPTY)
                        ->toArray();

                    $default = Str::contains($item, '=') ? str($item)->after('=')->trim()->value() : null;

                    return ['var' => $var, 'accepts' => $accepts, 'default' => $default];
                })
                ->toArray(),
            'entry' => $method,
        ];

        return $parsed;
    }

    public static function parseDocument(string $docComment): ?Collection
    {
        $docComment = str($docComment)->replace(['/', '*'], '')->squish();

        if ($docComment->isEmpty()) {
            return null;
        }

        $build = [];

        preg_replace_callback(
            pattern: '/@(\w+)\s([^@]*)/',
            callback: function ($matches) use (&$build) {
                $type = trim($matches[1]);
                $entry = trim($matches[2] ?? '');
                $build[] = match ($type) {
                    'method' => self::parseDocumentMethod($entry),
                    default => ['type' => $type, 'values' => Arr::wrap($entry)]
                };

                return $matches[1];
            },
            subject: $docComment->value(),
            flags: PREG_SET_ORDER
        );

        return collect(empty($build) ? [] : $build);
    }
}
