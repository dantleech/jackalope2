<?php

namespace Jackalope2\Tests\Unit\Storage;

use Jackalope2\Storage\NodeRegistry;
use Jackalope2\Storage\NodeDataInterface;
use Jackalope2\Storage\NodeInterface;

class NodeRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->registry = new NodeRegistry();
        $this->node = $this->prophesize(NodeInterface::class);
        $this->node->getUuid()->willReturn('1234');
        $this->node->getPath()->willReturn('/path/to');
    }

    /**
     * It should register a node.
     * It should retrieve a node.
     */
    public function testRegisterNode()
    {
        $this->registry->registerNode($this->node->reveal());
        $node = $this->registry->getNodeByUuid('1234');
        $this->assertSame($this->node->reveal(), $node);
    }

    /**
     * It should remove a node.
     * It should say if a node exists.
     */
    public function testRemoveNode()
    {
        $this->assertFalse($this->registry->hasUuid('1234'));
        $this->registry->registerNode($this->node->reveal());
        $this->assertTrue($this->registry->hasUuid('1234'));
        $this->registry->remove('1234');
        $this->assertFalse($this->registry->hasUuid('1234'));
    }

    /**
     * It should clear the registry.
     */
    public function testClear()
    {
        $this->registry->registerNode($this->node->reveal());
        $this->registry->clear();
        $this->assertFalse($this->registry->hasUuid('1234'));
        $this->assertFalse($this->registry->hasUuid('1334'));
        $this->assertFalse($this->registry->hasPath('/1'));
        $this->assertFalse($this->registry->hasPath('/2'));
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
        $this->registry->getNodeByUuid('4321');
    }

    /**
     * It should remove a path and all its descendants.
     */
    public function testRemovePathAndDescendants()
    {
        foreach ([
            [ '1', '/1' ],
            [ '2', '/1/2' ],
            [ '3', '/1/2/3' ],
            [ '4', '/asd' ],
        ] as $nodeTuple) {
            list($uuid, $path) = $nodeTuple;
            $node = $this->prophesize(NodeInterface::class);
            $node->getUuid()->willReturn($uuid);
            $node->getPath()->willReturn($path);
            $this->registry->registerNode($node->reveal());
        }

        $this->registry->remove('1');

        $this->assertFalse($this->registry->hasUuid('1'));
        $this->assertFalse($this->registry->hasPath('/1'));
        $this->assertFalse($this->registry->hasUuid('2'));
        $this->assertFalse($this->registry->hasPath('/1/2'));
        $this->assertFalse($this->registry->hasUuid('3'));
        $this->assertFalse($this->registry->hasPath('/1/2/3'));
        $this->assertTrue($this->registry->hasUuid('4'));
        $this->assertTrue($this->registry->hasPath('/asd'));
    }
}
