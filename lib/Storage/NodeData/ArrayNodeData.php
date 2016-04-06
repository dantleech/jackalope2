<?php

namespace Jackalope2\Storage\NodeData;

use Jackalope2\Storage\NodeDataInterface;

class ArrayNodeData implements NodeDataInterface
{
    private $uuid;
    private $path;
    private $data;

    public function __construct(string $uuid, string $path, array $data)
    {
        $this->path = $path;
        $this->data = $data;
        $this->uuid = $uuid;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function get($key)
    {
        if (!isset($this->data[$key])) {
            throw new UndefinedArrayKeyException('data property', $key, $this->keys());
        }

        return $this->data[$key];
    }

    public function keys(): array
    {
        return array_keys($this->data);
    }
}
