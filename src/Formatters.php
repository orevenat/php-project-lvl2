<?php

namespace Differ\Formatters;

function render($data, $format)
{
    switch ($format) {
        case 'json':
            return Json\render($data);
        case 'plain':
            return Plain\render($data);
        case 'pretty':
            return Pretty\render($data);
        default:
            throw new \Exception("Undefined format: {$format}");
    }
}
