<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core;


use FlorianPalme\DebugBar\Core\DebugBar\Elements;
use FlorianPalme\DebugBar\Core\DebugBar\Renderer;
use FlorianPalme\DebugBar\Core\DebugBar\Tabber;
use OxidEsales\Eshop\Core\Module\Module;
use OxidEsales\Eshop\Core\Module\ModuleList;

class DebugBar
{
    /**
     * Array aller Elemente der DebugBar
     *
     * @var array
     */
    protected $elements;

    /**
     * Element-Tabber
     *
     * @var Tabber
     */
    protected $tabber;


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

            /** @var Module $module */
            foreach ($moduleList as $module) {
                // TODO: Elemente durch andere Module integrieren
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
        ];
    }


    /**
     * Rendert die Debug-Bar und gibt das Ergebnis zurück
     *
     * @return string
     */
    public function render()
    {
        return $this->getElementsTabber()->render();
    }
}