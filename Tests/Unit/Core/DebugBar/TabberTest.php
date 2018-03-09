<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core\DebugBar;

use FlorianPalme\DebugBar\Core\DebugBar\Tabber;
use FlorianPalme\DebugBar\Tests\TestData;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class TabberTest extends UnitTestCase
{
    /**
     *
     */
    public function testTabberId()
    {
        $tabber = TestData::Tabber();

        $this->assertEquals('_unittests_tabber', $tabber->getId());
    }

    /**
     *
     */
    public function testGetTabs()
    {
        $tabber = TestData::Tabber();

        $this->assertCount(3, $tabber->getTabs());
    }

    /**
     * @depends testGetTabs
     */
    public function testTabsType()
    {
        $tabber = TestData::Tabber();
        $tab = array_shift($tabber->getTabs());

        $this->assertInstanceOf(Tabber\Tab::class, $tab);
    }

    /**
     * @throws \ReflectionException
     */
    public function testRenderTab()
    {
        $tabber = oxNew(Tabber::class);
        $tab = TestData::Tab();

        $return = $this->callMethod($tabber, 'renderTab', [$tab]);

        $this->assertRegExp('/^<li.*data-tab=[\'|"].*[\'|"]>.*<\/li>$/', $return);
    }

    /**
     * @depends testRenderTab
     * @depends testTabsType
     *
     * @throws \ReflectionException
     */
    public function testRenderTabs()
    {
        $tabber = TestData::Tabber();
        $return = $this->callMethod($tabber, 'renderTabs');

        $this->assertStringEndsWith('</li>', $return);
    }

    /**
     * @throws \ReflectionException
     */
    public function testRenderContent()
    {
        $tabber = oxNew(Tabber::class);
        $tab = TestData::Tab();

        $return = $this->callMethod($tabber, 'renderContent', [$tab]);

        $this->assertRegExp('/^<div.*data-content=[\'|"]_unittests_tab[\'|"]>.*<\/div>$/', $return);
    }

    /**
     * @depends testRenderTab
     * @depends testTabsType
     *
     * @throws \ReflectionException
     */
    public function testRenderContents()
    {
        $tabber = TestData::Tabber();
        $return = $this->callMethod($tabber, 'renderContents');

        $this->assertStringEndsWith('</div>', $return);
    }

    /**
     * @depends testRenderTabs
     * @depends testRenderContents
     * @depends testTabsType
     */
    public function testRender()
    {
        $tabber = TestData::Tabber();
        $return = $tabber->render();

        $this->assertStringStartsWith('<div class="tabber"', $return);
    }
}