<?php

namespace Jackalope2\Storage;

interface NodeDataInterface
{
    public function getPath(): string;

    public function getUuid(): string;

    public function get($key);

    public function keys(): array;
}
