<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests;


use OxidEsales\Eshop\Core\Registry;

class UnitTestCase extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        // Neu setzen überladener Klassen
        $utilsView = oxNew(\OxidEsales\Eshop\Core\UtilsView::class);
        Registry::set(\OxidEsales\Eshop\Core\UtilsView::class, $utilsView);

        $config = oxNew(\OxidEsales\Eshop\Core\Config::class);
        $config->init();
        Registry::set(\OxidEsales\Eshop\Core\Config::class, $config);
    }

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