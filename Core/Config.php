<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core;


use OxidEsales\Eshop\Core\Registry;

class Config extends Config_parent
{
    /**
     * @return array
     */
    public function getDebugBarConfigTrustedIps()
    {
        return (array) $this->getShopConfVar('debugbarTrustedIps', null, 'module:fpdebugbar') ?: [];
    }

    /**
     * @return string
     */
    public function getDebugBarConfigTheme()
    {
        return (string) $this->getShopConfVar('debugbarTheme', null, 'module:fpdebugbar') ?: 'default';
    }
}