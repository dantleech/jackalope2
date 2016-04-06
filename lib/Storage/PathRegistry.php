<?php

declare(strict_types=1);

namespace Jackalope2\Storage;

class PathRegistry
{
    private $pathsByUuid = [];
    private $uuidsByPath = [];

    public function register(string $uuid, string $path)
    {
        if (isset($this->paths[$uuid])) {
            throw new \InvalidArgumentException(sprintf(
                'UUID "%s" is already registered against path "%s" (trying to register path "%s")',
                $uuid, $this->paths[$uuid], $path
            ));
        }

        $this->pathsByUuid[$uuid] = $path;
        $this->uuidsByPath[$path] = $uuid;
    }

    public function getPath(string $uuid): string
    {
        if (!isset($this->pathsByUuid[$uuid])) {
            throw new \InvalidArgumentException(sprintf(
                'UUID "%s" is not known to the path registry, %d uuids currently registered.',
                $uuid, count($this->pathsByUuid)
            ));
        }

        return $this->pathsByUuid[$uuid];
    }

    public function hasPath($path): bool
    {
        return isset($this->uuidsByPath[$path]);
    }

    public function hasUuid($uuid): bool
    {
        return isset($this->pathsByUuid[$uuid]);
    }

    public function getUuid(string $path): string
    {
        if (!isset($this->uuidsByPath[$path])) {
            throw new \InvalidArgumentException(sprintf(
                'Path "%s" is not known to the path registry, %d paths currently registered.',
                $path, count($this->uuidsByPath)
            ));
        }

        return $this->uuidsByPath[$path];
    }

    /**
     * Remove the given node and all its desendents from the registry.
     */
    public function removeByUuid(string $uuid)
    {
        $targetPath = $this->pathsByUuid[$uuid];

        foreach ($this->getDescendants($targetPath) as $path) {
            $uuid = $this->getUuid($path);
            unset($this->uuidsByPath[$path]);
            unset($this->pathsByUuid[$uuid]);
        }
    }

    /**
     * Return the path of the given node followed by all of its descendants.
     */
    public function getDescendants($targetPath)
    {
        $paths = [];
        foreach ($this->uuidsByPath as $path => $uuid) {
            if (0 === strpos($path, $targetPath)) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    public function clear()
    {
        $this->pathsByUuid = [];
        $this->uuidsByPath = [];
    }
}
