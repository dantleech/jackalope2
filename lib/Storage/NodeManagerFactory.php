<?php

declare(strict_types=1);

namespace Jackalope2\Storage;

use Jackalope2\Storage\UnitOfWork\UnitOfWork;
use Jackalope2\Storage\PathRegistry;
use Jackalope2\Storage\UnitOfWork\OperationFactory;
use Jackalope2\Storage\NodeManager;
use Ramsey\Uuid\UuidFactory;

class NodeManagerFactory
{
    private $uuidFactory;

    public function __construct(
        UuidFactory $uuidFactory
    )
    {
        $this->uuidFactory = $uuidFactory;
    }

    public function create(string $workspaceName): NodeManager
    {
        $unitOfWork = new UnitOfWork(
            new PathRegistry(),
            new OperationFactory(),
            $this->uuidFactory
        );

        return new NodeManager(
            $workspaceName,
            $driver,
            $unitOfWork
        );
    }
}
