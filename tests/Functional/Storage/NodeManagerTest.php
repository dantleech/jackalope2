<?php

namespace Jackalope2\Tests\Functional\Storage;

use Jackalope2\Storage\Node;
use Jackalope2\Tests\Functional\FunctionalTestCase;

class NodeManagerTest extends FunctionalTestCase
{
    private $nodeManager;
    private $driver;

    public function setUp()
    {
        $container = $this->getContainer();
        $this->nodeManager = $container->get('storage.node_manager');
        $this->driver = $container->get('storage.driver');
    }

    /**
     * It should create the root node.
     */
    public function testCreateRootNode()
    {
        $node = $this->nodeManager->createNode('/');
        $this->assertInstanceOf(Node::class, $node);
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
        $this->assertInstanceOf(Node::class, $node);

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

        $this->assertTrue($this->driver->pathExists('/'));
        $this->assertTrue($this->driver->pathExists('/foo'));
    }

    /**
     * It should throw an exception if trying to get an unknown node.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Path "/path" is not known to the path registry, 0 paths currently registered.
     */
    public function testGetNodeUnknown()
    {
        $this->nodeManager->findNodeByPath('/path');
    }
}
