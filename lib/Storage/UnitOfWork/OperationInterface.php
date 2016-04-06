<?php

namespace Jackalope2\Storage\UnitOfWork;

use Jackalope2\Storage\DriverInterface;

interface OperationInterface
{
    public function commit(DriverInterface $driver);

    public function rollback(DriverInterface $driver);
}
