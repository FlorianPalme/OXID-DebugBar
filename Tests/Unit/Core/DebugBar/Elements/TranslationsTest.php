<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core\DebugBar\Elements;

use FlorianPalme\DebugBar\Core\DebugBar\Elements\Performance;
use FlorianPalme\DebugBar\Core\DebugBar\Elements\Translations;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class TranslationsTest extends UnitTestCase
{
    /**
     *
     */
    public function testGetTitle()
    {
        /** @var Translations $tab */
        $tab = oxNew(Translations::class);

        $this->assertInternalType('string', $tab->getTitle());
    }

    /**
     *
     */
    public function testGetContent()
    {
        /** @var Performance $tab */
        $tab = oxNew(Performance::class);

        $this->assertInternalType('string', $tab->getContent());
    }
}