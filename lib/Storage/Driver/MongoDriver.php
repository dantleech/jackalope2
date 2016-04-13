<?php

declare(strict_types=1);

namespace Jackalope2\Storage\Driver;

use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\NodeDataInterface;
use Jackalope2\Storage\PathRegistry;
use Jackalope2\Exception\UndefinedMongoKeyException;
use Jackalope2\Storage\NodeRegistry;

class MongoDriver implements DriverInterface
{
    private $nodeRegistries = [];
    private $pathRegistries = [];

    /**
     * {@inheritdoc}
     */
    public function store(string $workspaceName, NodeDataInterface $node)
    {
        $pathRegistry = $this->getPathRegistry($workspaceName);
        $nodeRegistry = $this->getNodeRegistry($workspaceName);

        $nodeRegistry->registerNode($node);
        $pathRegistry->register($node->getUuid(), $node->getPath());
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $workspaceName, string $uuid)
    {
        $pathRegistry = $this->getPathRegistry($workspaceName);
        foreach ($pathRegistry->getDescendants($this->pathRegistry->getPath($uuid)) as $path) {
            $uuid = $pathRegistry->getUuid($path);
            $this->getNodeRegistry($workspaceName)->removeNode($uuid);
        }

        $this->pathRegistry->removeByUuid($uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function findByPath(string $workspaceName, string $path): NodeDataInterface
    {
        return $this->findByUuid($workspaceName, $this->getPathRegistry($workspaceName)->getUuid($path));
    }

    /**
     * {@inheritdoc}
     */
    public function findByUuid(string $workspaceName, string $uuid): NodeDataInterface
    {
        // assert workspace exists.
        $nodeRegistry = $this->getNodeRegistry($workspaceName);
        return $nodeRegistry->getNode($uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function pathExists(string $workspaceName, string $path): bool
    {
        return $this->getPathRegistry($workspaceName)->hasPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function uuidExists(string $workspaceName, string $uuid): bool
    {
        return $this->getNodeRegistry($workspaceName)->hasNode($uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function createWorkspace(string $workspaceName)
    {
        if (isset($this->pathRegistries[$workspaceName])) {
            throw new \InvalidArgumentException(sprintf(
                'Workspace "%s" already exists.',
                $workspaceName
            ));
        }

        $this->pathRegistries[$workspaceName] = new PathRegistry();
        $this->nodeRegistries[$workspaceName] = new NodeRegistry();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteWorkspace(string $workspaceName)
    {
        // assert that the workspace exists.
        $this->getPathRegistry($workspaceName);
        unset($this->pathRegistries[$workspaceName]);
    }

    public function listWorkspaces(): array
    {
        return array_keys($this->pathRegistries);
    }

    private function getPathRegistry(string $workspaceName): PathRegistry
    {
        if (!isset($this->pathRegistries[$workspaceName])) {
            throw new UndefinedMongoKeyException('workspace', $workspaceName, array_keys($this->pathRegistries));
        }

        return $this->pathRegistries[$workspaceName];
    }

    private function getNodeRegistry(string $workspaceName): NodeRegistry
    {
        if (!isset($this->nodeRegistries[$workspaceName])) {
            throw new UndefinedMongoKeyException('workspace', $workspaceName, array_keys($this->nodeRegistries));
        }

        return $this->nodeRegistries[$workspaceName];
    }
}
