<?php

namespace Jackalope2\Tests\Functional\Storage;

use Jackalope2\Tests\Functional\FunctionalTestCase;
use Jackalope2\Storage\NodeInterface;

class NodeManagerTest extends FunctionalTestCase
{
    private $nodeManager;
    private $driver;

    public function setUp()
    {
        $container = $this->getContainer();
        $this->nodeManager = $container->get('storage.node_manager_factory')->create('workspace');
        $this->driver = $container->get('storage.driver');
        $this->driver->createWorkspace('workspace');
    }

    /**
     * It should create the root node.
     */
    public function testCreateRootNode()
    {
        $node = $this->nodeManager->createNode('/');
        $this->assertInstanceOf(NodeInterface::class, $node);
        $foundPath = $this->nodeManager->findNodeByPath('/');
        $foundUuid = $this->nodeManager->findNodeByUuid($node->getUuid());

        $this->assertSame($node, $foundPath);
        $this->assertSame($node, $foundUuid);
    }

    /**
     * It should create a node at an arbitrary position.
     *
     * TODO: Show we create the intermediary nodes? Should it be neceessary
     *       for a node to have a *real* parent? (at least at this layer).
     */
    public function testCreateNonRootNode()
    {
        $this->nodeManager->createNode('/');
        $node = $this->nodeManager->createNode('/foo/bar');
        $this->assertInstanceOf(NodeInterface::class, $node);

        $foundNode = $this->nodeManager->findNodeByPath('/foo/bar');

        $this->assertSame($node, $foundNode);
    }

    /**
     * It should save the nodes.
     */
    public function testSave()
    {
        $this->nodeManager->createNode('/');
        $this->nodeManager->createNode('/foo');
        $this->nodeManager->save();

        $this->assertTrue($this->driver->pathExists('workspace', '/'));
        $this->assertTrue($this->driver->pathExists('workspace', '/foo'));
    }

    /**
     * It should throw an exception if trying to get an unknown node.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No node has been registered with path "/path"
     */
    public function testGetNodeUnknown()
    {
        $this->nodeManager->findNodeByPath('/path');
    }
}
