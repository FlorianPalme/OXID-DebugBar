<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

/**
 * Smarty function
 * -------------------------------------------------------------
 * Purpose: Gibt die DebugBar aus
 * add [{fp_debug_var}]
 * -------------------------------------------------------------
 *
 * @param array  $aParams  parameters to process
 * @param smarty &$oSmarty smarty object
 *
 * @return string
 */
function smarty_function_fp_debug_bar_inccss($params, &$smarty)
{
    /** @var \OxidEsales\Eshop\Core\ViewConfig $viewConf */
    $viewConf = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\ViewConfig::class);

    /** @var \OxidEsales\Eshop\Core\Config $config */
    $config = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\Config::class);
    $theme = $config->getShopConfVar('debugbarTheme', null, 'module:fpdebugbar');

    $styles = [
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        $viewConf->getModuleUrl('fpdebugbar','out/src/css/theme.' . $theme . '.css')
    ];

    /** @var \OxidEsales\Eshop\Core\ViewHelper\StyleRegistrator $registrator */
    $registrator = oxNew(\OxidEsales\Eshop\Core\ViewHelper\StyleRegistrator::class);

    foreach ($styles as $style) {
        $registrator->addFile($style, '', false);
    }
}
