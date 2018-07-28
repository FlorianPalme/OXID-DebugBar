<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core;


class Language extends Language_parent
{

    /**
     * @var array Speichert die fehlenden Übersetzungen zwischen
     */
    protected $debugbarMissingTranslations = [];

    /**
     * @inheritdoc
     */
    public function translateString($sStringToTranslate, $iLang = null, $blAdminMode = null)
    {
        $translatedString = parent::translateString($sStringToTranslate, $iLang, $blAdminMode);

        if (!$this->isTranslated()) {
            if (!array_key_exists($sStringToTranslate, $this->debugbarMissingTranslations)) {
                $data = [
                    'stringToTranslate' => $sStringToTranslate,
                    'lang' => $iLang,
                    'adminMode' => $blAdminMode,
                    'called' => 1,
                ];

                $this->debugbarMissingTranslations[$sStringToTranslate] = $data;
            } else {
                $this->debugbarMissingTranslations[$sStringToTranslate]['called']++;
            }
        }

        return $translatedString;
    }

    /**
     * @return array
     */
    public function getDebugbarMissingTranslations(): array
    {
        return $this->debugbarMissingTranslations;
    }
}