<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core\DebugBar\Elements;


use FlorianPalme\DebugBar\Core\DebugBar\Formatter;
use FlorianPalme\DebugBar\Core\DebugBar\Renderer;
use OxidEsales\Eshop\Core\Module\Module;
use OxidEsales\Eshop\Core\Module\ModuleList;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ShopVersion;
use OxidEsales\Facts\Facts;

class Modules implements Element
{

    /**
     * Gibt den Titel des Elements zurück
     *
     * Wird im Tab verwendet
     *
     * @return string
     */
    public function getTitle()
    {
        return Registry::getLang()->translateString('FP_DEBUGBAR_TABS_MODULES');
    }

    /**
     * Gibt den Content des Elements zurück
     *
     * @return string
     */
    public function getContent()
    {
        $html = $this->getActiveModules();

        return $html;
    }


    /**
     * Gibt die OXID-Konfiguration zurück
     *
     * @return string
     */
    protected function getActiveModules()
    {
        $lang = Registry::getLang();
        $config = Registry::getConfig();

        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_MODULES_ACTIVEMODULES'));

        /** @var ModuleList $moduleList */
        $moduleList = oxNew(ModuleList::class);
        $activeModules = $moduleList->getActiveModuleInfo();
        $moduleData = [];

        foreach ($activeModules as $activeModule) {
            /** @var Module $module */
            $module = oxNew(Module::class);
            $module->load($activeModule);

            $author = $module->getInfo('author');
            $url = $module->getInfo('url');

            $moduleData[] = [
                $activeModule,
                $module->getTitle(),
                $module->getInfo('version'),
                "<a href='$url' target='_blank'>$author</a>",
            ];
        }

        $html .= $renderer->createTable(
            [
                $lang->translateString('FP_DEBUGBAR_TABS_MODULES_ACTIVEMODULES_ID'),
                $lang->translateString('FP_DEBUGBAR_TABS_MODULES_ACTIVEMODULES_TITLE'),
                $lang->translateString('FP_DEBUGBAR_TABS_MODULES_ACTIVEMODULES_VERSION'),
                $lang->translateString('FP_DEBUGBAR_TABS_MODULES_ACTIVEMODULES_AUTHOR'),
            ],
            $moduleData,
            'activemodules'
        );

        return $html;
    }
}