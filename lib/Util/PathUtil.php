<?php

namespace Jackalope2\Util;

class PathUtil
{
    public static function concat(array $pathSegments = [])
    {
        return implode('/', $pathSegments);
    }
}
