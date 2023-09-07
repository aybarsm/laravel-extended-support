<?php

return [
    'runtime' => [
        'class_autoload' => true,
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
    'mixins' => [
        'replace' => true,
        'load' => [
            Aybarsm\Laravel\Support\Mixins\StringableMixin::class,
            Aybarsm\Laravel\Support\Mixins\StrMixin::class,
            Aybarsm\Laravel\Support\Mixins\ArrMixin::class,
            Aybarsm\Laravel\Support\Mixins\FileMixin::class,
            Aybarsm\Laravel\Support\Mixins\RuleMixin::class,
            Aybarsm\Laravel\Support\Mixins\ApplicationMixin::class,
            Aybarsm\Laravel\Support\Mixins\CommandMixin::class,
            Aybarsm\Laravel\Support\Mixins\ProcessMixin::class,
        ],
    ],
];
