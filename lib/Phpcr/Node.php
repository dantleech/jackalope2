<?php

namespace Jackalope2\Phpcr;

use PHPCR\NodeInterface;
use Jackalope2\Exception\UnsupportedOperationException;

class Node implements NodeInterface
{
    private $nodeManager;
    private $path;

    public function __construct(NodeManager $nodeManager, $path)
    {
        $this->nodeManager = $nodeManager;
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function addNode($relPath, $primaryNodeTypeName = null)
    {
        return $this->nodeManager->createNode(
            PathUtil::concat(
                [ $this->path, $relPath ]
            )
        );
    }


    /**
     * {@inheritDoc}
     */
    public function addNodeAutoNamed($nameHint = null, $primaryNodeTypeName = null)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function orderBefore($srcChildRelPath, $destChildRelPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function rename($newName)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function setProperty($name, $value, $type = null)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNode($relPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNodes($nameFilter = null, $typeFilter = null)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getNodeNames($nameFilter = null, $typeFilter = null)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty($relPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyValue($name, $type=null)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyValueWithDefault($relPath, $defaultValue)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperties($nameFilter = null)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesValues($nameFilter=null, $dereference=true)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrimaryItem()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getIndex()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getReferences($name = null)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getWeakReferences($name = null)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function hasNode($relPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function hasProperty($relPath)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function hasNodes()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function hasProperties()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrimaryNodeType()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getMixinNodeTypes()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function isNodeType($nodeTypeName)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function setPrimaryType($nodeTypeName)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function addMixin($mixinName)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function removeMixin($mixinName)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function setMixins(array $mixinNames)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function canAddMixin($mixinName)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinition()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function update($srcWorkspace)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getCorrespondingNodePath($workspaceName)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getSharedSet()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function removeSharedSet()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function removeShare()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function isCheckedOut()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function isLocked()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function followLifecycleTransition($transition)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getAllowedLifecycleTransitions()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getAncestor($depth)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return $this->nodeManager->findNode(PathUtil::parentPath($this->path));
    }

    /**
     * {@inheritDoc}
     */
    public function getDepth()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getSession()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function isNode()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function isNew()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function isModified()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function isSame(ItemInterface $otherItem)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function accept(ItemVisitorInterface $visitor)
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function revert()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function remove()
    {
        throw new UnsupportedOperationException(__METHOD__);
    }
}
