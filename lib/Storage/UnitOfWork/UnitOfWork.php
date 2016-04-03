<?php

namespace Jackalope2\Storage;

use Jackalope2\Exception\UndefinedArrayKeyException;
use Jackalope2\Storage\Node;
use PHPCR\Util\PathHelper;
use PHPCR\Util\UUIDHelper;
use Jackalope2\Exception\UnsupportedOperationException;

class UnitOfWork
{
    private $nodes = [];
    private $pathRegistry;
    private $driver;

    private $pendingOperations;
    private $rollbackOperations;

    public function __construct(DriverInterface $driver, PathRegistry $pathRegistry)
    {
        $this->driver = $driver;
        $this->pathRegistry = $pathRegistry;
        $this->pendingOperations = new \SplQueue();
        $this->rollbackOperations = new \SplQueue();
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
    public function createNew(string $path): Node
    {
        $parentPath = PathHelper::parentPath($path);
        $node = new Node(
            $uuid = UUIDHelper::generateUuid(),
            $this->pathRegistry
        );
        $this->register($node, $path);
        $this->pendingOperations->enqueue(new CreateOperation($node));

        return $node;
    }

    /**
     * Register a node at the given path.
     */
    public function register(Node $node, $path)
    {
        $this->nodes[$node->getUuid()] = $node;
        $this->pathRegistry->register($node->getUuid(), $node->getPath());
    }

    /**
     * Enqueue a move operation.
     */
    public function enqueueMove($srcPath, $destPath)
    {
        $this->pendingOperations->enqueue(new MoveOperation($srcPath, $destPath));
    }

    public function hasUuid($uuid): boolean
    {
        return isset($this->nodes[$node->getUuid()]);
    }

    public function hasPath($path): boolean
    {
        return $this->pathRegistry->hasPath($path);
    }

    /**
     * Return a registered node by UUID.
     */
    public function getNodeByUuid($uuid): Node
    {
        if (false === $this->hasUuid($uuid)) {
            throw new \InvalidArgumentException(sprintf(
                'Node with UUID "%s" is not registred',
                $uuid
            ));
        }

        return $this->nodes($uuid);
    }

    /**
     * Return a registered node by path.
     */
    public function getNodeByPath($path)
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
        try {
            foreach ($this->pendingOperations as $operation) {
                $operation->commit($this->driver);
                $this->rollbackOperations->enqueue($operation);
            }
        } catch (\Exception $e) {
            foreach ($this->rollbackOperations as $operation) {
                $operation->rollback($this->driver);
            }
        }

        $this->rollbackOperations = new \SplQueue();
    }
}
