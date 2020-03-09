<?php

namespace Differ;

function genDiff(string $pathToFile1, string $pathToFile2)
{
    $contentBefore = file_get_contents($pathToFile1);
    $contentAfter = file_get_contents($pathToFile2);

    $before = json_decode($contentBefore, true);
    $after = json_decode($contentAfter, true);

    $allKeys = array_unique(array_merge(array_keys($before), array_keys($after)));
    $astDiff = array_map(function ($key) use ($before, $after) {
        if (!array_key_exists($key, $before)) {
            return ['added', $key, convertToString($after[$key]), null];
        }

        if (!array_key_exists($key, $after)) {
            return ['removed', $key, convertToString($before[$key]), null];
        }

        if ($before[$key] === $after[$key]) {
            return ['unchanged', $key, convertToString($before[$key]), null];
        }

        return ['changed', $key, convertToString($before[$key]), convertToString($after[$key])];
    }, $allKeys);


    $diff = array_map(function ($astLine) {
        [$status, $key, $value, $newValue] = $astLine;
        switch ($status) {
            case 'unchanged':
                $res = "    $key: $value";
                break;
            case 'changed':
                $res = "  + $key: $newValue\n  - $key: $value";
                break;
            case 'removed':
                $res = "  - $key: $value";
                break;
            case 'added':
                $res = "  + $key: $value";
                break;
        }

        return $res;
    }, $astDiff);

    $resultDiff = implode("\n", $diff);
    return "{\n$resultDiff\n}";
}

function convertToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return strval($value);
}
