<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core;


use FlorianPalme\DebugBar\Core\DebugBar;
use OxidEsales\Eshop\Core\UtilsView;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class DebugBarTest extends UnitTestCase
{
    /**
     *
     */
    public function testGetElementsAssertArray()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);
        $elements = $debugbar->getElements();

        $this->assertInternalType('array', $elements);
    }

    /**
     * @depends testGetElementsAssertArray
     */
    public function testGetElementsCheckElementsCount()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);
        $elements = $debugbar->getElements();

        $this->assertGreaterThanOrEqual(4, count($elements));
    }

    /**
     * @depends testGetElementsAssertArray
     */
    public function testGetElementsWithModuleElements()
    {
        $this->markTestIncomplete();
    }

    /**
     *
     * @throws \ReflectionException
     */
    public function testGetDefaultElementsAssertArray()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);

        $elements = $this->callMethod($debugbar, 'getDefaultElements');

        $this->assertInternalType('array', $elements);
    }

    /**
     * @depends testGetDefaultElementsAssertArray
     * @throws \ReflectionException
     */
    public function testGetDefaultElementsCheckElementsCount()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);

        $elements = $this->callMethod($debugbar, 'getDefaultElements');

        $this->assertEquals(4, count($elements));
    }

    /**
     * @depends testGetDefaultElementsAssertArray
     * @throws \ReflectionException
     */
    public function testGetDefaultElementsCheckHTTPElementExists()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);

        $elements = $this->callMethod($debugbar, 'getDefaultElements');

        $this->assertArrayHasKey('http', $elements);
    }

    /**
     * @depends testGetDefaultElementsCheckHTTPElementExists
     * @throws \ReflectionException
     */
    public function testGetDefaultElementsCheckHTTPElement()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);

        $elements = $this->callMethod($debugbar, 'getDefaultElements');
        $httpElement = $elements['http'];

        $this->assertEquals(\FlorianPalme\DebugBar\Core\DebugBar\Elements\HTTP::class, $httpElement);
    }

    /**
     *
     */
    public function testGetElementsTabberType()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);

        $this->assertInstanceOf(DebugBar\Tabber::class, $debugbar->getElementsTabber());
    }

    /**
     * @depends testGetElementsTabberType
     */
    public function testGetElementsTabberTabsExists()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);
        $tabber = $debugbar->getElementsTabber();

        $this->assertGreaterThan(0, $tabber->getTabs());
    }

    /**
     * @depends testGetElementsTabberTabsExists
     */
    public function testGetElementsTabberFirstTabType()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);
        $tabber = $debugbar->getElementsTabber();
        $firstTab = array_shift($tabber->getTabs());

        $this->assertInstanceOf(DebugBar\Tabber\Tab::class, $firstTab);
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetTrustedIps()
    {
        /** @var DebugBar $debugbar */
        $debugbar = oxNew(DebugBar::class);
        $ips = $this->callMethod($debugbar, 'getTrustedIps');

        $this->assertInternalType('array', $ips);
    }

    /**
     * @depends testGetTrustedIps
     */
    public function testRenderTypeWithoutIPLimitations()
    {
        $debugbar = $this->getMock(DebugBar::class, ['getTrustedIps']);
        $debugbar
            ->expects($this->once())
            ->method('getTrustedIps')
            ->will($this->returnValue([]));

        $render = $debugbar->render();

        $this->assertInternalType('string', $render);
        $this->assertNotSame('', $render);
    }


    /**
     * @depends testGetTrustedIps
     */
    public function testRenderTypeWithIPLimitation()
    {
        $debugbar = $this->getMock(DebugBar::class, ['getTrustedIps']);
        $debugbar
            ->expects($this->once())
            ->method('getTrustedIps')
            ->will($this->returnValue([
                '127.0.0.1',
            ]));

        $render = $debugbar->render();

        $this->assertInternalType('string', $render);
        $this->assertSame('', $render);
    }
}