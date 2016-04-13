<?php

namespace Jackalope2\Storage;

/**
 * The storage node contains only the node data and its UUID.
 */
interface NodeInterface
{
    /**
     * Return the storage node UUID.
     */
    public function getUuid(): string;

    public function getPath(): string;

    /**
     * Return a scalar / array property.
     */
    public function get($key);

    /**
     * Return the property names.
     */
    public function keys(): array;
}
