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
        $before = getFixturePath("before.json");
        $after = getFixturePath("after.json");
        $expected = trim(file_get_contents(getFixturePath("expected.pretty")));

        $actual = genDiff($before, $after);

        $this->assertEquals($expected, $actual);
    }

    public function testPlain()
    {
        $before = getFixturePath("before.yml");
        $after = getFixturePath("after.yml");
        $expected = trim(file_get_contents(getFixturePath("expected.plain")));

        $actual = genDiff($before, $after, 'plain');

        $this->assertEquals($expected, $actual);
    }

    public function testJson()
    {
        $before = getFixturePath("before.json");
        $after = getFixturePath("after.json");
        $expected = trim(file_get_contents(getFixturePath("expected.json")));

        $actual = genDiff($before, $after, 'json');

        $this->assertEquals($expected, $actual);
    }
}
