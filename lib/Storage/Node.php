<?php 

declare(strict_types=1);

namespace Jackalope2\Storage;

use Jackalope2\Util\PathUtil;

/**
 * Storage level representation of a node.
 */
class Node
{
    private $uuid;
    private $pathRegistry;
    private $nodeData;

    public function __construct(string $uuid, PathRegistry $pathRegistry, NodeDataInterface $data = null)
    {
        $this->uuid = $uuid;
        $this->pathRegistry = $pathRegistry;
        $this->nodeData = $nodeData ?: new UnpersistedNodeData();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getPath(): string
    {
        return $this->pathRegistry->getPath($this->uuid);
    }

    public function getPropertyValue($name): Property
    {
        return $this->data->get($name);
    }

    public function getPropertyNames(): array
    {
        return $this->data->keys();
    }
}
