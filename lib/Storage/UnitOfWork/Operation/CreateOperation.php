<?php

namespace Jackalope2\Storage\UnitOfWork\Operation;

use Jackalope2\Storage\UnitOfWork\OperationInterface;
use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\Node;
use Jackalope2\Storage\NodeDataInterface;

class CreateOperation implements OperationInterface
{
    private $node;

    public function __construct(NodeDataInterface $node)
    {
        $this->node = $node;
    }

    public function commit($workspace, DriverInterface $driver)
    {
        $driver->store($this->node);
    }

    public function rollback($workspace, DriverInterface $driver)
    {
        $driver->remove($this->node->getUuid());
    }
}
