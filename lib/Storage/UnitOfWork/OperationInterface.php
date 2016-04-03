<?php

namespace Jackalope2\Storage\UnitOfWork;

interface OperationInterface
{
    public function commit(DriverInterface $driver);

    public function rollback(DriverInterface $driver);
}
