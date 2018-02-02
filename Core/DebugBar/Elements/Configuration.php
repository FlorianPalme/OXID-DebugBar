<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core\DebugBar\Elements;


use FlorianPalme\DebugBar\Core\DebugBar\Formatter;
use FlorianPalme\DebugBar\Core\DebugBar\Renderer;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ShopVersion;
use OxidEsales\Facts\Facts;

class Configuration implements Element
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
        return Registry::getLang()->translateString('FP_DEBUGBAR_TABS_CONFIGURATION');
    }

    /**
     * Gibt den Content des Elements zurück
     *
     * @return string
     */
    public function getContent()
    {
        $html = $this->getOxidConfiguration();
        $html .= $this->getPHPConfiguration();
        $html .= $this->getOxidConfigConfiguration();

        return $html;
    }


    /**
     * Gibt die OXID config.inc.php Konfiguration zurück
     *
     * @return string
     */
    protected function getOxidConfigConfiguration()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();
        $config = Registry::getConfig();

        // OXID config.inc.php-Values
        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_OXID_CONFIG'));

        $configParams = [
            'sShopURL' => $config->getConfigParam('sShopURL'),
            'sSSLShopURL' => $config->getConfigParam('sSSLShopURL'),
            'sAdminSSLURL' => $config->getConfigParam('sAdminSSLURL'),
            'sShopDir' => $config->getConfigParam('sShopDir'),
            'sCompileDir' => $config->getConfigParam('sCompileDir'),
            'aAllowedUploadTypes' => $config->getConfigParam('aAllowedUploadTypes'),
            'sOXIDPHP' => $config->getConfigParam('sOXIDPHP'),
            'iDebug' => $config->getConfigParam('iDebug'),
            'blLogChangesInAdmin' => $config->getConfigParam('blLogChangesInAdmin'),
            'sAdminEmail' => $config->getConfigParam('sAdminEmail'),
            'offlineWarningInterval' => $config->getConfigParam('offlineWarningInterval'),
            'blForceSessionStart' => $config->getConfigParam('blForceSessionStart'),
            'blSessionUseCookies' => $config->getConfigParam('blSessionUseCookies'),
            'aCookieDomains' => $config->getConfigParam('aCookieDomains'),
            'aCookiePaths' => $config->getConfigParam('aCookiePaths'),
            'aRobots' => $config->getConfigParam('aRobots'),
            'aRobotsExcept' => $config->getConfigParam('aRobotsExcept'),
            'aTrustedIPs' => $config->getConfigParam('aTrustedIPs'),
            'iBasketReservationCleanPerRequest' => $config->getConfigParam('iBasketReservationCleanPerRequest'),
            'blDebugTemplateBlocks' => $config->getConfigParam('blDebugTemplateBlocks'),
            'blSeoLogging' => $config->getConfigParam('blSeoLogging'),
            'aUserComponentNames' => $config->getConfigParam('aUserComponentNames'),
            'aMultiLangTables' => $config->getConfigParam('aMultiLangTables'),
            'blUseCron' => $config->getConfigParam('blUseCron'),
            'blDoNotDisableModuleOnError' => $config->getConfigParam('blDoNotDisableModuleOnError'),
            'blSkipViewUsage' => $config->getConfigParam('blSkipViewUsage'),
            'iDebugSlowQueryTime' => $config->getConfigParam('iDebugSlowQueryTime'),
            'blUseRightsRoles' => $config->getConfigParam('blUseRightsRoles'),
            'aMultishopArticleFields' => $config->getConfigParam('aMultishopArticleFields'),
            'blShowUpdateViews' => $config->getConfigParam('blShowUpdateViews'),
            'aSlaveHosts' => $config->getConfigParam('aSlaveHosts'),
            'blDelSetupDir' => $config->getConfigParam('blDelSetupDir'),
        ];

        $html .= $renderer->createParameterTable(
            [
                $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_OXID_CONFIG_PARAM'),
                $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_OXID_CONFIG_VALUE'),
            ],
            $configParams,
            'params'
        );

        return $html;
    }


    /**
     * Gibt die OXID-Konfiguration zurück
     *
     * @return string
     */
    protected function getOxidConfiguration()
    {
        $lang = Registry::getLang();
        $config = Registry::getConfig();

        /** @var Facts $facts */
        $facts = Registry::get(Facts::class);

        /** @var ShopVersion $shopVersion */
        $shopVersion = Registry::get(ShopVersion::class);

        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_OXID'));

        // OXID Edition
        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_OXID_EDITION'),
            $facts->getEdition(),
            'oxidedition'
        );

        // OXID Version
        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_OXID_VERSION'),
            $shopVersion->getVersion(),
            'oxidversion'
        );

        // OXID Produktivmodus?
        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_OXID_PRODUCTIVE'),
            $renderer->getIsIcon($config->isProductiveMode()),
            'oxidproductive icon'
        );


        return $html;
    }


    /**
     * Gibt die PHP-Konfiguration zurück
     *
     * @return string
     */
    protected function getPHPConfiguration()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();

        /** @var Formatter $formatter */
        $formatter = Registry::get(Formatter::class);


        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_PHP'));

        // PHP Version
        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_PHP_VERSION'),
            preg_replace('/^(\d+.\d+.\d+)(.*)/', '$1<span>$2</span>', phpversion()),
            'phpversion'
        );

        // BIT Version
        switch(PHP_INT_SIZE) {
            case 8:
                $bitVersion = 64 . ' <span>' . $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_PHP_BITVERSION_BIT') . '</span>';
                break;

            case 4:
                $bitVersion = 32 . ' <span>' . $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_PHP_BITVERSION_BIT') . '</span>';
                break;

            default:
                $bitVersion = $lang->translateString('FP_DEBUGBAR_NA');
        }

        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_PHP_BITVERSION'),
            $bitVersion,
            'bitversion'
        );

        // Zeitzone
        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_PHP_TIMEZONE'),
            date('T'),
            'timezone'
        );

        //$html .= '<div class="clearfix"></div>';

        // OPCache
        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_PHP_OPCACHE'),
            $renderer->getIsIcon($formatter->formatPHPOnOffValue(ini_get('opcache.enable'))),
            'opcache icon'
        );

        // APCu
        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_PHP_APCU'),
            $renderer->getIsIcon(extension_loaded('apcu')),
            'apcu icon'
        );

        // Xdebug
        $html .= $renderer->createBadge(
            $lang->translateString('FP_DEBUGBAR_TABS_CONFIGURATION_PHP_XDEBUG'),
            $renderer->getIsIcon(extension_loaded('xdebug')),
            'xdebug icon'
        );

        return $html;

    }
}