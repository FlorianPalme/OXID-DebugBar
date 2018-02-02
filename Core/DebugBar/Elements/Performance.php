<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core\DebugBar\Elements;


use FlorianPalme\DebugBar\Core\DebugBar\Formatter;
use FlorianPalme\DebugBar\Core\DebugBar\Renderer;
use OxidEsales\Eshop\Core\Registry;

class Performance implements Element
{
    /** @var int Die höchste Execution Time */
    protected $maxExecutionTime = 0;

    /** @var int Maximale Anzahl an Ausführungen */
    protected $maxExecutions = 0;

    /**
     * Gibt den Titel des Elements zurück
     *
     * Wird im Tab verwendet
     *
     * @return string
     */
    public function getTitle()
    {
        return Registry::getLang()->translateString('FP_DEBUGBAR_TABS_PERFORMANCE');
    }

    /**
     * Gibt den Content des Elements zurück
     *
     * @return string
     */
    public function getContent()
    {
        $lang = Registry::getLang();
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_PERFORMANCE'));

        /** Execution Time */
        $time = (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000;
        $time = number_Format($time, 0, ',', '');

        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_PERFORMANCE_EXECUTIONTIME'),
            $time,
            'executiontime'
        );

        /** Peak Usage */
        /** @var Formatter $formatter */
        $formatter = Registry::get(Formatter::class);
        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_PERFORMANCE_MEMORYPEAK'),
            $formatter->formatBytes($this->getMemoryPeakUsage()),
            'peakusage'
        );

        $html .= '<div class="clearfix"></div>';
        $html .= $this->getProfiler();

        return $html;
    }


    /**
     * Gibt den Profiler zurück
     *
     * @return string
     */
    protected function getProfiler()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();

        $profiles = $this->getProfiles();
        $executionTimeOnePercent = 100 / $this->maxExecutionTime;
        $executionsOnePercent = 100 / $this->maxExecutions;
        $msText = $lang->translateString('FP_DEBUGBAR_TABS_PERFORMANCE_PROFILER_TIME');
        $executionsText = $lang->translateString('FP_DEBUGBAR_TABS_PERFORMANCE_PROFILER_EXECUTIONS');


        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_PERFORMANCE_PROFILER'));
        $html .= "<div class='profiler'>";

        foreach ($profiles as $profile) {
            $executionTimeWidth = $executionTimeOnePercent * $profile->time;
            $exeuctionsWidth = $executionsOnePercent * $profile->executions;

            $html .= <<<PROFILE
<div class="profile">
    <span class="title">$profile->id</span>
    <div class="bars">
        <span class="time" style="width:$executionTimeWidth%;">$profile->time $msText</span>
        <span class="executions" style="width:$exeuctionsWidth%;">$profile->executions $executionsText</span>
    </div>
</div>
<div class="clearfix"></div>
PROFILE;

        }

        $html .= "</div>";

        return $html;
    }


    /**
     * Gibt die Profil-Daten zurück
     *
     * @return array
     */
    protected function getProfiles()
    {
        global $aProfileTimes;
        global $aExecutionCounts;

        $profiles = [];
        $maxTime = 0;
        $maxExecutions = 0;

        foreach ($aProfileTimes as $key => $profileTime) {
            $profile = new \stdClass();
            $profile->id = $key;
            $profile->time = $profileTime * 1000;
            $profile->executions = $aExecutionCounts[$key];

            $profiles[] = $profile;

            if ($maxTime < $profile->time) $maxTime = $profile->time;
            if ($maxExecutions < $profile->executions) $maxExecutions = $profile->executions;
        }

        $this->maxExecutionTime = $maxTime;
        $this->maxExecutions = $maxExecutions;

        return $profiles;
    }


    /**
     * Gibt den maximal benötigten Speicher zurück
     *
     * @return int
     */
    protected function getMemoryPeakUsage()
    {
        return memory_get_peak_usage();
    }
}