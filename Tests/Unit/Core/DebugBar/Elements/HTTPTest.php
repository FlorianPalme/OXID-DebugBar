<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core\DebugBar\Elements;

use FlorianPalme\DebugBar\Core\DebugBar\Elements\HTTP;
use FlorianPalme\DebugBar\Core\DebugBar\Tabber\Tab;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class HTTPTest extends UnitTestCase
{
    /**
     *
     */
    public function testGetTitle()
    {
        /** @var HTTP $tab */
        $tab = oxNew(HTTP::class);

        $this->assertInternalType('string', $tab->getTitle());
    }

    /**
     * @depends testGetTitle
     */
    public function testGetTitleWithFnc()
    {
        $tab = $this->getMock(HTTP::class, ['getFnc']);
        $tab
            ->expects($this->once())
            ->method('getFnc')
            ->will($this->returnValue([
                'alist',
            ]));

        $this->assertInternalType('string', $tab->getTitle());
    }


    /**
     *
     * @throws \ReflectionException
     */
    public function testGetResponseHeaders()
    {
        $tab = $this->getMock(HTTP::class, ['getResponseHeaders']);
        $tab
            ->expects($this->once())
            ->method('getResponseHeaders')
            ->will($this->returnValue([
                'Set-Cookie: foo=bar',
                'X-Sample-Test: foo',
            ]));

        /** @var Tab $responseTab */
        $responseTab = $this->callMethod($tab, 'getResponseTab');

        $this->assertInstanceOf(Tab::class, $responseTab);
        $this->assertRegExp('/Set-Cookie: foo=bar/', $responseTab->getContent());
    }

    /**
     *
     * @throws \ReflectionException
     */
    public function testGetCookieTabResponse()
    {
        $tab = $this->getMock(HTTP::class, ['getResponseHeaders']);
        $tab
            ->expects($this->once())
            ->method('getResponseHeaders')
            ->will($this->returnValue([
                'Set-Cookie: foo=bar',
                'X-Sample-Test: foo',
            ]));

        $response = $this->callMethod($tab, 'getCookieTabResponse');

        $this->assertInternalType('string', $response);
    }

    /**
     *
     * @throws \ReflectionException
     */
    public function testGetCookieTabRequest()
    {
        $tab = $this->getMock(HTTP::class, ['getAllRequestHeaders']);
        $tab
            ->expects($this->once())
            ->method('getAllRequestHeaders')
            ->will($this->returnValue([
                'Cookie' => 'oxidadminlanguage=de; oxidadminprofile=0%40Standard%4010%401; admin_sid=49d93kd93; sid_key=oxid; language=0; oxidadminhistory=%7Cadmin_start%7Cnavigation%7Cmodule; sid=3948dj39; foo=bar',
            ]));

        $response = $this->callMethod($tab, 'getCookieTabRequest');

        $this->assertInternalType('string', $response);
    }


    /**
     *
     * @throws \ReflectionException
     */
    public function testGetRequestTabGetParameters()
    {
        /** @var HTTP $tab */
        $tab = oxNew(HTTP::class);

        $get = $_GET;
        $_GET = ['foo' => 'bar'];

        $response = $this->callMethod($tab, 'getRequestTabGetParameters');

        $_GET = $get;

        $this->assertInternalType('string', $response);
    }


    /**
     *
     * @throws \ReflectionException
     */
    public function testGetRequestTabPostParameters()
    {
        /** @var HTTP $tab */
        $tab = oxNew(HTTP::class);

        $post = $_POST;
        $_POST = ['foo' => 'bar'];

        $response = $this->callMethod($tab, 'getRequestTabPostParameters');

        $_POST = $post;

        $this->assertInternalType('string', $response);
    }


    /**
     *
     * @throws \ReflectionException
     */
    public function testGetRequestTabServerParameters()
    {
        /** @var HTTP $tab */
        $tab = oxNew(HTTP::class);

        $server = $_SERVER;
        $_SERVER = [];

        $response = $this->callMethod($tab, 'getRequestTabServerParameters');

        $_SERVER = $server;

        $this->assertInternalType('string', $response);
    }


    /**
     *
     * @throws \ReflectionException
     */
    public function testGetRequestTabRequestHeaders()
    {
        $tab = $this->getMock(HTTP::class, ['getAllRequestHeaders']);
        $tab
            ->expects($this->once())
            ->method('getAllRequestHeaders')
            ->will($this->returnValue([
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Connection' => 'keep-alive',
                'Cookie' => 'oxidadminlanguage=de; oxidadminprofile=0%40Standard%4010%401; admin_sid=49d93kd93; sid_key=oxid; language=0; oxidadminhistory=%7Cadmin_start%7Cnavigation%7Cmodule; sid=3948dj39; foo=bar',
            ]));

        $response = $this->callMethod($tab, 'getRequestTabRequestHeaders');

        $this->assertInternalType('string', $response);
    }


    /**
     *
     */
    public function testGetContent()
    {
        /** @var HTTP $tab */
        $tab = oxNew(HTTP::class);

        $this->assertInternalType('string', $tab->getContent());
    }
}