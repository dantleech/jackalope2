<?php

namespace Jackalope2\Tests\Unit\Storage\Driver;

use Jackalope2\Storage\NodeDataInterface;
use Jackalope2\Storage\Driver\ArrayDriver;

class ArrayDriverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->nodeData = $this->prophesize(NodeDataInterface::class);
        $this->driver = new ArrayDriver();
    }

    /**
     * It should store node data.
     * It should find by path.
     * It should find by UUID.
     */
    public function testStore()
    {
        $uuid = '1234';
        $path = '/1234';

        $this->nodeData->getUuid()->willReturn($uuid);
        $this->nodeData->getPath()->willReturn($path);
        $this->driver->store($this->nodeData->reveal());

        $this->assertEquals($this->nodeData->reveal(), $this->driver->findByUuid($uuid));
        $this->assertEquals($this->nodeData->reveal(), $this->driver->findByPath($path));
        $this->assertTrue($this->driver->uuidExists($uuid));
        $this->assertTrue($this->driver->pathExists($path));
    }

    /**
     * It should remove a node.
     */
    public function testRemove()
    {
        $uuid = '1234';
        $path = '/1234';

        $this->nodeData->getUuid()->willReturn($uuid);
        $this->nodeData->getPath()->willReturn($path);
        $this->driver->store($this->nodeData->reveal());

        $this->assertEquals($this->nodeData->reveal(), $this->driver->findByUuid($uuid));
        $this->assertEquals($this->nodeData->reveal(), $this->driver->findByPath($path));
    }
}
