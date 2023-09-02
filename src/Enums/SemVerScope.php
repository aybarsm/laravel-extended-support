<?php

namespace Aybarsm\Laravel\Support\Enums;

use Aybarsm\Laravel\Support\Traits\EnumHelper;

enum SemVerScope: int
{
    use EnumHelper;

    case PATCH = 0;
    case MINOR = 1;
    case MAJOR = 2;
}
