<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core;


use OxidEsales\Eshop\Core\Registry;

class UtilsView extends UtilsView_parent
{
    /**
     * @inheritdoc
     */
    protected function _fillCommonSmartyProperties($oSmarty)
    {
        parent::_fillCommonSmartyProperties($oSmarty);
        array_unshift($oSmarty->plugins_dir, Registry::getConfig()->getModulesDir() . "/fp/debugbar/Core/Smarty/Plugin");
    }


    /**
     * Inkludiert Funktionen, welche ggf. nicht OOB verfügbar sind
     */
    public function includeFunctions()
    {
        require_once(Registry::getConfig()->getModulesDir() . "/fp/debugbar/polyfill.php");
    }
}