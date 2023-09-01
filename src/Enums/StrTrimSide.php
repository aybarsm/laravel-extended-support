<?php

namespace Aybarsm\Laravel\Support\Enums;

use Aybarsm\Laravel\Support\Traits\EnumHelper;

enum StrTrimSide: int
{
    use EnumHelper;

    case BOTH = 0;
    case LEFT = 1;
    case RIGHT = 2;
}
