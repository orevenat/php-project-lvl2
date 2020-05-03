<?php

namespace Differ\Formatters;

const BASE_INDENT = '    ';

function iter($tree, $depth = 1)
{
    $indent = str_repeat(BASE_INDENT, $depth);
    $nodeIndent = substr($indent, 0, strlen($indent) - 2);

    $diff = array_map(function ($node) use ($depth, $indent, $nodeIndent) {
        $status = $node['status'];
        $key = $node['key'];
        $oldValue = stringify($node['oldValue'], $depth);
        $newValue = stringify($node['newValue'], $depth);

        switch ($status) {
            case 'unchanged':
                return "{$nodeIndent}  {$key}: {$newValue}";
            case 'changed':
                $lines = ["{$nodeIndent}+ {$key}: {$newValue}", "{$nodeIndent}- {$key}: {$oldValue}"];
                return implode("\n", $lines);
            case 'removed':
                return "{$nodeIndent}- {$key}: {$oldValue}";
            case 'added':
                return "{$nodeIndent}+ {$key}: {$newValue}";
            case 'nested':
                $children = iter($node['children'], $depth + 1);
                return "{$nodeIndent}  {$key}: {\n{$children}\n{$indent}}";
            default:
                throw new \Exception('Undefined status');
        }
    }, $tree);

    return implode("\n", $diff);
}

function render($internalTree)
{
    $result = iter($internalTree, 1);
    return "{\n$result\n}";
}

function stringify($value, $depth = null)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (!is_object($value) && !is_array($value)) {
        return $value;
    }

    $keys = array_keys(get_object_vars($value));
    $indent = str_repeat(BASE_INDENT, $depth + 1);
    $bracketIndent = str_repeat(BASE_INDENT, $depth);
    $list = array_map(function ($key) use ($value, $indent, $depth) {
        $formattedValue = stringify($value->$key, $depth);
        return "{$indent}{$key}: {$formattedValue}";
    }, $keys);

    $str = implode("\n", $list);

    return "{\n{$str}\n{$bracketIndent}}";
}
