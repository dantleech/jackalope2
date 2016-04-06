<?php

namespace Jackalope2\Tests\Unit\Storage\UnitOfWork;

use Jackalope2\Storage\UnitOfWork\Operation\CreateOperation;
use Jackalope2\Storage\UnitOfWork\OperationFactory;
use Jackalope2\Storage\Node;
use Jackalope2\Storage\NodeDataInterface;

class OperationFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;
    private $node;

    public function setUp()
    {
        $this->factory = new OperationFactory();
        $this->nodeData = $this->prophesize(NodeDataInterface::class);
    }

    public function testCreate()
    {
        $operation = $this->factory->create($this->nodeData->reveal());
        $this->assertInstanceOf(CreateOperation::class, $operation);
    }
}
