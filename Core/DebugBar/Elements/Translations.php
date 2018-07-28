<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core\DebugBar\Elements;


use FlorianPalme\DebugBar\Core\DebugBar\Formatter;
use FlorianPalme\DebugBar\Core\DebugBar\Renderer;
use FlorianPalme\DebugBar\Core\Language;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ShopVersion;

class Translations implements Element
{

    /**
     * Gibt den Titel des Elements zurück
     *
     * Wird im Tab verwendet
     *
     * @return string
     */
    public function getTitle()
    {
        return Registry::getLang()->translateString('FP_DEBUGBAR_TABS_TRANSLATIONS');
    }

    /**
     * Gibt den Content des Elements zurück
     *
     * @return string
     */
    public function getContent()
    {
        $html = $this->getQuickOverview();

        return $html;
    }

    /**
     * Gibt die OXID-Konfiguration zurück
     *
     * @return string
     */
    protected function getQuickOverview()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        /** @var Language $lang */
        $lang = Registry::getLang();

        // Fehlende Übersetzungen
        $missingTranslations = count($lang->getDebugbarMissingTranslations());

        if ($missingTranslations <> 1) {
            $missingTranslationsText = $lang->translateString('FP_DEBUGBAR_TABS_TRANSLATIONS_MISSING_P');
        } else {
            $missingTranslationsText = $lang->translateString('FP_DEBUGBAR_TABS_TRANSLATIONS_MISSING');
        }

        $html = $renderer->createBadge(
            $missingTranslationsText,
            $missingTranslations,
            'missingtranslations'
        );

        if ($missingTranslations) {
            $html .= $renderer->createHeadline(
                $lang->translateString('FP_DEBUGBAR_TABS_TRANSLATIONS_MISSING_HEADLINE')
            );

            $missingTranslationsArray = $lang->getDebugbarMissingTranslations();
            $missingTranslationsArray = array_map([$this, 'modifyTranslationsArrayForDisplay'], $missingTranslationsArray);

            $html .= $renderer->createTable([
                $lang->translateString('FP_DEBUGBAR_TABS_TRANSLATIONS_MISSING_TABLE_STRING'),
                $lang->translateString('FP_DEBUGBAR_TABS_TRANSLATIONS_MISSING_TABLE_LANG'),
                $lang->translateString('FP_DEBUGBAR_TABS_TRANSLATIONS_MISSING_TABLE_ADMINMODE'),
                $lang->translateString('FP_DEBUGBAR_TABS_TRANSLATIONS_MISSING_TABLE_CALLED'),
            ], $missingTranslationsArray);
        }

        return $html;
    }

    /**
     * Modifiziert das Array zur Anzeige in einer Tabelle
     *
     * @param array $lang
     * @return array
     */
    protected function modifyTranslationsArrayForDisplay($lang): array
    {
        if ($lang['adminMode']) {
            $lang['adminMode'] = Registry::getLang()->translateString('FP_DEBUGBAR_YES');
        } else {
            $lang['adminMode'] = Registry::getLang()->translateString('FP_DEBUGBAR_NO');
        }

        return $lang;
    }
}