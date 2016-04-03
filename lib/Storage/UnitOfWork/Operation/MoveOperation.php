<?php

namespace Jackalope2\Storage\UnitOfWork\Operation;

use Jackalope2\Storage\UnitOfWork\OperationInterface;

class MoveOperation implements OperationInterface
{
    private $srcPath;
    private $destPath;

    public function __construct(string $srcPath, string $destPath)
    {
        $this->srcPath = $srcPath;
        $this->destPath = $destPath;
    }

    public function commit(DriverInterface $driver)
    {
        $driver->move($srcPath, $destPath);
    }

    public function rollback(DriverInterface $driver)
    {
        $driver->move($destPath, $srcPath);
    }
}

