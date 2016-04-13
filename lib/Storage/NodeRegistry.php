<?php

declare(strict_types=1);

namespace Jackalope2\Storage;

use Jackalope2\Storage\NodeInterface;

/**
 * The node registry tracks the node instances and the paths associated with
 * them.
 */
class NodeRegistry
{
    private $nodes = [];
    private $uuidsByPath = [];
    private $pathsByUuid = [];

    /**
     * Register a node, if the node already exists an exception will be thrown.
     */
    public function registerNode(NodeInterface $node)
    {
        if (isset($this->nodes[$node->getUuid()])) {
            throw new \InvalidArgumentException(sprintf(
                'Node data with UUID "%s" already exists.',
                $node->getUuid()
            ));
        }

        $this->nodes[$node->getUuid()] = $node;
        $this->uuidsByPath[$node->getPath()] = $node->getUuid();
        $this->pathsByUuid[$node->getUuid()] = $node->getPath();
    }

    /**
     * Return a registered node, if the node does not exist an exception will
     * be thrown.
     *
     * @throws \InvalidArgumentException
     */
    public function getNodeByUuid(string $uuid): NodeInterface
    {
        $this->assertUuidExists($uuid);
        return $this->nodes[$uuid];
    }

    /**
     * Return a registered node by path, if the path does not exist, an
     * exception will be thrown.
     */
    public function getNodeByPath(string $path): NodeInterface
    {
        $this->assertPathExists($path);
        return $this->nodes[$this->uuidsByPath[$path]];
    }

    /**
     * Remove a registered node by UUID, if the UUID is not known an exception
     * will be thrown.
     */
    public function remove(string $uuid)
    {
        $this->assertUuidExists($uuid);

        $node = $this->nodes[$uuid];
        $nodes = $this->getDescendants($node);
        $nodes[] = $node;

        foreach ($nodes as $node) {
            unset($this->nodes[$node->getUuid()]);
            unset($this->uuidsByPath[$node->getPath()]);
            unset($this->pathsByUuid[$node->getUuid()]);
        }
    }

    /**
     * Return true if the UUID is registered, false otherwise.
     */
    public function hasUuid(string $uuid): bool
    {
        return isset($this->nodes[$uuid]);
    }

    /**
     * Return true if the path is registered, false otherwise.
     */
    public function hasPath(string $path): bool
    {
        return isset($this->uuidsByPath[$path]);
    }

    /**
     * Reset the registry.
     *
     * TODO: Remove this and just instantiate a new class?
     */
    public function clear()
    {
        $this->nodes = [];
        $this->uuidsByPath = [];
    }

    /**
     * Replace a node.
     *
     * If the UUID of the given node is registered, then the registered node
     * will be replaced by the given node. Otherwise an exception will be
     * thrown.
     */
    public function replaceNode(NodeInterface $node)
    {
        $this->assertUuidExists($node->getUuid());

        $this->nodes[$node->getUuid()] = $node;
        $this->uuidsByPath[$node->getPath()] = $node->getUuid();
    }

    /**
     * Return the path of the given node followed by all of its descendants.
     */
    private function getDescendants(NodeInterface $node)
    {
        $nodes = [];
        foreach ($this->uuidsByPath as $path => $uuid) {
            if ($uuid === $node->getUuid()) {
                continue;
            }

            if (0 === strpos($path, $node->getPath())) {
                $nodes[] = $this->nodes[$uuid];
            }
        }

        return $nodes;
    }

    private function assertUuidExists(string $uuid)
    {
        if (!isset($this->nodes[$uuid])) {
            throw new \InvalidArgumentException(sprintf(
                'No node has been registered with UUID "%s".',
                $uuid
            ));
        }
    }

    private function assertPathExists(string $path)
    {
        if (!isset($this->uuidsByPath[$path])) {
            throw new \InvalidArgumentException(sprintf(
                'No node has been registered with path "%s".',
                $path
            ));
        }
    }
}
