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
    public function testPretty()
    {
        $formats = ['json', 'yml'];

        foreach ($formats as $format) {
            $before = getFixturePath("before.$format");
            $after = getFixturePath("after.$format");
            $expected = trim(file_get_contents(getFixturePath("expected.pretty")));

            $actual = genDiff($before, $after);

            $this->assertEquals($expected, $actual);
        }
    }

    public function testPlain()
    {
        $formats = ['json', 'yml'];

        foreach ($formats as $format) {
            $before = getFixturePath("before.$format");
            $after = getFixturePath("after.$format");
            $expected = trim(file_get_contents(getFixturePath("expected.plain")));

            $actual = genDiff($before, $after, 'plain');

            $this->assertEquals($expected, $actual);
        }
    }
}