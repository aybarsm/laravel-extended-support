<?php

namespace Aybarsm\Laravel\Support\Contracts;

interface ExtendedSupportInterface
{
    public function addMixin(string|array $class): static;

    public function setReplace(bool $replace = true): static;

    public function setClassAutoload(bool $autoload = true): static;

    public function setBindPattern(string $pattern): static;

    public function loadMissing(bool $force = false): static;

    public function isValidMixin(string $class): bool;

    public function isValidBind(string $class): bool;

    public function resolveBind(string $class): ?string;

    public function getLoadedMixins(): array;
}
