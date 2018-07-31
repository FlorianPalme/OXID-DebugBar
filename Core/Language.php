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
                    'backtraces' => [],
                ];

                $this->debugbarMissingTranslations[$sStringToTranslate] = $data;
            } else {
                $this->debugbarMissingTranslations[$sStringToTranslate]['called']++;
            }

            ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
            $backtrace = ob_get_clean();

            $this->debugbarMissingTranslations[$sStringToTranslate]['backtraces'][] = $backtrace;
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