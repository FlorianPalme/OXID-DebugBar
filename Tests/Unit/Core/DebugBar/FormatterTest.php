<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests\Unit\Core\DebugBar;

use FlorianPalme\DebugBar\Core\DebugBar\Formatter;
use FlorianPalme\DebugBar\Tests\UnitTestCase;

final class FormatterTest extends UnitTestCase
{
    /**
     *
     */
    public function testFormatBytesMB()
    {
       /** @var Formatter $formatter */
        $formatter = oxNew(Formatter::class);
        $return = $formatter->formatBytes(4134244);

        $this->assertEquals('3.94 <span>M</span>', $return);
    }

    /**
     *
     */
    public function testFormatBytesKB()
    {
        /** @var Formatter $formatter */
        $formatter = oxNew(Formatter::class);
        $return = $formatter->formatBytes(5930, 5);

        $this->assertEquals('5.79102 <span>K</span>', $return);
    }

    /**
     *
     */
    public function testFormatPHPOnOffValueTrue()
    {
        /** @var Formatter $formatter */
        $formatter = oxNew(Formatter::class);

        $this->assertTrue($formatter->formatPHPOnOffValue('On'));
        $this->assertTrue($formatter->formatPHPOnOffValue('on'));
        $this->assertTrue($formatter->formatPHPOnOffValue(true));
        $this->assertTrue($formatter->formatPHPOnOffValue(1));
    }

    /**
     *
     */
    public function testFormatPHPOnOffValueFalse()
    {
        /** @var Formatter $formatter */
        $formatter = oxNew(Formatter::class);

        $this->assertFalse($formatter->formatPHPOnOffValue('Off'));
        $this->assertFalse($formatter->formatPHPOnOffValue('off'));
        $this->assertFalse($formatter->formatPHPOnOffValue(false));
        $this->assertFalse($formatter->formatPHPOnOffValue(0));
    }
}