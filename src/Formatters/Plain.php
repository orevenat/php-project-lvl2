<?php

namespace Differ\Formatters\Plain;

function iter($tree, $parentKeys = [])
{
    $filtredTree = array_filter($tree, fn($node) => $node['type'] != 'unchanged');

    return array_map(function ($node) use ($parentKeys) {
        $type = $node['type'];
        $key = $node['key'];
        $oldValue = stringify($node['oldValue']);
        $newValue = stringify($node['newValue']);

        $fullPath = array_merge($parentKeys, [$key]);
        $fullName = implode(".", $fullPath);

        switch ($type) {
            case 'changed':
                return sprintf("Property '%s' was changed. From '%s' to '%s'", $fullName, $oldValue, $newValue);
            case 'removed':
                return sprintf("Property '%s' was removed", $fullName);
            case 'added':
                return sprintf("Property '%s' was added with value: '%s'", $fullName, $newValue);
            case 'nested':
                $children = iter($node['children'], $fullPath);
                return implode("\n", $children);
            default:
                throw new \Exception("Undefined type: {$type}");
        }
    }, $filtredTree);
}

function stringify($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (!is_object($value) && !is_array($value)) {
        return $value;
    }

    return 'complex value';
}

function render($internalTree)
{
    $diff = iter($internalTree);
    return implode("\n", $diff);
}
