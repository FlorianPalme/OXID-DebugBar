<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core;


use OxidEsales\Eshop\Core\ConfigFile;
use OxidEsales\Eshop\Core\FileCache;
use OxidEsales\Eshop\Core\Registry;

class Events
{
    /**
     * Bei Modul-Aktivierung
     */
    public static function onActivate()
    {
        self::clearCache();
        self::reloadSmarty();
    }


    /**
     * Bei Modul-Deaktivierung
     */
    public static function onDeactivate()
    {
        self::clearCache();
        self::reloadSmarty();
    }


    /**
     * Löscht den TMP-Ordner sowie den Smarty-Ordner
     */
    protected static function clearCache()
    {
        /** @var FileCache $fileCache */
        $fileCache = oxNew(FileCache::class);
        $fileCache::clearCache();

        /** Smarty leeren */
        $tempDirectory = Registry::get(ConfigFile::class)->getVar("sCompileDir");
        $mask = $tempDirectory . "/smarty/*.php";
        $files = glob($mask);
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }


    /**
     * Lädt Smarty neu
     */
    protected static function reloadSmarty()
    {
        Registry::getUtilsView()->getSmarty(true);
    }
}