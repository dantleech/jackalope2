<?php

namespace Jackalope2\Tests\Unit\Storage;

use Ramsey\Uuid\UuidFactory;
use Jackalope2\Storage\NodeManagerFactory;
use Jackalope2\Storage\DriverInterface;

class NodeManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->uuidFactory = $this->prophesize(UuidFactory::class);
        $this->driver = $this->prophesize(DriverInterface::class);
        $this->factory = new NodeManagerFactory(
            $this->uuidFactory->reveal(),
            $this->driver->reveal()
        );
    }

    public function testCreate()
    {
        $manager = $this->factory->create('foobar');
        $this->assertEquals('foobar', $manager->getWorkspaceName());
    }
}
