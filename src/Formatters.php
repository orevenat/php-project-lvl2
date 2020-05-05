<?php

namespace Differ\Formatters;

function render($data, $format)
{
    switch ($format) {
        case 'pretty':
            return Pretty\render($data);
        case 'plain':
            return Plain\render($data);
        default:
            throw new \Exception('Undefined format');
    }
}
