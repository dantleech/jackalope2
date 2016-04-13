<?php

namespace Jackalope2\Storage\Operation;

use Jackalope2\Storage\OperationInterface;

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

