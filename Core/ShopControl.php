<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core;


use OxidEsales\Eshop\Core\Registry;

class ShopControl extends ShopControl_parent
{

    /**
     * @inheritdoc
     */
    public function start($controllerKey = null, $function = null, $parameters = null, $viewsChain = null)
    {
        parent::start($controllerKey, $function, $parameters, $viewsChain);

        if (!isAdmin() && $controllerKey != 'fpdebugbar_getprofile') {
            /** @var DebugBar $debugBar */
            $debugBar = Registry::get(DebugBar::class);
            $debugBar->write();
        }
    }
}