<?php

declare(strict_types=1);

namespace Jackalope2\Storage;

use PHPCR\Util\UUIDHelper;
use Jackalope2\Storage\Node;
use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\NodeInterface;
use Ramsey\Uuid\UuidFactory;
use Jackalope2\Storage\OperationFactory;
use Jackalope2\Storage\NodeRegistry;
use Jackalope2\Storage\PathRegistry;
use Jackalope2\Storage\Node\ArrayNode;

/**
 * The node manager knows about the storage and the unit of work.
 */
class NodeManager
{
    private $workspaceName;
    private $driver;
    private $nodeRegistry;
    private $operationFactory;
    private $uuidFactory;

    public function __construct(
        string $workspaceName,
        DriverInterface $driver, 
        NodeRegistry $nodeRegistry = null,
        OperationFactory $operationFactory = null,
        UuidFactory $uuidFactory,
        \SplQueue $pendingOperations = null
    )
    {
        $this->workspaceName = $workspaceName;
        $this->driver = $driver;
        $this->nodeRegistry = $nodeRegistry ?: new NodeRegistry();
        $this->operationFactory = $operationFactory ?: new OperationFactory();
        $this->uuidFactory = $uuidFactory ?: new UuidFactory();
        $this->pendingOperations = $pendingOperations ?: new \SplQueue();
    }

    public function getWorkspaceName()
    {
        return $this->workspaceName;
    }

    public function findNodeByUuid($uuid): NodeInterface
    {
        if ($this->nodeRegistry->hasUuid($uuid)) {
            return $this->nodeRegistry->getNodeByUuid($uuid);
        }

        $node = $this->driver->findByUuid($this->workspaceName, $uuid);
        $this->nodeRegistry->registerNode($node);

        return $node;
    }

    public function findNodeByPath($path): NodeInterface
    {
        if ($this->nodeRegistry->hasPath($path)) {
            return $this->nodeRegistry->getNodeByPath($path);
        }

        $node = $this->driver->findByPath($this->workspaceName, $path);
        $this->nodeRegistry->registerNode($node);

        return $node;
    }

    public function createNode($path): NodeInterface
    {
        $uuid = $this->uuidFactory->uuid4();
        $node = new ArrayNode(
            (string) $uuid,
            $path
        );
        $this->nodeRegistry->registerNode($node);
        $this->pendingOperations->enqueue($this->operationFactory->create($node));

        return $node;
    }

    public function save()
    {
        $rollbackOperations = new \SplQueue();
        try {
            foreach ($this->pendingOperations as $operation) {
                $operation->commit($this->workspaceName, $this->driver);
                $rollbackOperations->enqueue($operation);
            }
        } catch (\Exception $e) {
            foreach ($rollbackOperations as $operation) {
                $operation->rollback($this->workspaceName, $this->driver);
            }

            throw $e;
        }
    }

    public function clear()
    {
        $this->nodeRegistry->clear();
    }
}
