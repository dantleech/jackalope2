<?php

namespace Jackalope2\Storage\UnitOfWork;

use Jackalope2\Storage\Node;
use Jackalope2\Storage\UnitOfWork\Operation\CreateOperation;
use Jackalope2\Storage\NodeDataInterface;

class OperationFactory
{
    public function create(NodeDataInterface $node)
    {
        return new CreateOperation($node);
    }
}
