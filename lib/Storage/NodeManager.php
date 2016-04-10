<?php

declare(strict_types=1);

namespace Jackalope2\Storage;

use PHPCR\Util\UUIDHelper;
use Jackalope2\Storage\Node;
use Jackalope2\Storage\UnitOfWork\UnitOfWork;
use Jackalope2\Storage\DriverInterface;

/**
 * The node manager knows about the storage and the unit of work.
 */
class NodeManager
{
    private $workspaceName;
    private $unitOfWork;
    private $driver;

    public function __construct(
        string $workspaceName,
        DriverInterface $driver, 
        UnitOfWork $unitOfWork
    )
    {
        $this->workspaceName = $workspaceName;
        $this->unitOfWork = $unitOfWork;
        $this->driver = $driver;
    }

    public function findNodeByUuid($uuid): Node
    {
        if ($this->unitOfWork->hasUuid($uuid)) {
            return $this->unitOfWork->getNodeByUuid($uuid);
        }

        $nodeData = $this->driver->findByUuid($workspace, $uuid);
        $node = $this->unitOfWork->registerNodeData($nodeData);

        return $node;
    }

    public function findNodeByPath($path): Node
    {
        if (
            $path === '/' && 
            false === $this->unitOfWork->hasPath($path) &&
            false === $this->driver->pathExists($workspace, '/')) {
            return $this->unitOfWork->createNode('/');
        }

        if ($this->unitOfWork->hasPath($path)) {
            return $this->unitOfWork->getNodeByPath($path);
        }

        $nodeData = $this->driver->findByPath($workspace, $path);
        $node = $this->unitOfWork->registerNodeData($nodeData);

        return $node;
    }

    public function createNode($path): Node
    {
        return $this->unitOfWork->createNode($path);
    }

    public function move($srcPath, $destPAth)
    {
        $this->unitOfWork->enqueueMove($srcPath, $destPath);
    }

    public function save()
    {
        $this->unitOfWork->commit($driver);
    }

    public function clear()
    {
        $this->unitOfWork->clear();
    }
}
