<?php

declare(strict_types=1);

namespace Jackalope2\Storage\Driver;

use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\NodeInterface;
use Jackalope2\Storage\PathRegistry;
use Jackalope2\Exception\UndefinedArrayKeyException;
use Jackalope2\Storage\NodeRegistry;
use Jackalope2\Storage\NodeReference;

class ArrayDriver implements DriverInterface
{
    private $nodeRegistries = [];

    /**
     * {@inheritdoc}
     */
    public function store(string $workspaceName, NodeInterface $node)
    {
        $nodeRegistry = $this->getNodeRegistry($workspaceName);
        $nodeRegistry->registerNode($node);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $workspaceName, string $uuid)
    {
        $registry = $this->getNodeRegistry($workspaceName);
        $node = $registry->getNodeByUuid($uuid);
        $registry->removeNode($node->getUuid());
    }

    /**
     * {@inheritdoc}
     */
    public function findByPath(string $workspaceName, string $path): NodeInterface
    {
        return $this->getNodeRegistry($workspaceName)->getNodeByPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function findByUuid(string $workspaceName, string $uuid): NodeInterface
    {
        return $this->getNodeRegistry($workspaceName)->getNodeByUuid($uuid);

    }

    /**
     * {@inheritdoc}
     */
    public function pathExists(string $workspaceName, string $path): bool
    {
        return $this->getNodeRegistry($workspaceName)->hasPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function uuidExists(string $workspaceName, string $uuid): bool
    {
        return $this->getNodeRegistry($workspaceName)->hasUuid($uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function createWorkspace(string $workspaceName)
    {
        if (isset($this->nodeRegistries[$workspaceName])) {
            throw new \InvalidArgumentException(sprintf(
                'Workspace "%s" already exists.',
                $workspaceName
            ));
        }

        $this->nodeRegistries[$workspaceName] = new NodeRegistry();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteWorkspace(string $workspaceName)
    {
        // assert that the workspace exists.
        $this->getNodeRegistry($workspaceName);
        unset($this->nodeRegistries[$workspaceName]);
    }

    public function listWorkspaces(): array
    {
        return array_keys($this->nodeRegistries);
    }

    private function getNodeRegistry(string $workspaceName): NodeRegistry
    {
        if (!isset($this->nodeRegistries[$workspaceName])) {
            throw new UndefinedArrayKeyException('workspace', $workspaceName, array_keys($this->nodeRegistries));
        }

        return $this->nodeRegistries[$workspaceName];
    }
}
