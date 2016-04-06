<?php

namespace Jackalope2;

use PhpBench\DependencyInjection\Container;
use Jackalope2\Extensions\CoreExtension;

class Jackalope2
{
    public static function getContainer()
    {
        $container = new Container([
            CoreExtension::class
        ]);
        $container->init();

        return $container;
    }
}

