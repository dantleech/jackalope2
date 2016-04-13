<?php

namespace Jackalope2\Storage;

use Jackalope2\Storage\NodeInterface;

interface DriverInterface
{
    public function store(string $workspaceName, NodeInterface $node);

    public function remove(string $workspaceName, string $uuid);

    public function findByPath(string $workspaceName, string $path): NodeInterface;

    public function findByUuid(string $workspaceName, string $uuid): NodeInterface;

    public function pathExists(string $workspaceName, string $path): bool;

    public function uuidExists(string $workspaceName, string $uuid): bool;

    public function listWorkspaces(): array;

    public function createWorkspace(string $workspaceNameName);

    public function deleteWorkspace(string $workspaceNameName);
}
