<?php

namespace Differ\Differ;

use Symfony\Component\Yaml\Yaml;

function genDiff(string $pathToFileBefore, string $pathToFileAfter)
{
    $before = getParsedContent($pathToFileBefore);
    $after = getParsedContent($pathToFileAfter);

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
            default:
                throw new \Exception('Undefined status');

        }

        return $res;
    }, $astDiff);

    $resultDiff = implode("\n", $diff);
    return "{\n$resultDiff\n}";
}

function getParsedContent($pathToFile)
{
    $fileContent = file_get_contents($pathToFile);
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);

    switch ($extension) {
        case 'json':
            return json_decode($fileContent, true);
        break;
        case 'yml':
        case 'yaml':
            return Yaml::parse($fileContent);
        break;
        default:
            throw new \Exception('Undefined extension');
    }
}

function convertToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return strval($value);
}
