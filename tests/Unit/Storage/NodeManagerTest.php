<?php

namespace Jackalope2\Tests\Unit\Storage;

use Jackalope2\Storage\UnitOfWork\UnitOfWork;
use Jackalope2\Storage\Node;
use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\NodeManager;
use Jackalope2\Storage\NodeDataInterface;

class NodeManagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->unitOfWork = $this->prophesize(UnitOfWork::class);
        $this->driver = $this->prophesize(DriverInterface::class);
        $this->node = $this->prophesize(Node::class);
        $this->nodeData = $this->prophesize(NodeDataInterface::class);

        $this->nodeManager = new NodeManager(
            $this->unitOfWork->reveal(),
            $this->driver->reveal()
        );
    }

    /**
     * It should find a node by UUID.
     */
    public function testFindByUuid()
    {
        $uuid = '1234';
        $this->unitOfWork->hasUuid($uuid)->willReturn(false);
        $this->driver->findByUuid($uuid)->willReturn($this->nodeData->reveal());
        $this->unitOfWork->registerNodeData($this->nodeData->reveal())->willReturn($this->node->reveal());

        $node = $this->nodeManager->findNodeByUuid($uuid);

        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * If the UOW already has the node, it should return the node from the UOW.
     */
    public function testFindByUUidInUow()
    {
        $uuid = '1234';
        $this->unitOfWork->hasUuid($uuid)->willReturn(true);
        $this->unitOfWork->getNodeByUuid($uuid)->willReturn($this->node->reveal());

        $node = $this->nodeManager->findNodeByUuid($uuid);
        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * It should create the root node if it does not exist.
     */
    public function testCreateRootNode()
    {
        $this->driver->pathExists('/')->willReturn(false);
        $this->unitOfWork->createNew('/')->willReturn($this->node->reveal());

        $node = $this->nodeManager->findNodeByPath('/');

        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * It should return the node from the UOW if it is already loaded.
     */
    public function testFindByPathFromUow()
    {
        $this->unitOfWork->hasPath('/foo')->willReturn(true);
        $this->unitOfWork->getNodeByPath('/foo')->willReturn($this->node->reveal());

        $node = $this->nodeManager->findNodeByPath('/foo');

        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * It should load the node from storage and register it.
     */
    public function testFindByPathFromStorage()
    {
        $this->unitOfWork->hasPath('/foo')->willReturn(false);
        $this->driver->findByPath('/foo')->willReturn($this->nodeData->reveal());
        $this->unitOfWork->registerNodeData($this->nodeData->reveal())->willReturn($this->node->reveal());

        $node = $this->nodeManager->findNodeByPath('/foo');

        $this->assertSame($this->node->reveal(), $node);
    }
}
