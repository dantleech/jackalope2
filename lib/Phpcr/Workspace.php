<?php

namespace Jackalope2\Phpcr;

use PHPCR\WorkspaceInterface;

class Workspace implements WorkspaceInterface
{
    private $name;
    private $driver;

    public function __construct(
        $name,
        DriverInterface $driver
    ) 
    {
        $this->name = $name;
        $this->driver = $driver;
    }


    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function copy($srcAbsPath, $destAbsPath, $srcWorkspace = null)
    {
        $this->driver->copy($this->name, $srcAbsPath, $destAbsPath, $srcWorkspace);
    }

    /**
     * {@inheritDoc}
     */
    public function cloneFrom($srcWorkspace, $srcAbsPath, $destAbsPath, $removeExisting)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function move($srcAbsPath, $destAbsPath)
    {
        $this->driver->move($srcAbsPath, $destAbsPath);
    }

    /**
     * {@inheritDoc}
     */
    public function removeItem($absPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    public function getLockManager()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryManager()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionManager()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespaceRegistry()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNodeTypeManager()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getObservationManager()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getRepositoryManager()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getVersionManager()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccessibleWorkspaceNames()
    {
        return $this->driver->listWorkspaces();
    }

    /**
     * {@inheritDoc}
     */
    public function importXML($parentAbsPath, $uri, $uuidBehavior)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function createWorkspace($name, $srcWorkspace = null)
    {
        return $this->driver->createWorkspace($name, $srcWorkspace);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteWorkspace($name)
    {
        return $this->driver->deleteWorkspace($name, $srcWorkspace);
    }
}
