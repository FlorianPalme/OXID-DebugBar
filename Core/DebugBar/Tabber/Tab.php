<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core\DebugBar\Tabber;


class Tab
{
    /**
     * Tab-Content
     *
     * @var string
     */
    protected $content = '';

    /**
     * Tab-Titel
     *
     * @var string
     */
    protected $title = '';

    /**
     * Identifier
     *
     * @var string
     */
    protected $key;


    /**
     * Setzt den Content des Tabs
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }


    /**
     * Gibt den Inhalt des Tabs zurück
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Setzt den Titel des Tabs
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * Gibt den Titel des Tabs zurück
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Setzt den Identifier des Tabs
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }


    /**
     * Gibt den Identifier zurück
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}