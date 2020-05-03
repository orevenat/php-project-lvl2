<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($data, $format)
{
    switch ($format) {
        case 'json':
            return json_decode($data);
        break;
        case 'yml':
        case 'yaml':
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        break;
        default:
            throw new \Exception('Undefined extension');
    }
}
