<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package DebugBar
 */

namespace FlorianPalme\DebugBar\Core\DebugBar\Elements;

use FlorianPalme\DebugBar\Core\DebugBar\Renderer;
use FlorianPalme\DebugBar\Core\DebugBar\Tabber;
use FlorianPalme\DebugBar\Core\UtilsView;
use GuzzleHttp\Cookie\SetCookie;
use OxidEsales\Eshop\Core\Registry;

class HTTP implements Element
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
        $headerCode = $this->getHTTPResponseCode();
        $title = '<span class="header-' . $headerCode . '">' . $headerCode . '</span>';
        $title .= '@ ' . $this->getController();

        if ($fnc = $this->getFnc()) {
            $title .= ' / ' . $fnc;
        }

        return $title;
    }


    /**
     * Gibt den aktuellen Controller-Namen zurück
     *
     * @return string
     */
    protected function getController()
    {
        return Registry::getConfig()->getTopActiveView()->getClassKey();
    }


    /**
     * Gibt den Fnc-Name zurück
     *
     * @return string
     */
    protected function getFnc()
    {
        return Registry::getConfig()->getTopActiveView()->getFncName();
    }


    /**
     * Gibt den HTTP Response-Code zurück
     *
     * @return int
     */
    protected function getHTTPResponseCode()
    {
        return http_response_code();
    }


    /**
     * Gibt den Content des Elements zurück
     *
     * @return string
     */
    public function getContent()
    {
        /** @var Tabber $tabber */
        $tabber = oxNew(Tabber::class);
        $tabber->setId('httptabber');

        $tabber->addTab($this->getRequestTab());
        $tabber->addTab($this->getResponseTab());
        $tabber->addTab($this->getCookieTab());
        $tabber->addTab($this->getSessionTab());

        return $tabber->render();
    }


    /**
     * Gibt den Request-Tab zurück
     *
     * @return Tabber\Tab
     */
    protected function getRequestTab(): Tabber\Tab
    {
        /** @var Tabber\Tab $tab */
        $tab = oxNew(Tabber\Tab::class);
        $tab->setKey('http_request');
        $tab->setTitle(Registry::getLang()->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST'));

        $contents = $this->getRequestTabGetParameters();
        $contents .= $this->getRequestTabPostParameters();
        $contents .= $this->getRequestTabRequestHeaders();
        $contents .= $this->getRequestTabRequestContent();
        $contents .= $this->getRequestTabServerParameters();

        $tab->setContent($contents);

        return $tab;
    }


    /**
     * Gibt den Response-Tab zurück
     *
     * @return Tabber\Tab
     */
    protected function getResponseTab(): Tabber\Tab
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        /** @var Tabber\Tab $tab */
        $tab = oxNew(Tabber\Tab::class);
        $tab->setKey('http_response');
        $tab->setTitle(Registry::getLang()->translateString('FP_DEBUGBAR_TABS_HTTP_RESPONSE'));

        $lang = Registry::getLang();

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_HTTP_RESPONSE_HEADERS'));

        $responseHeaders = [];

        foreach ($this->getResponseHeaders() as $header) {
            preg_match('/^[^:]+: (.+)/', $header, $matches);

            $responseHeaders[$matches[0]] = $matches[1];
        }

        $html .= $renderer->createParameterTable(
            [
                $lang->translateString('FP_DEBUGBAR_TABS_HTTP_RESPONSE_HEADERS_KEY'),
                $lang->translateString('FP_DEBUGBAR_TABS_HTTP_RESPONSE_HEADERS_VALUE'),
            ],
            $responseHeaders,
            'params'
        );

        $tab->setContent($html);

        return $tab;
    }


    /**
     * Gibt den Session-Tab zurück
     *
     * @return Tabber\Tab
     */
    protected function getSessionTab(): Tabber\Tab
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        /** @var Tabber\Tab $tab */
        $tab = oxNew(Tabber\Tab::class);
        $tab->setKey('http_session');
        $tab->setTitle(Registry::getLang()->translateString('FP_DEBUGBAR_TABS_HTTP_SESSION'));

        $lang = Registry::getLang();

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_HTTP_SESSION_HEADER'));

        if (count($_SESSION)) {
            $html .= $renderer->createParameterTable(
                [
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_SESSION_KEY'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_SESSION_VALUE'),
                ],
                $_SESSION,
                'params'
            );
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_DEBUGBAR_TABS_HTTP_SESSION_NOVALUES')}</div>
HTML;

        }

        $tab->setContent($html);

        return $tab;
    }


    /**
     * Gibt die Response-Header der Anfrage zurück
     *
     * @return array
     */
    protected function getResponseHeaders()
    {
        return headers_list();
    }


    /**
     * Gibt den Cookie-Tab zurück
     *
     * @return Tabber\Tab
     */
    protected function getCookieTab(): Tabber\Tab
    {
        /** @var Tabber\Tab $tab */
        $tab = oxNew(Tabber\Tab::class);
        $tab->setKey('http_cookie');
        $tab->setTitle(Registry::getLang()->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE'));

        $contents = $this->getCookieTabRequest();
        $contents .= $this->getCookieTabResponse();

        $tab->setContent($contents);

        return $tab;
    }


    /**
     * Gibt die Request-Cookies zurück
     *
     * @return string
     */
    protected function getCookieTabRequest()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();
        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_REQUEST'));

        $requestHeader = $this->getAllRequestHeaders();
        if (array_key_exists('Cookie', $requestHeader)) {
            $headerCookies = explode(';', $requestHeader['Cookie']);
            $headerCookies = array_map('trim', $headerCookies);

            $cookies = [];

            foreach ($headerCookies as $cookie) {
                preg_match('/([^=]+)=(.+)/', $cookie, $matches);
                $cookies[$matches[1]] = urldecode($matches[2]);
            }


            $html .= $renderer->createParameterTable(
                [
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_REQUEST_KEY'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_REQUEST_VALUE'),
                ],
                $cookies,
                'params'
            );
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_REQUEST_NOVALUES')}</div>
HTML;

        }

        return $html;
    }


    /**
     * Gibt die Response-Cookies zurück
     *
     * @return string
     */
    protected function getCookieTabResponse()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE'));

        $responseHeaders = $this->getResponseHeaders();
        $cookies = [];

        foreach ($responseHeaders as $header) {
            if (preg_match('/^Set-Cookie/i', $header)) {
                $cookie = SetCookie::fromString($header);
                $cookies[] = $cookie;
            }
        }

        if (count($cookies)) {
            $datas = [];

            /** @var SetCookie $cookie */
            foreach ($cookies as $cookie) {
                $data = [
                    str_replace('Set-Cookie: ', '', $cookie->getName()),
                    $cookie->getValue(),
                    $cookie->getDomain(),
                    $cookie->getPath(),
                    $cookie->getMaxAge(),
                    $cookie->getExpires(),
                    $cookie->getSecure(),
                    $cookie->getDiscard(),
                    $cookie->getHttpOnly(),
                ];

                $datas[] = $data;
            }

            $html .= $renderer->createTable(
                [
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_NAME'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_VALUE'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_DOMAIN'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_PATH'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_MAXAGE'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_EXPIRES'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_SECURE'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_DISCARD'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_HTTPONLY'),
                ],
                $datas,
                'params'
            );
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_DEBUGBAR_TABS_HTTP_COOKIE_RESPONSE_NOVALUES')}</div>
HTML;

        }

        return $html;
    }


    /**
     * Gibt alle Header der Anfrage zurück
     *
     * @return array|false
     */
    protected function getAllRequestHeaders()
    {
        return getallheaders();
    }

    /**
     * Gibt das HTML für die Get-Parameter des Request-Tabs zurück
     *
     * @return string
     */
    protected function getRequestTabGetParameters()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_GETPARAMETERS'));

        if ($getParameter = $_GET) {
            $html .= $renderer->createParameterTable(
                [
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_GETPARAMETERS_KEY'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_GETPARAMETERS_VALUE'),
                ],
                $getParameter,
                'params'
            );
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_GETPARAMETERS_NOVALUES')}</div>
HTML;

        }

        return $html;
    }


    /**
     * Gibt das HTML für die Post-Parameter des Request-Tabs zurück
     *
     * @return string
     */
    protected function getRequestTabPostParameters()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_POSTPARAMETERS'));

        if ($postParameter = $_POST) {
            $html .= $renderer->createParameterTable(
                [
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_POSTPARAMETERS_KEY'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_POSTPARAMETERS_VALUE'),
                ],
                $postParameter,
                'params'
            );
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_POSTPARAMETERS_NOVALUES')}</div>
HTML;

        }

        return $html;
    }


    /**
     * Gibt die Request-Header zurück
     *
     * @return string
     */
    protected function getRequestTabRequestHeaders()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);

        /** @var UtilsView $utilsView */
        $utilsView = Registry::getUtilsView();
        $utilsView->includeFunctions();

        $lang = Registry::getLang();

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_HEADERSPARAMETERS'));

        if ($requestHeaders = $this->getAllRequestHeaders()) {
            $html .= $renderer->createParameterTable(
                [
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_HEADERSPARAMETERS_KEY'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_HEADERSPARAMETERS_VALUE'),
                ],
                $requestHeaders,
                'params'
            );
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_HEADERSPARAMETERS_NOVALUES')}</div>
HTML;

        }

        return $html;
    }


    /**
     * Gibt den Request-Content zurück
     *
     * @return string
     */
    protected function getRequestTabRequestContent()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();

        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_CONTENTPARAMETERS'));

        if ($content = file_get_contents('php://input')) {
            $html .= <<<HTML
<div class="requestcontent">{$content}</div>
HTML;
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_CONTENTPARAMETERS_NOVALUES')}</div>
HTML;

        }

        return $html;
    }


    /**
     * Gibt das HTML für die Get-Parameter des Request-Tabs zurück
     *
     * @return string
     */
    protected function getRequestTabServerParameters()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();
        $html = $renderer->createHeadline($lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_SERVERPARAMETERS'));

        if ($serverParameter = $_SERVER) {
            $html .= $renderer->createParameterTable(
                [
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_SERVERPARAMETERS_KEY'),
                    $lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_SERVERPARAMETERS_VALUE'),
                ],
                $serverParameter,
                'params'
            );
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_DEBUGBAR_TABS_HTTP_REQUEST_SERVERPARAMETERS_NOVALUES')}</div>
HTML;

        }

        return $html;
    }
}