<?php

namespace Jackalope2;

interface StorageInterface
{
    public function store(Node $node);

    public function remove(string $uuid);

    public function nodeByPath(string $path): Node;

    public function dataByUuid(string $uuid): Node;
}
