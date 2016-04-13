<?php

namespace Jackalope2\Storage;

use Jackalope2\Storage\DriverInterface;

interface OperationInterface
{
    public function commit(string $workspace, DriverInterface $driver);

    public function rollback(string $workspace, DriverInterface $driver);
}
