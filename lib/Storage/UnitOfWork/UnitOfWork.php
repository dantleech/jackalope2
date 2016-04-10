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
 * TODO: Remove this class, merge into NodeManager.
 */
class UnitOfWork
{
    private $nodeRegistry;
    private $pathRegistry;
    private $operationFactory;
    private $uuidFactory;

    private $pendingOperations;

    public function __construct(
        PathRegistry $pathRegistry,
        NodeRegistry $nodeRegistry,
        OperationFactory $operationFactory,
        UuidFactory $uuidFactory,

        \SplQueue $pendingOperations = null
    )
    {
        $this->pathRegistry = $pathRegistry;
        $this->nodeRegistry = $nodeRegistry;
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
        return $this->nodeRegistry->nodeExists($uuid);
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
        return $this->nodeRegistry->getNode($uuid);
    }

    /**
     * Return a registered node by path.
     */
    public function getNodeByPath(string $path)
    {
        $uuid = $this->pathRegistry->getUuid($path);

        return $this->nodeRegistry->getNode($uuid);
    }

    /**
     * Commit the transaction.
     *
     * If an exception is encountered, then the transaction will be rolled back.
     */
    public function commit(string $workspaceName, DriverInterface $driver)
    {
        $rollbackOperations = new \SplQueue();
        try {
            foreach ($this->pendingOperations as $operation) {
                $operation->commit($workspaceName, $driver);
                $rollbackOperations->enqueue($operation);
            }
        } catch (\Exception $e) {
            foreach ($rollbackOperations as $operation) {
                $operation->rollback($workspaceName, $driver);
            }

            throw $e;
        }
    }

    public function clear()
    {
        $this->nodeRegistry->clear();
        $this->pathRegistry->clear();
    }

    /**
     * Register a node at the given path.
     */
    private function register(Node $node, $path)
    {
        $this->nodeRegistry->registerNode($node);
        $this->pathRegistry->register($node->getUuid(), $path);
    }
}
