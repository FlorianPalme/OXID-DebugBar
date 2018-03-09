<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests;


class UnitTestCase extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * @param $obj
     * @param $method
     * @param null $args
     * @return mixed
     * @throws \ReflectionException
     */
    protected function callMethod($obj, $method, $args = null)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        if ($args === null) {
            return $method->invoke($obj);
        } else {
            return $method->invokeArgs($obj, $args);
        }
    }
}