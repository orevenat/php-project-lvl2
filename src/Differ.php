<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\render;
use function Funct\Collection\union;

function genDiff(string $path1, string $path2)
{
    $data1 = file_get_contents(realpath($path1));
    $extension1 = pathinfo($path1, PATHINFO_EXTENSION);
    $parsedData1 = parse($data1, $extension1);

    $data2 = file_get_contents(realpath($path2));
    $extension2 = pathinfo($path2, PATHINFO_EXTENSION);
    $parsedData2 = parse($data2, $extension2);

    $internalTree = buildDiff($parsedData1, $parsedData2);
    return render($internalTree);
}

function buildNode($status, $key, $oldValue, $newValue, $children = [])
{
    return [
        'status' => $status,
        'key' => $key,
        'oldValue' => $oldValue,
        'newValue' => $newValue,
        'children' => $children
    ];
}

function buildDiff($before, $after)
{
    $beforeKeys = array_keys(get_object_vars($before));
    $afterKeys = array_keys(get_object_vars($after));

    $allKeys = union($beforeKeys, $afterKeys);
    $astDiff = array_map(function ($key) use ($before, $after) {
        if (!property_exists($after, $key)) {
            return buildNode('removed', $key, $before->$key, null);
        } elseif (!property_exists($before, $key)) {
            return buildNode('added', $key, null, $after->$key);
        } else {
            $beforValue = $before->$key;
            $afterValue = $after->$key;

            if (is_object($beforValue) && is_object($afterValue)) {
                $children = buildDiff($beforValue, $afterValue);
                return buildNode('nested', $key, null, null, $children);
            } elseif ($beforValue === $afterValue) {
                return buildNode('unchanged', $key, $beforValue, $afterValue);
            }

            return buildNode('changed', $key, $beforValue, $afterValue);
        }

    }, $allKeys);

    return $astDiff;
}
