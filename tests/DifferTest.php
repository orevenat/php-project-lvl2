<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testPlainJson()
    {
        $types = ['json', 'yml'];

        foreach($types as $type) {
            $configBefore = __DIR__ . "/fixtures/config_before.$type";
            $configAfter  = __DIR__ . "/fixtures/config_after.$type";
            $expected = trim(file_get_contents(__DIR__ . '/fixtures/expected.txt'));

            $result = genDiff($configBefore, $configAfter);

            $this->assertEquals($expected, $result);
        }
    }
}
