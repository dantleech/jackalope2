<?php

namespace Jackalope2\Tests\Unit\Storage;

use Jackalope2\Storage\NodeRegistry;
use Jackalope2\Storage\NodeDataInterface;

class NodeRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->registry = new NodeRegistry();
        $this->node = $this->prophesize(NodeDataInterface::class);
        $this->node->getUuid()->willReturn('1234');
    }

    /**
     * It should register a node.
     * It should retrieve a node.
     */
    public function testRegisterNode()
    {
        $this->registry->registerNode($this->node->reveal());
        $node = $this->registry->getNode('1234');
        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * It should remove a node.
     * It should say if a node exists.
     */
    public function testRemoveNode()
    {
        $this->assertFalse($this->registry->hasNode('1234'));
        $this->registry->registerNode($this->node->reveal());
        $this->assertTrue($this->registry->hasNode('1234'));
        $this->registry->removeNode('1234');
        $this->assertFalse($this->registry->hasNode('1234'));
    }

    /**
     * It should clear the registry.
     */
    public function testClear()
    {
        $this->registry->registerNode($this->node->reveal());
        $this->registry->clear();
        $this->assertFalse($this->registry->hasNode('1234'));
    }

    /**
     * It should throw an exception if registering a node with a UUID that is already registred.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Node data with UUID "1234" already exists.
     */
    public function testRegisterNodeExistingUuid()
    {
        $this->registry->registerNode($this->node->reveal());
        $this->registry->registerNode($this->node->reveal());
    }

    /**
     * It should throw an exception when getting a non-existant UUID.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No node has been registered with UUID "4321"
     */
    public function testGetNodeNonExisting()
    {
        $this->registry->registerNode($this->node->reveal());
        $this->registry->getNode('4321');
    }
}
