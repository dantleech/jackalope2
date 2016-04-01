<?php

namespace Jackalope2\Phpcr;

use PHPCR\RepositoryInterface;
use PHPCR\CredentialsInterface;
use Jackalope2\Exception\UndefinedArrayKeyException;

class Repository implements RepositoryInterface
{
    private $descriptors = [
        self::WRITE_SUPPORTED => true,
    ];

    public function __construct(array $config = [])
    {
        $this->container = new Container($config);
    }

    public function login(CredentialsInterface $credentials, $workspaceName = null)
    {
        // TODO: Security

        return $this->container->get('phpcr.session.factory')->createSession();
    }

    public function getDescriptorKeys()
    {
        return array_keys($this->descriptors);
    }

    public function isStandardDescriptor($key)
    {
        return true;
    }

    public function getDescriptor($key)
    {
        if (!$this->descriptors[$key]) {
            throw new UndefinedArrayKeyException('descriptor', $key, array_keys($this->descriptors));
        }

        return $this->descriptors[$key];
    }
}
