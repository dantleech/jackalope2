<?php

namespace Jackalope2\Tests\Unit\Storage\Driver;

use Jackalope2\Storage\NodeDataInterface;
use Jackalope2\Storage\Driver\ArrayDriver;

class ArrayDriverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->nodeData = $this->prophesize(NodeDataInterface::class);
        $this->driver = new ArrayDriver();
    }

    /**
     * It should create workspaces.
     * It should list workspaces.
     */
    public function testListWorkspaces()
    {
        $this->assertEquals([], $this->driver->listWorkspaces());
        $this->driver->createWorkspace('foo');
        $this->assertEquals(['foo'], $this->driver->listWorkspaces());
    }

    /**
     * It should delete workspaces.
     */
    public function testDeleteWorkspaces()
    {
        $this->driver->createWorkspace('foo');
        $this->assertEquals(['foo'], $this->driver->listWorkspaces());
        $this->driver->deleteWorkspace('foo');
        $this->assertEquals([], $this->driver->listWorkspaces());
    }

    /**
     * It should throw an exception if a workspace already exists.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Workspace "foo" already exists
     */
    public function testCreateWorkspaceAlreadyExists()
    {
        $this->driver->createWorkspace('foo');
        $this->driver->createWorkspace('foo');
        $this->driver->createWorkspace('foo');
    }

    /**
     * It should throw an exception when trying to remove a non-existant workspace.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Undefined workspace
     */
    public function testDeleteNonExistingWorkspace()
    {
        $this->driver->deleteWorkspace('foo');
    }

    /**
     * It should store node data.
     * It should find by path.
     * It should find by UUID.
     */
    public function testStore()
    {
        $uuid = '1234';
        $path = '/1234';
        $workspace = 'bar';

        $this->driver->createWorkspace($workspace);
        $this->nodeData->getUuid()->willReturn($uuid);
        $this->nodeData->getPath()->willReturn($path);
        $this->driver->store($workspace, $this->nodeData->reveal());

        $this->assertEquals($this->nodeData->reveal(), $this->driver->findByUuid($workspace, $uuid));
        $this->assertEquals($this->nodeData->reveal(), $this->driver->findByPath($workspace, $path));
        $this->assertTrue($this->driver->uuidExists($workspace, $uuid));
        $this->assertTrue($this->driver->pathExists($workspace, $path));
    }

    /**
     * It should remove a node.
     */
    public function testRemove()
    {
        $uuid = '1234';
        $path = '/1234';
        $workspace = 'bar';

        $this->driver->createWorkspace($workspace);
        $this->nodeData->getUuid()->willReturn($uuid);
        $this->nodeData->getPath()->willReturn($path);
        $this->driver->store($workspace, $this->nodeData->reveal());

        $this->assertEquals($this->nodeData->reveal(), $this->driver->findByUuid($workspace, $uuid));
        $this->assertEquals($this->nodeData->reveal(), $this->driver->findByPath($workspace, $path));
    }
}
