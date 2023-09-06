<?php

namespace Aybarsm\Laravel\Support\Enums;

enum StrLinesAction: int
{
    case NORMALISE = 0;
    case REPLACE = 1;
    case REMOVE_EMPTY = 2;
    case SPLIT = 3;
}
