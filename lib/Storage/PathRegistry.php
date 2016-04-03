<?php

namespace Jackalope2\Storage;

class PathRegistry
{
    private $pathsByUUid;
    private $uuidsByPath;

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

    public function getPath(string $uuid)
    {
        if (!isset($this->pathsByUuid[$uuid])) {
            throw new \InvalidArgumentException(sprintf(
                'UUID "%s" is not known to the path registry, %d uuids currently registered.',
                $uuid, count($this->pathsByUuid)
            ));
        }

        return $this->pathsByUuid[$uuid];
    }

    public function getUuid(string $path)
    {
        if (!isset($this->uuidsByPath[$path])) {
            throw new \InvalidArgumentException(sprintf(
                'Path "%s" is not known to the path registry, %d paths currently registered.',
                $path, count($this->uuidsByPath)
            ));
        }

        return $this->uuidsByPath[$path];
    }
}
