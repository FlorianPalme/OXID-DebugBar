<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core\DebugBar;


use OxidEsales\Eshop\Core\Base;

class Utils extends Base
{
    /**
     * @return string|null
     */
    public function getUserIp()
    {
        return $_SERVER['REMOTE_ADDR'] ?: null;
    }
}