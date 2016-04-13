<?php

namespace Jackalope2\Tests\Unit\Storage;

use Jackalope2\Storage\Operation\CreateOperation;
use Jackalope2\Storage\OperationFactory;
use Jackalope2\Storage\Node;
use Jackalope2\Storage\NodeInterface;

class OperationFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;
    private $node;

    public function setUp()
    {
        $this->factory = new OperationFactory();
        $this->nodeData = $this->prophesize(NodeInterface::class);
    }

    public function testCreate()
    {
        $operation = $this->factory->create($this->nodeData->reveal());
        $this->assertInstanceOf(CreateOperation::class, $operation);
    }
}
