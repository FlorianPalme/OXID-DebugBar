<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Application\Controller;


use FlorianPalme\DebugBar\Core\DebugBar\Profile;
use FlorianPalme\DebugBar\Core\DebugBar\ProfileRotate;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Exception\SystemComponentException;
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

                $profileHtml = $this->addProfileSelect($profile->getDebugBarHTML(), $profileId);

                $json = [
                    'styles' => $styles,
                    'profile' => $profileHtml,
                ];

                $utils = Registry::getUtils();
                $utils->setHeader('Content-Type: application/json');
                $utils->showMessageAndExit(json_encode($json));
            }
        }

        Registry::getUtils()->handlePageNotFoundError();
    }


    /**
     * Fügt das DropDown zur Auswahl des Profils ein
     *
     * @param string $profileHtml
     * @param string $currentProfileId
     * @return string
     */
    protected function addProfileSelect($profileHtml, $currentProfileId)
    {
        try {
            /** @var \FlorianPalme\DebugBar\Core\Config $config */
            $config = Registry::getConfig();

            $select = '<li class="profile_select"><select name="profile_select">';

            $profileFiles = (oxNew(ProfileRotate::class))
                ->setPath($config->getDeubgBarProfileDir())
                ->setRegex('/(profile_\d*_.*)\.html/')
                ->getProfiles();


            foreach ($profileFiles as $time => $profileFile) {
                if (preg_match('/(profile_\d*_.*)\.html/', $profileFile, $profileMatches)) {
                    $profileId = $profileMatches[1];

                    $select .= '<option value="' . $profileId . '"';

                    if ($profileId === $currentProfileId) {
                        $select .= ' selected="selected"';
                    }

                    $select .= '>' . date('d.m.Y H:i:s', $time) . ' (';
                    $select .= $profileId . ')</option>';
                }
            }

            $select .= '</select></li>';

            return str_replace('%CHOOSE_PROFILE%', $select, $profileHtml);
        } catch (SystemComponentException $e) {
            return $profileHtml;
        }
    }
}