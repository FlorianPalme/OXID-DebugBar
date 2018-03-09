<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core;


use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class UtilsViewTest extends UnitTestCase
{
    /**
     *
     */
    public function testPolyfillFunctions()
    {
        /** @var \FlorianPalme\DebugBar\Core\UtilsView $utils */
        $utils = oxNew(UtilsView::class);
        $utils->includeFunctions();

        $this->assertTrue(function_exists('getallheaders'));
    }

    /**
     *
     * @throws \ReflectionException
     */
    public function testIfSmartyPluginDirectoryIsAdded()
    {
        /** @var \FlorianPalme\DebugBar\Core\UtilsView $utilsview */
        $utilsview = oxNew(UtilsView::class);

        /** @var \Smarty $smarty */
        $smarty = new \Smarty();

        $this->callMethod($utilsview, '_fillCommonSmartyProperties', [$smarty]);

        $smartyPluginsDir = Registry::getConfig()->getModulesDir() . "/fp/debugbar/Core/Smarty/Plugin";

        $this->assertTrue(in_array($smartyPluginsDir, $smarty->plugins_dir));
    }
}