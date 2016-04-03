<?php

namespace Jackalope2\Storage\NodeData;

use Jackalope2\Storage\NodeDataInterface;

class UnpersistedNodeData implements NodeDataInterface
{
    private $data;

    public function get($key)
    {
        if (!isset($this->data[$key])) {
            throw new UndefinedArrayKeyException('property', $key, array_keys($this->data));
        }

        return $this->data[$key];
    }

    public function keys()
    {
        return array_keys($this->data);
    }
}
