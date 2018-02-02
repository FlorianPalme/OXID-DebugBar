<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core\DebugBar\Elements;


interface Element
{
    /**
     * Gibt den Titel des Elements zurück
     *
     * Wird im Tab verwendet
     *
     * @return string
     */
    public function getTitle();

    /**
     * Gibt den Content des Elements zurück
     *
     * @return string
     */
    public function getContent();
}