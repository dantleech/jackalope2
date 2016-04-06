<?php

namespace Jackalope2\Tests\Unit\Storage;

use Jackalope2\Storage\NodeData\ArrayNodeData;
use Jackalope2\Storage\Node;
use Jackalope2\Storage\PathRegistry;
use Jackalope2\Storage\NodeDataInterface;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    private $node;
    private $pathRegistry;

    public function setUp()
    {
        $this->pathRegistry = $this->prophesize(PathRegistry::class);
        $this->nodeData = $this->prophesize(NodeDataInterface::class);
        $this->node = new Node('1234', $this->pathRegistry->reveal(), $this->nodeData->reveal());
    }

    /**
     * It should return its UUID.
     */
    public function testUuid()
    {
        $this->assertEquals('1234', $this->node->getUuid());
    }

    /**
     * It should return its path as reported by the path registry.
     */
    public function testPath()
    {
        $this->pathRegistry->getPath('1234')->willReturn('/path');
        $this->assertEquals('/path', $this->node->getPath('1234'));
    }

    /**
     * It should return a property value.
     */
    public function testPropertyValue()
    {
        $this->nodeData->get('foo')->willReturn('bar');
        $this->assertEquals('bar', $this->node->getPropertyValue('foo'));
    }

    /**
     * It should return the property names.
     */
    public function testPropertyNames()
    {
        $this->nodeData->keys()->willReturn([ 'one', 'two' ]);
        $this->assertEquals([ 'one', 'two' ], $this->node->getPropertyNames());
    }
}
