<?php

namespace Jackalope2\Tests\Unit\Storage;

use Jackalope2\Storage\PathRegistry;

class PathRegistryTest extends \PHPUnit_Framework_TestCase
{
    private $pathRegistry;

    public function setUp()
    {
        $this->pathRegistry = new PathRegistry();
    }

    /**
     * It should register a UUID and a path.
     * It should get a UUID for a path.
     * It should get a path for a UUID.
     */
    public function testRegistry()
    {
        $this->pathRegistry->register('1234', '/1/2/3/4');
        $this->assertEquals(
            '/1/2/3/4', $this->pathRegistry->getPath('1234')
        );
        $this->assertEquals(
            '1234', $this->pathRegistry->getUuid('/1/2/3/4')
        );
    }

    /**
     * It should throw an exception if a UUID does not exist.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Path "/path" is not known to the path registry
     */
    public function testGeUuidNotExist()
    {
        $this->pathRegistry->getUuid('/path');
    }

    /**
     * It should throw an exception if a path does not exist.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage UUID "1234" is not known to the path registry
     */
    public function testGetPathNotExist()
    {
        $this->pathRegistry->getPath('1234');
    }

    /**
     * It should remove a path and all its descendants.
     */
    public function testRemovePathAndDescendantsByUuid()
    {
        $this->pathRegistry->register('1234', '/1');
        $this->pathRegistry->register('1334', '/1/2/3/4');
        $this->pathRegistry->register('1434', '/1/2/3');
        $this->pathRegistry->register('1534', '/asd');

        $this->pathRegistry->removeByUuid('1234');

        $this->assertFalse($this->pathRegistry->hasUuid(1234));
        $this->assertFalse($this->pathRegistry->hasPath('/1'));
        $this->assertFalse($this->pathRegistry->hasUuid(1334));
        $this->assertFalse($this->pathRegistry->hasPath('/1/2/3/4'));
        $this->assertFalse($this->pathRegistry->hasUuid(1434));
        $this->assertFalse($this->pathRegistry->hasPath('/1/2/3'));
        $this->assertTrue($this->pathRegistry->hasUuid(1534));
        $this->assertTrue($this->pathRegistry->hasPath('/asd'));
    }
}
