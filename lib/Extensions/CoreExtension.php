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
        $container->register('storage.uuid_factory', function (Container $container) {
            return new \Ramsey\Uuid\UuidFactory();
        });

        $container->register('storage.driver', function (Container $container) {
            return new Storage\Driver\ArrayDriver();
        });

        $container->register('storage.node_manager_factory', function (Container $container) {
            return new Storage\NodeManagerFactory(
                $container->get('storage.uuid_factory'),
                $container->get('storage.driver')
            );
        });
    }
}
