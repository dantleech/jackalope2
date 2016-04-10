<?php

namespace Jackalope2\Storage;

use Jackalope2\Storage\NodeDataInterface;

interface DriverInterface
{
    public function store(string $workspaceName, NodeDataInterface $node);

    public function remove(string $workspaceName, string $uuid);

    public function findByPath(string $workspaceName, string $path): NodeDataInterface;

    public function findByUuid(string $workspaceName, string $uuid): NodeDataInterface;

    public function pathExists(string $workspaceName, string $path): bool;

    public function uuidExists(string $workspaceName, string $uuid): bool;

    public function listWorkspaces(): array;

    public function createWorkspace(string $workspaceNameName);

    public function deleteWorkspace(string $workspaceNameName);
}
