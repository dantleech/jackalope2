<?php

namespace Jackalope2\Tests\Unit\Storage;

use Jackalope2\Storage\Node;
use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\NodeManager;
use Jackalope2\Storage\NodeInterface;
use Jackalope2\Storage\NodeRegistry;
use Jackalope2\Storage\OperationFactory;
use Ramsey\Uuid\UuidFactory;
use Prophecy\Argument;

class NodeManagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->driver = $this->prophesize(DriverInterface::class);
        $this->nodeRegistry = $this->prophesize(NodeRegistry::class);
        $this->operationFactory = $this->prophesize(OperationFactory::class);
        $this->uuidFactory = $this->prophesize(UuidFactory::class);
        $this->pendingOperations = new \SplQueue();


        $this->nodeManager = new NodeManager(
            'workspace',
            $this->driver->reveal(),
            $this->nodeRegistry->reveal(),
            $this->operationFactory->reveal(),
            $this->uuidFactory->reveal(),
            $this->pendingOperations
        );

        $this->node = $this->prophesize(NodeInterface::class);
    }

    /**
     * It should find a node by UUID.
     */
    public function testFindByUuid()
    {
        $uuid = '1234';
        $this->nodeRegistry->hasUuid($uuid)->willReturn(false);
        $this->driver->findByUuid('workspace', $uuid)->willReturn($this->node->reveal());
        $this->nodeRegistry->registerNode($this->node->reveal())->shouldBeCalled();

        $node = $this->nodeManager->findNodeByUuid($uuid);

        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * If the UOW already has the node, it should return the node from the UOW.
     */
    public function testFindByUUidInUow()
    {
        $uuid = '1234';
        $this->nodeRegistry->hasUuid($uuid)->willReturn(true);
        $this->nodeRegistry->getNodeByUuid($uuid)->willReturn($this->node->reveal());

        $node = $this->nodeManager->findNodeByUuid($uuid);
        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * It should return the node from the UOW if it is already loaded.
     */
    public function testFindByPathFromUow()
    {
        $this->nodeRegistry->hasPath('/foo')->willReturn(true);
        $this->nodeRegistry->getNodeByPath('/foo')->willReturn($this->node->reveal());

        $node = $this->nodeManager->findNodeByPath('/foo');

        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * It should load the node from storage and register it.
     */
    public function testFindByPathFromStorage()
    {
        $this->nodeRegistry->hasPath('/foo')->willReturn(false);
        $this->driver->findByPath('workspace', '/foo')->willReturn($this->node->reveal());
        $this->nodeRegistry->registerNode($this->node->reveal())->willReturn($this->node->reveal());

        $node = $this->nodeManager->findNodeByPath('/foo');

        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * It should create a node.
     */
    public function testCreateNode()
    {
        $this->uuidFactory->uuid4()->willReturn('1234');
        $this->nodeRegistry->registerNode(Argument::type(NodeInterface::class))->shouldBeCalled();

        $this->nodeManager->createNode('/path/to');
    }
}
