<?php

namespace Jackalope2\Storage;

interface NodeDataInterface
{
    public function get($key);

    public function keys();
}
