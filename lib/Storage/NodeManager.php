<?php

declare(strict_types=1);

namespace Jackalope2\Storage;

use PHPCR\Util\UUIDHelper;
use Jackalope2\Storage\Node;

class NodeManager
{
    private $unitOfWork;

    public function __construct(UnitOfWork $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    public function findNodeByUuid($uuid): Node
    {
        if ($this->unitOfWork->hasUuid($uuid)) {
            return $this->unitOfWork->nodeByUuid($uuid);
        }

        $node = $this->storage->findByUuid($uuid);
        $this->unitOfWork->registerNode($node);

        return $node;
    }

    public function findNodeByPath($path): Node
    {
        if ($this->unitOfWork->hasPath($path)) {
            return $this->unitOfWork->nodeByPath($path);
        }

        $node = $this->storage->findByPath($path);
        $this->unitOfWork->registerNode($node);

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
}
