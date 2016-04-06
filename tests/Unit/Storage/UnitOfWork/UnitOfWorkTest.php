<?php

namespace Jackalope2\Tests\Unit\Storage\UnitOfWork;

use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\Node;
use Jackalope2\Storage\NodeDataInterface;
use Jackalope2\Storage\UnitOfWork\Operation\CreateOperation;
use Jackalope2\Storage\UnitOfWork\OperationFactory;
use Jackalope2\Storage\UnitOfWork\OperationInterface;
use Jackalope2\Storage\UnitOfWork\UnitOfWork;
use Jackalope2\Storage\PathRegistry;
use Ramsey\Uuid\UuidFactory;
use Prophecy\Argument;

class UnitOfWorkTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->driver = $this->prophesize(DriverInterface::class);
        $this->pathRegistry = $this->prophesize(PathRegistry::class);
        $this->node = $this->prophesize(Node::class);
        $this->nodeData = $this->prophesize(NodeDataInterface::class);
        $this->operationFactory = $this->prophesize(OperationFactory::class);
        $this->operation1 = $this->prophesize(OperationInterface::class);
        $this->operation2 = $this->prophesize(OperationInterface::class);
        $this->uuidFactory = $this->prophesize(UuidFactory::class);
        $this->pendingOperations = new \SplQueue();

        $this->unitOfWork = new UnitOfWork(
            $this->driver->reveal(),
            $this->pathRegistry->reveal(),
            $this->operationFactory->reveal(),
            $this->uuidFactory->reveal(),

            $this->pendingOperations
        );
    }

    /**
     * It should create a new node for a given path.
     */
    public function testNewNode()
    {
        $path = '/1234';
        $uuid = '1234';
        $this->uuidFactory->uuid4()->willReturn($uuid);
        $this->pathRegistry->register($uuid, $path)->shouldBeCalled();
        $this->operationFactory->create(Argument::type(NodeDataInterface::class))->willReturn($this->operation1->reveal());

        $this->unitOfWork->createNode($path);

        $operation = $this->pendingOperations->dequeue();
        $this->assertNotNull($operation);
        $this->assertSame($this->operation1->reveal(), $operation);
    }

    /**
     * It should register a node data.e
     */
    public function testRegisterFromNodeData()
    {
        $uuid = '1234';
        $path = '/1234';

        $this->nodeData->getUuid()->willReturn($uuid);
        $this->nodeData->getPath()->willReturn($path);
        $this->pathRegistry->register($uuid, $path)->shouldBeCalled();
        $node = $this->unitOfWork->registerNodeData($this->nodeData->reveal());

        $this->assertInstanceOf(Node::class, $node);
        $this->assertEquals($uuid, $node->getUuid());
    }

    /**
     * It should indicate if a UUID is in the UOW.
     */
    public function testHasUuid()
    {
        $this->assertFalse($this->unitOfWork->hasUuid('1234'));

        $this->uuidFactory->uuid4()->willReturn('1234');
        $this->unitOfWork->createNode('/path/to');

        $this->assertTrue($this->unitOfWork->hasUuid('1234'));
    }

    /**
     * It should indicate if it has a node at the given path.
     */
    public function testHasPath()
    {
        $this->pathRegistry->hasPath('/path')->willReturn(false);
        $this->pathRegistry->hasPath('/htap')->willReturn(true);
        $this->assertFalse($this->unitOfWork->hasPath('/path'));
        $this->assertTrue($this->unitOfWork->hasPath('/htap'));
    }

    /**
     * It should return a managed node by UUID.
     */
    public function testNodeByUuid()
    {
        $uuid = '1234';
        $this->uuidFactory->uuid4()->willReturn($uuid);
        $this->unitOfWork->createNode('/path/to');

        $node = $this->unitOfWork->getNodeByUuid($uuid);

        $this->assertEquals($uuid, $node->getUuid());
    }

    /**
     * It should throw an exception if an unknown node is requested by UUID.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Node with UUID "1234" is not registered
     */
    public function testNodeByUUidNotExist()
    {
        $this->unitOfWork->getNodeByUuid('1234');
    }

    /**
     * It should return a managed node by path.
     */
    public function testNodeByPath()
    {
        $path = '/1234';
        $uuid = '1234';

        // setup
        $this->uuidFactory->uuid4()->willReturn($uuid);
        $this->unitOfWork->createNode($path);

        $this->pathRegistry->getUuid($path)->willReturn($uuid);

        $node = $this->unitOfWork->getNodeByPath($path);

        $this->assertEquals($uuid, $node->getUuid());
    }

    /**
     * It should commit operations.
     */
    public function testCommit()
    {
        $this->pendingOperations->enqueue($this->operation1->reveal());
        $this->pendingOperations->enqueue($this->operation2->reveal());

        $this->operation1->commit($this->driver->reveal())->shouldBeCalled();
        $this->operation2->commit($this->driver->reveal())->shouldBeCalled();

        $this->unitOfWork->commit();
    }

    /**
     * It should clear.
     */
    public function testClear()
    {
        $path = '/path';
        $uuid = '1234';
        $this->uuidFactory->uuid4()->willReturn($uuid);
        $this->unitOfWork->createNode($path);
        $this->pathRegistry->register($uuid, $path)->shouldBeCalled();
        $this->pathRegistry->clear()->shouldBeCalled();

        $this->unitOfWork->clear();
    }

    /**
     * It should rollback operations if an error is encountered.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage foo
     */
    public function testCommitRollback()
    {
        $this->pendingOperations->enqueue($this->operation1->reveal());
        $this->pendingOperations->enqueue($this->operation2->reveal());

        $this->operation1->commit($this->driver->reveal())->shouldBeCalled();
        $this->operation2->commit($this->driver->reveal())->willThrow(new \RuntimeException('foo'));
        $this->operation1->rollback($this->driver->reveal())->shouldBeCalled();

        $this->unitOfWork->commit();
    }
}
