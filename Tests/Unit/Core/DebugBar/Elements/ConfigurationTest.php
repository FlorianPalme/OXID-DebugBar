<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core\DebugBar\Elements;

use FlorianPalme\DebugBar\Core\DebugBar\Elements\Configuration;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class ConfigurationTest extends UnitTestCase
{
    /**
     *
     */
    public function testGetTitle()
    {
        /** @var Configuration $tab */
        $tab = oxNew(Configuration::class);

        $this->assertInternalType('string', $tab->getTitle());
    }

    /**
     *
     */
    public function testGetPHPConfigurationPHPInt8()
    {
        $tab = $this->getMock(Configuration::class, ['getPhpIntSize']);
        $tab
            ->expects($this->once())
            ->method('getPhpIntSize')
            ->will($this->returnValue(8));

        $response = $this->callMethod($tab, 'getPHPConfiguration');

        $this->assertInternalType('string', $response);
    }

    /**
     * @depends testGetPHPConfigurationPHPInt8
     */
    public function testGetPHPConfigurationPHPInt4()
    {
        $tab = $this->getMock(Configuration::class, ['getPhpIntSize']);
        $tab
            ->expects($this->once())
            ->method('getPhpIntSize')
            ->will($this->returnValue(4));

        $response = $this->callMethod($tab, 'getPHPConfiguration');

        $this->assertInternalType('string', $response);
    }

    /**
     * @depends testGetPHPConfigurationPHPInt8
     * @depends testGetPHPConfigurationPHPInt4
     */
    public function testGetPHPConfigurationPHPIntNA()
    {
        $tab = $this->getMock(Configuration::class, ['getPhpIntSize']);
        $tab
            ->expects($this->once())
            ->method('getPhpIntSize')
            ->will($this->returnValue(0));

        $response = $this->callMethod($tab, 'getPHPConfiguration');

        $this->assertInternalType('string', $response);
    }

    /**
     * @depends testGetPHPConfigurationPHPInt8
     * @depends testGetPHPConfigurationPHPInt4
     * @depends testGetPHPConfigurationPHPIntNA
     */
    public function testGetContent()
    {
        /** @var Configuration $tab */
        $tab = oxNew(Configuration::class);

        $this->assertInternalType('string', $tab->getContent());
    }



}