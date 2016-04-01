<?php

namespace Jackalope2\Exception;

class UndefinedArrayKeyException extends \InvalidArgumentException
{
    public function __construct($context, $undefinedKey, array $knownKeys)
    {
        parent::__construct(sprintf(
            'Undefined %s "%s", known %ss: "%s"',
            $context, $undefinedKey, $context, implode('", "', $knownKeys)
        ));
    }
}
