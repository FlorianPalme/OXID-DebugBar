<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core;


use FlorianPalme\DebugBar\Core\DebugBar\Elements;
use FlorianPalme\DebugBar\Core\DebugBar\Profile;
use FlorianPalme\DebugBar\Core\DebugBar\ProfileRotate;
use FlorianPalme\DebugBar\Core\DebugBar\Renderer;
use FlorianPalme\DebugBar\Core\DebugBar\Tabber;
use FlorianPalme\DebugBar\Core\DebugBar\Utils;
use OxidEsales\Eshop\Core\Module\Module;
use OxidEsales\Eshop\Core\Module\ModuleList;
use OxidEsales\Eshop\Core\Registry;

class DebugBar
{
    /** @var array Array aller Elemente der DebugBar */
    protected $elements;

    /** @var Tabber Element-Tabber */
    protected $tabber;

    /** @var Profile Aktuelles Profil-Objekt */
    protected $profile;

    /**
     * Gibt die Elemente der Debugbar zurück
     * @return array
     */
    public function getElements()
    {
        if ($this->elements === null) {
            $this->elements = [];

            $elements = $this->getDefaultElements();

            /** @var ModuleList $moduleList */
            $moduleList = oxNew(ModuleList::class);

            foreach ($moduleList->getActiveModuleInfo() as $id => $path) {
                /** @var Module $module */
                $module = oxNew(Module::class);
                $module->load($id);

                if ($moduleElements = $module->getInfo('debugbar')) {
                    if (!is_array($moduleElements)) continue;

                    foreach ($moduleElements as $elementId => $elementClass) {
                        if (class_exists($elementClass)) {
                            $elements[$elementId] = $elementClass;
                        }
                    }
                }
            }

            // Element-Klassen laden
            foreach ($elements as $key => $element) {
                $this->elements[$key] = oxNew($element);
            }
        }

        return $this->elements;
    }


    /**
     * Gibt den Elements-Tabber zurück
     *
     * @return Tabber
     */
    public function getElementsTabber(): Tabber
    {
        if ($this->tabber === null) {
            /** @var Tabber $tabber */
            $tabber = oxNew(Tabber::class);
            $tabber->setId('oxiddebugbar');
            $tabber->setIsContentTabber(false);

            /** @var Elements\Element $element */
            foreach ($this->getElements() as $key => $element) {
                /** @var Tabber\Tab $tab */
                $tab = oxNew(Tabber\Tab::class);
                $tab->setContent($element->getContent());
                $tab->setTitle($element->getTitle());
                $tab->setKey($key);

                $tabber->addTab($tab);
            }

            $this->tabber = $tabber;
        }

        return $this->tabber;
    }


    /**
     * Gibt eine Liste der Standard DebugBar-Elemente zurück
     *
     * @return array
     */
    protected function getDefaultElements()
    {
        return [
            'http' => Elements\HTTP::class,
            'performance' => Elements\Performance::class,
            'configuration' => Elements\Configuration::class,
            'modules' => Elements\Modules::class,
            'translations' => Elements\Translations::class,
        ];
    }


    /**
     * Rendert die Debug-Bar und gibt das Ergebnis zurück
     *
     * @return string
     */
    public function render()
    {
        // Prüfen, ob's gerendet werden darf
        /** @var Utils $utils */
        $utils = oxNew(Utils::class);
        $trustedIps = $this->getTrustedIps();

        if (!count($trustedIps) || in_array($utils->getUserIp(), $trustedIps)) {
            return $this->getElementsTabber()->render();
        }

        return '';
    }

    /**
     * @return array
     */
    protected function getTrustedIps()
    {
        /** @var Config $config */
        $config = Registry::getConfig();
        return $config->getDebugBarConfigTrustedIps();
    }


    /**
     * Schreibt die gerenderte DebugBar in ein HTML-File
     */
    public function write()
    {
        try {
            /** @var Config $config */
            $config = Registry::getConfig();

            $profile = $this->getCurrentProfile();
            $profile->saveDebugBarHTML($this->render());

            /** @var ProfileRotate $rotate */
            $rotate = oxNew(ProfileRotate::class);
            $rotate
                ->setPath($config->getDeubgBarProfileDir())
                ->setRegex('/(profile_\d*_.*)\.html/')
                ->setMaxFiles($config->getDebugBarConfigMaxProfiles())
                ->rotate();
        } catch (\Exception $e) {
        }
    }

    /**
     * Gibt das aktuelle Profile-Objekt zurück
     *
     * @return Profile
     */
    public function getCurrentProfile(): Profile
    {
       if ($this->profile === null) {
           $this->profile = oxNew(Profile::class);
       }

       return $this->profile;
    }
}