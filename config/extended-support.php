<?php

return [
    'runtime' => [
        'replace_existing' => true,
        'class_autoload' => true,
        'required_trait' => 'Illuminate\Support\Traits\Macroable',
        'bind_pattern' => '/@mixin\s*([^\s*]+)/',
        'load' => [
            Aybarsm\Laravel\Support\Mixins\StringableMixin::class,
            Aybarsm\Laravel\Support\Mixins\StrMixin::class,
            Aybarsm\Laravel\Support\Mixins\ArrMixin::class,
            Aybarsm\Laravel\Support\Mixins\FileMixin::class,
            Aybarsm\Laravel\Support\Mixins\RuleMixin::class,
            Aybarsm\Laravel\Support\Mixins\ApplicationMixin::class,
            Aybarsm\Laravel\Support\Mixins\CommandMixin::class,
            Aybarsm\Laravel\Support\Mixins\ProcessMixin::class,
            Aybarsm\Laravel\Support\Mixins\CollectionMixin::class,
        ],
    ],
    'concretes' => [
        'ExtendedSupport' => Aybarsm\Laravel\Support\ExtendedSupport::class,
        'Supplements' => [
            'Str' => [
                'SemVer' => Aybarsm\Laravel\Support\Supplements\Str\SemVer::class,
            ],
            'Foundation' => [
                'Annotation' => Aybarsm\Laravel\Support\Supplements\Foundation\Annotation::class,
            ],
        ],
    ],
];
