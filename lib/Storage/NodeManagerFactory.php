<?php

declare(strict_types=1);

namespace Jackalope2\Storage;

use Jackalope2\Storage\PathRegistry;
use Jackalope2\Storage\OperationFactory;
use Jackalope2\Storage\NodeManager;
use Ramsey\Uuid\UuidFactory;
use Jackalope2\Storage\DriverInterface;

class NodeManagerFactory
{
    private $uuidFactory;
    private $driver;

    public function __construct(
        UuidFactory $uuidFactory,
        DriverInterface $driver
    )
    {
        $this->uuidFactory = $uuidFactory;
        $this->driver = $driver;
    }

    public function create(string $workspaceName): NodeManager
    {
        return new NodeManager(
            $workspaceName,
            $this->driver,
            new NodeRegistry(),
            new OperationFactory(),
            $this->uuidFactory
        );
    }
}
