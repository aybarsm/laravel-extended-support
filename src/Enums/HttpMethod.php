<?php

namespace Aybarsm\Laravel\Support\Enums;

use Aybarsm\Laravel\Support\Traits\EnumHelper;

enum HttpMethod: string
{
    use EnumHelper;
    case GET = 'GET';
    case POST = 'POST';
    case HEAD = 'HEAD';
    case OPTIONS = 'OPTIONS';
    case PATCH = 'PATCH';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}
