<?php

return [
    'mixins' => [
        Aybarsm\Laravel\Support\Mixins\StringableMixin::class => Illuminate\Support\Stringable::class,
        Aybarsm\Laravel\Support\Mixins\StrMixin::class => Illuminate\Support\Str::class,
        Aybarsm\Laravel\Support\Mixins\ArrMixin::class => Illuminate\Support\Arr::class,
        Aybarsm\Laravel\Support\Mixins\FileMixin::class => Illuminate\Filesystem\Filesystem::class,
        Aybarsm\Laravel\Support\Mixins\RuleMixin::class => Illuminate\Validation\Rule::class,
        Aybarsm\Laravel\Support\Mixins\ApplicationMixin::class => Illuminate\Foundation\Application::class,
        Aybarsm\Laravel\Support\Mixins\CommandMixin::class => Illuminate\Console\Command::class,
        Aybarsm\Laravel\Support\Mixins\ProcessMixin::class => Illuminate\Process\Factory::class,
        Aybarsm\Laravel\Support\Mixins\CollectionMixin::class => Illuminate\Support\Collection::class,
    ],
];
