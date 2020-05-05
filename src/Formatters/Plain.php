<?php

namespace Differ\Formatters\Plain;

function iter($tree, $parentKeys = [])
{
    $filtredTree = array_filter($tree, function ($node) {
        return $node['type'] != 'unchanged';
    });

    return array_map(function ($node) use ($parentKeys) {
        $type = $node['type'];
        $key = $node['key'];
        $oldValue = stringify($node['oldValue']);
        $newValue = stringify($node['newValue']);

        $fullPath = [...$parentKeys, $key];
        $fullName = implode(".", $fullPath);

        $message =  "Property '{$fullName}' was";

        switch ($type) {
            case 'changed':
                return "{$message} changed. From '{$oldValue}' to '{$newValue}'";
            case 'removed':
                return "{$message} removed";
            case 'added':
                return "{$message} added with value: '{$newValue}'";
            case 'nested':
                $children = iter($node['children'], $fullPath);
                return implode("\n", $children);
            default:
                throw new \Exception('Undefined type');
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
