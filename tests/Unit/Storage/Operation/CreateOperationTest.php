<?php

namespace Jackalope2\Tests\Unit\Storage\Operation;

use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\NodeInterface;
use Jackalope2\Storage\Operation\CreateOperation;

class CreateOperationTest extends \PHPUnit_Framework_TestCase
{
    private $operation;

    public function setUp()
    {
        $this->driver = $this->prophesize(DriverInterface::class);
        $this->node = $this->prophesize(NodeInterface::class);
        $this->operation = new CreateOperation($this->node->reveal());
    }

    /**
     * It should commit.
     */
    public function testCommit()
    {
        $this->driver->store('workspace', $this->node->reveal())->shouldBeCalled();

        $this->operation->commit('workspace', $this->driver->reveal());
    }
}
