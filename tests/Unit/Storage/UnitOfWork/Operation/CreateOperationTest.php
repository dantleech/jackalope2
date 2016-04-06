<?php

namespace Jackalope2\Tests\Unit\Storage\UnitOfWork\Operation;

use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\Node;
use Jackalope2\Storage\NodeDataInterface;
use Jackalope2\Storage\UnitOfWork\Operation\CreateOperation;

class CreateOperationTest extends \PHPUnit_Framework_TestCase
{
    private $operation;

    public function setUp()
    {
        $this->driver = $this->prophesize(DriverInterface::class);
        $this->node = $this->prophesize(NodeDataInterface::class);
        $this->operation = new CreateOperation($this->node->reveal());
    }

    /**
     * It should commit.
     */
    public function testCommit()
    {
        $this->driver->store($this->node->reveal())->shouldBeCalled();

        $this->operation->commit($this->driver->reveal());
    }
}
