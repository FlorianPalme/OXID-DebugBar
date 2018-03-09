<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core\DebugBar\Tabber;

use FlorianPalme\DebugBar\Core\DebugBar\Tabber;
use FlorianPalme\DebugBar\Tests\TestData;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class TabTest extends UnitTestCase
{
    /**
     *
     */
    public function testGetContent()
    {
        $tab = TestData::Tab();

        $this->assertEquals('Unittests Content', $tab->getContent());
    }

    /**
     *
     */
    public function testGetTitle()
    {
        $tab = TestData::Tab();

        $this->assertEquals('Unittests Title', $tab->getTitle());
    }

    /**
     *
     */
    public function testGetKey()
    {
        $tab = TestData::Tab();

        $this->assertEquals('_unittests_tab', $tab->getKey());
    }
}