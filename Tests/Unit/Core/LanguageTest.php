<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core;


use FlorianPalme\DebugBar\Core\DebugBar;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class LanguageTest extends UnitTestCase
{
    /**
     *
     */
    public function testGetDebugbarMissingTranslations()
    {
        /** @var \FlorianPalme\DebugBar\Core\Language $lang */
        $lang = Registry::getLang();

        // Translation-Error triggern
        $lang->translateString('UNITTEST_NOT_TRANSLATED_STRING_LANGUAGETEST');

        $this->assertInternalType('array', $lang->getDebugbarMissingTranslations());
        $this->assertEquals(1, count($lang->getDebugbarMissingTranslations()));
    }
}