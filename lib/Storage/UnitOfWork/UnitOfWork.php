<?php

namespace Jackalope2\Storage\UnitOfWork;

use Jackalope2\Exception\UndefinedArrayKeyException;
use Jackalope2\Storage\Node;
use PHPCR\Util\PathHelper;
use PHPCR\Util\UUIDHelper;
use Jackalope2\Exception\UnsupportedOperationException;
use Jackalope2\Storage\NodeDataInterface;
use Jackalope2\Storage\DriverInterface;
use Jackalope2\Storage\PathRegistry;
use Jackalope2\Storage\UnitOfWork\OperationFactory;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Jackalope2\Storage\NodeData\UnpersistedNodeData;

/**
 * The unit of work knows about the path registry and driver.
 *
 * It needs the driver in order to commit transactions to it, the NodeManager
 * also knows about the driver - should that dependency be moved here? 
 *
 * TODO: Is there even a requirement for a node manager?
 */
class UnitOfWork
{
    private $nodes = [];
    private $pathRegistry;
    private $driver;
    private $operationFactory;
    private $uuidFactory;

    private $pendingOperations;

    public function __construct(
        DriverInterface $driver,
        PathRegistry $pathRegistry,
        OperationFactory $operationFactory,
        UuidFactory $uuidFactory,

        \SplQueue $pendingOperations = null
    )
    {
        $this->driver = $driver;
        $this->pathRegistry = $pathRegistry;
        $this->operationFactory = $operationFactory;
        $this->uuidFactory = $uuidFactory;

        $this->pendingOperations = $pendingOperations ?: new \SplQueue();
    }

    /**
     * Create and register a new node at the given path.
     *
     * NOTE: We do this here as the node has a dependency on the path registry and
     *       the Node itself is not coupled to anything in particular, so it does no
     *       harm here, and it means we do not expose the PathRegistry to the general
     *       public.
     *
     * TODO: Checking for parent node existence, it may be more performant to
     *       leave this until the commit(). Should find out what the performance cost
     *       is if any.
     */
    public function createNode(string $path): Node
    {
        $uuid = $this->uuidFactory->uuid4();
        $node = new Node(
            $uuid,
            $this->pathRegistry,
            $nodeData = new UnpersistedNodeData($uuid, $path, [])
        );
        $this->register($node, $path);
        $this->pendingOperations->enqueue($this->operationFactory->create($nodeData));

        return $node;
    }

    /**
     * Register a storage NodeDataInterface instance wrap it in a Node.
     */
    public function registerNodeData(NodeDataInterface $nodeData)
    {
        $node = new Node(
            $nodeData->getUuid(),
            $this->pathRegistry,
            $nodeData
        );
        $this->register($node, $nodeData->getPath());

        return $node;
    }

    /**
     * Enqueue a move operation.
     */
    public function enqueueMove($srcPath, $destPath)
    {
        $this->pendingOperations->enqueue(new MoveOperation($srcPath, $destPath));
    }

    public function hasUuid($uuid): bool
    {
        return isset($this->nodes[$uuid]);
    }

    public function hasPath($path): bool
    {
        return $this->pathRegistry->hasPath($path);
    }

    /**
     * Return a registered node by UUID.
     */
    public function getNodeByUuid(string $uuid): Node
    {
        if (false === $this->hasUuid($uuid)) {
            throw new \InvalidArgumentException(sprintf(
                'Node with UUID "%s" is not registered',
                $uuid
            ));
        }

        return $this->nodes[$uuid];
    }

    /**
     * Return a registered node by path.
     */
    public function getNodeByPath(string $path)
    {
        $uuid = $this->pathRegistry->getUuid($path);

        return $this->getNodeByUuid($uuid);
    }

    /**
     * Commit the transaction.
     *
     * If an exception is encountered, then the transaction will be rolled back.
     */
    public function commit()
    {
        $rollbackOperations = new \SplQueue();
        try {
            foreach ($this->pendingOperations as $operation) {
                $operation->commit($this->driver);
                $rollbackOperations->enqueue($operation);
            }
        } catch (\Exception $e) {
            foreach ($rollbackOperations as $operation) {
                $operation->rollback($this->driver);
            }

            throw $e;
        }
    }

    public function clear()
    {
        $this->nodes = [];
        $this->pathRegistry->clear();
    }

    /**
     * Register a node at the given path.
     */
    private function register(Node $node, $path)
    {
        $this->nodes[$node->getUuid()] = $node;
        $this->pathRegistry->register($node->getUuid(), $path);
    }
}
