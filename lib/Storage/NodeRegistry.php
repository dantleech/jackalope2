<?php

declare(strict_types=1);

namespace Jackalope2\Storage;

class NodeRegistry
{
    private $nodes = [];

    public function registerNode(NodeDataInterface $node)
    {
        if (isset($this->nodes[$node->getUuid()])) {
            throw new \InvalidArgumentException(sprintf(
                'Node data with UUID "%s" already exists.',
                $node->getUuid()
            ));
        }

        $this->nodes[$node->getUuid()] = $node;
    }

    public function getNode(string $uuid): NodeDataInterface
    {
        $this->assertNodeExists($uuid);
        return $this->nodes[$uuid];
    }

    public function removeNode(string $uuid)
    {
        $this->assertNodeExists($uuid);
        unset($this->nodes[$uuid]);
    }

    public function hasNode($uuid)
    {
        return isset($this->nodes[$uuid]);
    }

    public function clear()
    {
        $this->nodes = [];
    }

    private function assertNodeExists($uuid)
    {
        if (!isset($this->nodes[$uuid])) {
            throw new \InvalidArgumentException(sprintf(
                'No node has been registered with UUID "%s".',
                $uuid
            ));
        }
    }
}
