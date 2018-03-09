<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core;


use FlorianPalme\DebugBar\Core\DebugBar;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\UtilsView;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class ConfigTest extends UnitTestCase
{
    /**
     *
     */
    public function testGetDebugBarConfigTrustedIps()
    {
        /** @var \FlorianPalme\DebugBar\Core\Config $config */
        $config = oxNew(Config::class);

        $this->assertInternalType('array', $config->getDebugBarConfigTrustedIps());
    }

    /**
     *
     */
    public function testGetDebugBarConfigTheme()
    {
        /** @var \FlorianPalme\DebugBar\Core\Config $config */
        $config = oxNew(Config::class);

        $this->assertInternalType('string', $config->getDebugBarConfigTheme());
    }
}