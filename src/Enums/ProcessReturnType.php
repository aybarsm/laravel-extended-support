<?php

namespace Aybarsm\Laravel\Support\Enums;

use Aybarsm\Laravel\Support\Traits\EnumHelper;

enum ProcessReturnType: int
{
    use EnumHelper;
    case STATUS = 0;
    case SUCCESSFUL = 1;
    case FAILED = 2;
    case EXIT_CODE = 3;
    case OUTPUT = 4;
    case ERROR_OUTPUT = 5;
    case INSTANCE = 6;
    case ALL_OUTPUT = 7;
}
