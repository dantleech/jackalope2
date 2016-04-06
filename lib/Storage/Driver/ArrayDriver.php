<?php

declare(strict_types=1);

namespace Jackalope2\Storage\Driver;

use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\NodeDataInterface;
use Jackalope2\Storage\PathRegistry;

class ArrayDriver implements DriverInterface
{
    private $nodes;
    private $pathRegistry;

    public function __construct()
    {
        $this->pathRegistry = new PathRegistry();
    }

    /**
     * {@inheritdoc}
     */
    public function store(NodeDataInterface $node)
    {
        $this->nodes[$node->getUuid()] = $node;
        $this->pathRegistry->register($node->getUuid(), $node->getPath());
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $uuid)
    {
        foreach ($this->pathRegistry->getDescendants($this->pathRegistry->getPath($uuid)) as $path) {
            $uuid = $this->pathRegistry->getUuid($path);
            unset($this->nodes[$uuid]);
        }

        $this->pathRegistry->removeByUuid($uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function findByPath(string $path): NodeDataInterface
    {
        return $this->findByUuid($this->pathRegistry->getUuid($path));
    }

    /**
     * {@inheritdoc}
     */
    public function findByUuid(string $uuid): NodeDataInterface
    {
        if (false === isset($this->nodes[$uuid])) {
            throw new \InvalidArgumentException(sprintf(
                'Node with UUID "%s" not found',
                $uuid
            ));
        }

        return $this->nodes[$uuid];
    }

    /**
     * {@inheritdoc}
     */
    public function pathExists(string $path): bool
    {
        return $this->pathRegistry->hasPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function uuidExists(string $uuid): bool
    {
        return $this->pathRegistry->hasUuid($uuid);
    }
}
