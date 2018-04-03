<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Application\Controller;


use FlorianPalme\DebugBar\Core\DebugBar\Profile;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\ViewConfig;

class GetProfileController extends FrontendController
{

    /**
     * Rendert ein Profil
     *
     * @return null|void
     * @throws \Exception
     */
    public function render()
    {
        /** @var Request $request */
        $request = Registry::get(Request::class);
        $profileId = $request->getRequestParameter('profileid', false);

        if ($profileId) {
            /** @var Profile $profile */
            $profile = oxNew(Profile::class);
            $profile->setProfileId($profileId);

            if ($profile->exists()) {
                /**
                 * Styles auslesen und an das Json übergeben
                 */
                /** @var ViewConfig $viewConfig */
                $viewConfig = Registry::get(ViewConfig::class);

                /** @var \FlorianPalme\DebugBar\Core\Config $config */
                $config = Registry::get(Config::class);
                $theme = $config->getDebugBarConfigTheme();

                $styles = [
                    'https://fonts.googleapis.com/icon?family=Material+Icons',
                    $viewConfig->getModuleUrl('fpdebugbar','out/src/css/theme.' . $theme . '.css')
                ];

                $json = [
                    'styles' => $styles,
                    'profile' => $profile->getDebugBarHTML(),
                ];

                $utils = Registry::getUtils();
                $utils->setHeader('Content-Type: application/json');
                $utils->showMessageAndExit(json_encode($json));
            }
        }

        Registry::getUtils()->handlePageNotFoundError();
    }
}