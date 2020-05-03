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
    public function testPlainJson()
    {
        $formats = ['json', 'yml'];

        foreach($formats as $format) {
            $before = getFixturePath("before.$format");
            $after = getFixturePath("after.$format");
            $expected = trim(file_get_contents(getFixturePath("expected.txt")));

            $actual = genDiff($before, $after);

            $this->assertEquals($expected, $actual);
        }
    }

    public function testRecursiveJson()
    {
        $formats = ['json', 'yml'];

        foreach($formats as $format) {
            $before = getFixturePath("before_recursive.$format");
            $after = getFixturePath("after_recursive.$format");
            $expected = trim(file_get_contents(getFixturePath("expected_recursive.txt")));

            $actual = genDiff($before, $after);

            $this->assertEquals($expected, $actual);
        }
    }
}
