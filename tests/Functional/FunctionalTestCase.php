<?php

namespace Jackalope2\Tests\Functional;

use Jackalope2\Jackalope2;

abstract class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    public function getContainer()
    {
        return Jackalope2::getContainer();
    }
}
