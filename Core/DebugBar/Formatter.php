<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core\DebugBar;


class Formatter
{
    /**
     * Formatiert Bytes in ein Menschen-Lesbares Format
     *
     * @param int $size
     * @param int $precision
     *
     * @return string
     */
    public function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');

        return round(pow(1024, $base - floor($base)), $precision) .' <span>'. $suffixes[(int) floor($base)] . '</span>';
    }


    /**
     * Gibt einen boolschen Wert für einen PHP-Wert zurück
     *
     * @param mixed $value
     * @return bool
     */
    public function formatPHPOnOffValue($value)
    {
        if ($value === 'On' || $value === 'on') {
            return true;
        } elseif ($value === 'Off' || $value === 'off') {
            return false;
        }

        return (bool) $value;
    }
}