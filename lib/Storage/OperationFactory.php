<?php

namespace Jackalope2\Storage;

use Jackalope2\Storage\Operation\CreateOperation;
use Jackalope2\Storage\NodeInterface;

class OperationFactory
{
    public function create(NodeInterface $node)
    {
        return new CreateOperation($node);
    }
}
