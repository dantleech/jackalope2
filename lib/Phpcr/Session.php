<?php

namespace Jackalope2\Phpcr;

use PHPCR\WorkspaceInterface;
use PHPCR\RepositoryInterface;

class Session
{
    private $repository;
    private $workspace;
    private $nodeManager;

    public function __construct(
        NodeManager $nodeManager,
        RepositoryInterface $repository, 
        WorkspaceInterface $workspace
    )
    {
        $this->repository = $repository;
        $this->workspace = $workspace;
        $this->nodeManager = $nodeManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserID()
    {
        return 1;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributeNames()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * {@inheritDoc}
     */
    public function getRootNode()
    {
        return $this->nodeManager->findNodeByPath('/');
    }

    /**
     * {@inheritDoc}
     */
    public function impersonate(CredentialsInterface $credentials)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNodeByIdentifier($id)
    {
        return $this->nodeManager->findByUuid($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getNodesByIdentifier($ids)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getItem($absPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNode($absPath, $depthHint = -1)
    {
        return $this->nodeManager->findNodeByPath($absPath, $depthHint);
    }

    /**
     * {@inheritDoc}
     */
    public function getNodes($absPaths)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty($absPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperties($absPaths)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function itemExists($absPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function nodeExists($absPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function propertyExists($absPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function move($srcAbsPath, $destAbsPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function removeItem($absPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        $this->nodeManager->save();
    }

    /**
     * {@inheritDoc}
     */
    public function refresh($keepChanges)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function hasPendingChanges()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function hasPermission($absPath, $actions)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function checkPermission($absPath, $actions)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function hasCapability($methodName, $target, array $arguments)
    {
        throw new UnsupportedOperationException(__METHOD__);
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
    public function exportSystemView($absPath, $stream, $skipBinary, $noRecurse)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function exportDocumentView($absPath, $stream, $skipBinary, $noRecurse)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function setNamespacePrefix($prefix, $uri)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespacePrefixes()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespaceURI($prefix)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespacePrefix($uri)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function logout()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function isLive()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccessControlManager()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getRetentionManager()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }
}
