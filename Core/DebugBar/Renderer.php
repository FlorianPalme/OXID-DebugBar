<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core\DebugBar;


class Renderer
{
    /**
     * Erstellt eine Tabelle
     *
     * @return string
     */
    public function createTable($columns, $data, $class = '')
    {
        $head = '<tr>';
        foreach ($columns as $column) {
            $head .= "<th>$column</th>";
        }
        $head .= '</tr>';

        $body = '';
        foreach ($data as $values) {
            $body .= '<tr>';

            foreach ($values as $value) {
                $body .= "<td>$value</td>";
            }

            $body .= '</tr>';
        }

        $table = <<<TABLE
<table class="table $class">
<thead>
    $head
</thead>
<tbody>
    $body
</tbody>
</table>
TABLE;

        return $table;
    }


    /**
     * Erstellt eine Parameter-Tabelle
     *
     * @return string
     */
    public function createParameterTable($columns, $data, $class = '')
    {
        $head = '<tr>';
        foreach ($columns as $column) {
            $head .= "<th>$column</th>";
        }
        $head .= '</tr>';

        $body = $this->createParameterTableBody($data);

        $table = <<<TABLE
<table class="table $class">
<thead>
    $head
</thead>
<tbody>
    $body
</tbody>
</table>
TABLE;

        return $table;
    }


    /**
     * Erstellt aus einem Daten-Array den Tabellen-Body für Parameter
     *
     * @param array $data
     * @return string
     */
    public function createParameterTableBody($data)
    {
        $rows = [];

        foreach ($data as $key => $param) {
            $row = "<tr><td>$key</td><td>";

            if (is_string($param) && $newParam = @unserialize($param)) {
                $row .= $this->dumpVariable($newParam);
            } elseif (is_float($param)) {
                $row .= "<span class='type-float'>$param</span>";
            } elseif (is_int($param)) {
                $row .= "<span class='type-int'>$param</span>";
            } elseif (is_array($param)) {
                $row .= $this->dumpVariable($param);
            } elseif (is_string($param)) {
                $row .= '"' . htmlspecialchars($param) . '"';
            } else {
                $row .= $param;
            }

            $row .= "</td></tr>";

            $rows[] = $row;
        }

        return implode('', $rows);
    }


    /**
     * Dumpt einen Wert mit var_export und fängt ggf. HTML-Output (wenn php.ini-Setting html_errors = On)
     *
     * @param mixed $var
     * @return string
     */
    public function dumpVariable($var)
    {
        ob_start();

        var_dump($var);

        $dump = ob_get_clean();

        if (!preg_match('/^<pre/i', $dump)) {
            $dump = "<pre>$dump</pre>";
        }

        return $dump;
    }


    /**
     * Erstellt einen Badge
     *
     * @param string $title
     * @param string $value
     * @param string $class
     * @return string
     */
    public function createBadge($title, $value, $class = '')
    {
        return "<div class='badge $class'><span class='value'>{$value}</span><span class='title'>$title</span></div>";
    }


    /**
     * Erstellt eine Headline
     *
     * @param string $headline
     * @return string
     */
    public function createHeadline($headline)
    {
        return "<div class='headline'>$headline</div>";
    }


    /**
     * Gibt ein Done oder Clear Icon zurück
     *
     * @param bool $is
     * @return string
     */
    public function getIsIcon($is)
    {
        return $is ? '<i class="material-icons done">done</i>' : '<i class="material-icons clear">clear</i>';
    }
}