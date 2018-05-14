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
 * @param \Smarty &$oSmarty smarty object
 *
 * @return string
 */
function smarty_function_fp_debug_bar($params, &$smarty)
{
    /** @var \FlorianPalme\DebugBar\Core\DebugBar $debugBar */
    $debugBar = \OxidEsales\Eshop\Core\Registry::get(\FlorianPalme\DebugBar\Core\DebugBar::class);
    $profile = $debugBar->getCurrentProfile();
    $id = $profile->getProfileId();

    $smarty->assign('debugbarProfileId', $id);
}
