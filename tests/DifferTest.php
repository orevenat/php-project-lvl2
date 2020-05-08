<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

function getFixturePath($fixtureName)
{
    $parts = [__DIR__, 'fixtures', $fixtureName];
    return realpath(implode(DIRECTORY_SEPARATOR, $parts));
}

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testFormat($before, $after, $format, $expected)
    {
        $before = getFixturePath($before);
        $after = getFixturePath($after);
        $expected = trim(file_get_contents(getFixturePath($expected)));

        $actual = genDiff($before, $after, $format);

        $this->assertEquals($expected, $actual);
    }

    public function additionProvider()
    {
        return [
            'pretty' => ['before.json', 'after.json', 'pretty', 'expected.pretty'],
            'plain' => ['before.yml', 'after.yml', 'plain', 'expected.plain'],
            'json' => ['before.json', 'after.json', 'json', 'expected.json']
        ];
    }
}
