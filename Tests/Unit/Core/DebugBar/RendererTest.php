<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core\DebugBar;

use FlorianPalme\DebugBar\Core\DebugBar\Renderer;
use FlorianPalme\DebugBar\Core\DebugBar\Tabber;
use FlorianPalme\DebugBar\Tests\TestData;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class RendererTest extends UnitTestCase
{
    /**
     *
     */
    public function testGetTable()
    {
        /** @var Renderer $renderer */
        $renderer = oxNew(Renderer::class);
        $output = $renderer->getTable('', '');

        $this->assertInternalType('string', $output);
    }

    /**
     * @depends testGetTable
     */
    public function testCreateTable()
    {
        /** @var Renderer $renderer */
        $renderer = oxNew(Renderer::class);

        $output = $renderer->createTable([
            'column a',
            'column b',
        ], [
            [
                'data 1.a',
                'data 1.b',
            ],
            [
                'data 2.a',
                'data 2.b',
            ]
        ]);

        $this->assertInternalType('string', $output);
    }


    /**
     *
     */
    public function testCreateParameterTableBody()
    {
        /** @var Renderer $renderer */
        $renderer = oxNew(Renderer::class);

        $resource = fopen('/tmp/test.txt', 'w+');

        $output = $renderer->createParameterTableBody([
            'string' => 'string',
           'serialized' => serialize(['test']),
           'float' => 5.345,
           'int' => 42,
           'array' => ['abc', 'def'],
           'object' => new \stdClass(),
           'null' => null,
           'false' => false,
           'true' => true,
           'empty' => '',
            'unknown' => $resource,
        ]);

        fclose($resource);

        $this->assertInternalType('string', $output);
    }

    /**
     * @depends testGetTable
     * @depends testCreateParameterTableBody
     */
    public function testCreateParameterTable()
    {
        /** @var Renderer $renderer */
        $renderer = oxNew(Renderer::class);

        $output = $renderer->createParameterTable([
                'column a', 'column b',
            ], [
            ['string', serialize(['test'])],
            [5.345, 42],
            [['abc', 'def'], new \stdClass()],
            [null, false],
            [true, '']
        ]);

        $this->assertInternalType('string', $output);
    }

    /**
     *
     */
    public function testDumpVariable()
    {
        /** @var Renderer $renderer */
        $renderer = oxNew(Renderer::class);
        $output = $renderer->dumpVariable(['abc']);

        $this->assertInternalType('string', $output);
    }

    /**
     *
     */
    public function testCreateBadge()
    {
        /** @var Renderer $renderer */
        $renderer = oxNew(Renderer::class);
        $output = $renderer->createBadge('title', 'value');

        $this->assertInternalType('string', $output);
    }

    /**
     *
     */
    public function testCreateHeadline()
    {
        /** @var Renderer $renderer */
        $renderer = oxNew(Renderer::class);
        $output = $renderer->createHeadline('headline');

        $this->assertInternalType('string', $output);
    }

    /**
     *
     */
    public function testGetIsIcon()
    {
        /** @var Renderer $renderer */
        $renderer = oxNew(Renderer::class);
        $output = $renderer->getIsIcon(true);

        $this->assertInternalType('string', $output);
    }
}