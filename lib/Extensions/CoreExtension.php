<?php

namespace Jackalope2\Extensions;

use PhpBench\DependencyInjection\Container;
use PhpBench\DependencyInjection\ExtensionInterface;
use Jackalope2\Storage;

class CoreExtension implements ExtensionInterface
{
    public function load(Container $container)
    {
        $this->loadStorage($container);
    }

    public function getDefaultConfig()
    {
        return [];
    }

    public function build(Container $container)
    {
    }

    private function loadStorage(Container $container)
    {
        $container->register('storage.node_manager', function (Container $container) {
            return new Storage\NodeManager(
                $container->get('storage.unit_of_work'),
                $container->get('storage.driver')
            );
        });

        $container->register('storage.path_registry', function (Container $container) {
            return new Storage\PathRegistry();
        });

        $container->register('storage.uuid_factory', function (Container $container) {
            return new \Ramsey\Uuid\UuidFactory();
        });

        $container->register('storage.driver', function (Container $container) {
            return new Storage\Driver\ArrayDriver();
        });
    }
}
