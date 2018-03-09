<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core\DebugBar\Elements;

use FlorianPalme\DebugBar\Core\DebugBar\Elements\Modules;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class ModulesTest extends UnitTestCase
{
    /**
     *
     */
    public function testGetTitle()
    {
        /** @var Modules $tab */
        $tab = oxNew(Modules::class);

        $this->assertInternalType('string', $tab->getTitle());
    }

    /**
     *
     */
    public function testGetContent()
    {
        /** @var Modules $tab */
        $tab = oxNew(Modules::class);

        $this->assertInternalType('string', $tab->getContent());
    }
}