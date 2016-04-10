<?php

declare(strict_types=1);

namespace Jackalope2\Phpcr;

class SessionFactory
{
    private $nodeManagerFactory;
    private $repository;

    public function __construct(
        Repository $repository,
        NodeManagerFactory $nodeManagerFactory
    )
    {
        $this->nodeManagerFactory = $nodeManagerFactory;
        $this->repository = $repository;
    }

    public function create(Workspace $workspace): SessionInterface
    {
        $nodeManager = $nodeManagerFactory->create($workspaceName);
        return new Session(
            $this->repository,

            $nodeManager
        );
    }
}
