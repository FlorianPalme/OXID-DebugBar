<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core\DebugBar;


use FlorianPalme\DebugBar\Core\DebugBar\Tabber\Content;
use FlorianPalme\DebugBar\Core\DebugBar\Tabber\Tab;

class Tabber
{
    /**
     * Tabs für des Tabbers
     *
     * @var array
     */
    protected $tabs = [];


    /**
     * ID des Tabbers
     *
     * @var string
     */
    protected $id;


    /**
     * Ist der Tabber ein Content-Tabber?
     *
     * @var bool
     */
    protected $isContentTabber = true;


    /**
     * Setzt die Tabber-ID
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * Gibt die Tabber-ID zurück
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Fügt ein Tab-Element hinzu
     *
     * @param Tab $tab
     */
    public function addTab(Tab $tab)
    {
        $this->tabs[$tab->getKey()] = $tab;
    }


    /**
     * Gibt alle Tabs zurück
     *
     * @return array
     */
    public function getTabs()
    {
        return $this->tabs;
    }


    /**
     * Rendert den Tabber als HTML und gibt ihn zurück
     *
     * @return string
     */
    public function render()
    {
        $tabber = '<div class="tabber"';

        if ($this->getId() !== null) {
            $tabber .= ' id="' . $this->getId() . '"';
        }

        $tabber .= '>';

        $tabber .= '<ul class="tabs">' . $this->renderTabs() . '</ul>';
        $tabber .= '<div class="clearfix"></div>';
        $tabber .= '<div class="contents">' . $this->renderContents() . '</div>';

        $tabber .= '</div>';

        return $tabber;
    }


    /**
     * Rendert alle Tabs als HTML
     *
     * @return string
     */
    protected function renderTabs()
    {
        $tabs = [];
        $isFirstTab = true;

        /**
         * @var Tab $tab
         */
        foreach ($this->tabs as $tab) {
            $tabs[] = $this->renderTab($tab, $isFirstTab);
            $isFirstTab = false;
        }

        if (!$this->isContentTabber) {
            $tabs[] = "<li class='close'><i class='material-icons'>clear</i></li>";
            $tabs[] = "%CHOOSE_PROFILE%";
        }

        return implode('', $tabs);
    }


    /**
     * Rendert einen einzelnen Tab
     *
     * @param Tab $tab
     * @param bool $isFirstTab
     * @return string
     */
    protected function renderTab(Tab $tab, $isFirstTab = false)
    {
        $class = $tab->getKey();

        if ($isFirstTab && $this->isContentTabber) $class .= ' active';

        return "<li class='{$class}' data-tab='{$tab->getKey()}'>{$tab->getTitle()}</li>";
    }


    /**
     * Rendert alle Contents als HTML
     *
     * @return string
     */
    protected function renderContents()
    {
        $contents = [];
        $isFirstContent = true;

        /**
         * @var Tab $tab
         */
        foreach ($this->tabs as $tab) {
            $contents[] = $this->renderContent($tab, $isFirstContent);
            $isFirstContent = false;
        }

        return implode('', $contents);
    }


    /**
     * Rendert einen einzelnen Tab-Content
     *
     * @param Tab $tab
     * @param bool $isFirstContent
     * @return string
     */
    protected function renderContent(Tab $tab, $isFirstContent = false)
    {
        $class = 'content ' . $tab->getKey();

        if ($isFirstContent && $this->isContentTabber) $class .= ' active';

        return "<div class='{$class}' data-content='{$tab->getKey()}'>{$tab->getContent()}</div>";
    }


    /**
     * Setzt den Wert für Content-Tabber
     *
     * @param $isContentTabber
     */
    public function setIsContentTabber($isContentTabber)
    {
        $this->isContentTabber = $isContentTabber;
    }
}