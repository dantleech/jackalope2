<?php

namespace Jackalope2\Storage\Operation;

use Jackalope2\Storage\OperationInterface;
use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\NodeInterface;

class CreateOperation implements OperationInterface
{
    private $node;

    public function __construct(NodeInterface $node)
    {
        $this->node = $node;
    }

    public function commit(string $workspace, DriverInterface $driver)
    {
        $node = $driver->store($workspace, $this->node);
    }

    public function rollback(string $workspace, DriverInterface $driver)
    {
        $driver->remove($workspace, $this->node->getUuid());
    }
}
