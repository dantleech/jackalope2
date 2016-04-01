<?php

namespace Jackalope2\Exception;

class UnsupportedOperationException extends \BadMethodCallException
{
    public function __construct($method)
    {
        parent::__construct(sprintf(
            'Unsupported method "%s"', $method
        ));
    }
}
