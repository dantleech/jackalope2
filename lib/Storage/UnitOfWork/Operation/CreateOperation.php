<?php

namespace Jackalope2\Storage\UnitOfWork\Operation;

use Jackalope2\Storage\UnitOfWork\OperationInterface;

class CreateOperation implements OperationInterface
{
    private $node;

    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    public function commit(DriverInterface $driver)
    {
        $driver->store($this->node);
    }

    public function rollback(DriverInterface $driver)
    {
        $driver->remove($this->node->getUuid());
    }
}
