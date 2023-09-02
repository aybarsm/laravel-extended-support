<?php

namespace Aybarsm\Laravel\Support\Supplements\Str;

use Aybarsm\Laravel\Support\Enums\SemVerScope;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

class SemVer
{
    use Macroable;

    protected string $semVer;

    protected string $semVerOriginal;

    protected int $major;

    protected int $minor;

    protected int $patch;

    /**
     * @throws \Throwable
     */
    public function __construct(
        public readonly string $ver
    ) {
        $this->semVer = static::get($this->ver);

        throw_if(
            ! static::validate($this->semVer),
            \InvalidArgumentException::class,
            "Semantic Version could not be captured from [{$this->ver}]."
        );

        $this->semVerOriginal = $this->semVer;

        $this->major = $this->getScope(SemVerScope::MAJOR);
        $this->minor = $this->getScope(SemVerScope::MINOR);
        $this->patch = $this->getScope();
    }

    public static function get(string $ver): string
    {
        return preg_replace('/.*?(\d+\.\d+\.\d+).*/', '$1', $ver);
    }

    public static function validate(string $semVer): bool
    {
        return preg_match('/^(\d+\.\d+\.\d+)$/', $semVer);
    }

    public function getOriginal(): string
    {
        return $this->semVerOriginal;
    }

    public function getScope(SemVerScope $scope = SemVerScope::PATCH, bool $asInteger = true): string
    {
        $scopeVer = match ($scope) {
            SemVerScope::MAJOR => Str::before($this->semVer, '.'),
            SemVerScope::MINOR => Str::between($this->semVer, '.', '.'),
            default => Str::afterLast($this->semVer, '.')
        };

        return $asInteger ? intval($scopeVer) : $scopeVer;
    }

    public function next(SemVerScope $scope = SemVerScope::PATCH): static
    {
        [$this->major, $this->minor, $this->patch] = match ($scope) {
            SemVerScope::MAJOR => [$this->major + 1, 0, 0],
            SemVerScope::MINOR => [$this->major, $this->minor + 1, 0],
            default => [$this->major, $this->minor, $this->patch + 1]
        };

        $this->semVer = "{$this->major}.{$this->minor}.{$this->patch}";

        return $this;
    }

    public function value(bool $asOriginal = false): string
    {
        return $asOriginal ? Str::replaceFirst($this->getOriginal(), $this->semVer, $this->ver) : $this->semVer;
    }
}
