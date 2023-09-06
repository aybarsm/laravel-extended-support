<?php

namespace Aybarsm\Laravel\Support\Mixins;

use Aybarsm\Laravel\Support\Enums\StrLinesAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;

/** @mixin \Illuminate\Support\Stringable */
class StringableMixin
{
    public function lines(): \Closure
    {
        return function (StrLinesAction $action, ...$args): Stringable|Collection {
            return match ($action) {
                StrLinesAction::NORMALISE => $this->replaceMatches("/((\r?\n)|(\r\n?))/", "\n", $args[0] ?? -1),
                StrLinesAction::REPLACE => $this->replaceMatches("/((\r?\n)|(\r\n?))/", $args[0] ?? "\n", $args[1] ?? -1),
                StrLinesAction::REMOVE_EMPTY => $this->replaceMatches("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $args[0] ?? -1),
                StrLinesAction::SPLIT => $this->split("/((\r?\n)|(\r\n?))/", $args[0] ?? -1, $args[1] ?? 0),
                default => $this
            };
        };
    }

    public function shuffle(): \Closure
    {
        return fn (): Stringable => new static(str_shuffle($this->value));
    }
}
