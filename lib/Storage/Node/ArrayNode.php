<?php

namespace Jackalope2\Storage\Node;

use Jackalope2\Storage\NodeInterface;

class ArrayNode implements NodeInterface
{
    private $uuid;
    private $data;
    private $path;

    public function __construct(string $uuid, string $path, array $data = [])
    {
        $this->data = $data;
        $this->uuid = $uuid;
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (!isset($this->data[$key])) {
            throw new UndefinedArrayKeyException('data property', $key, $this->keys());
        }

        return $this->data[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function keys(): array
    {
        return array_keys($this->data);
    }
}
