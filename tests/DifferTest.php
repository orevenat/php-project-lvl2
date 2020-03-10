<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\genDiff;

class DifferTest extends TestCase
{
    public function testPlainJson()
    {
        $configBefore = __DIR__ . '/fixtures/config_before.json';
        $configAfter  = __DIR__ . '/fixtures/config_after.json';
        $expected = trim(file_get_contents(__DIR__ . '/fixtures/expected.txt'));

        $result = genDiff($configBefore, $configAfter);

        $this->assertEquals($expected, $result);
    }
}
