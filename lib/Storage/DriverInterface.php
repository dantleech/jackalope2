<?php

namespace Jackalope2\Storage;

use Jackalope2\Storage\NodeDataInterface;

interface DriverInterface
{
    public function store(NodeDataInterface $node);

    public function remove(string $uuid);

    public function findByPath(string $path): NodeDataInterface;

    public function findByUuid(string $uuid): NodeDataInterface;

    public function pathExists(string $path): bool;

    public function uuidExists(string $uuid): bool;
}
