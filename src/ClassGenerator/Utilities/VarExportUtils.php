<?php

namespace DCarbone\PHPFHIR\ClassGenerator\Utilities;

/**
 * Code based on gist by lithrel; https://gist.github.com/lithrel/a224edb1ed2975992c73
 */
abstract class VarExportUtils
{
    public static function prettyVarExport($var, array $opts = [])
    {
        $opts = array_merge(['indent' => '', 'tab' => '    ', 'array-align' => false], $opts);

        switch (gettype($var)) {
            case 'array':
                $r = [];
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $maxLength = $opts['array-align'] ? max(array_map('strlen', array_map('trim', array_keys($var)))) + 2 : 0;
                foreach ($var as $key => $value) {
                    $key = str_replace("'' . \"\\0\" . '*' . \"\\0\" . ", '', static::prettyVarExport($key));
                    $r[] = $opts['indent'] . $opts['tab']
                        . ($indexed ? '' : str_pad($key, $maxLength) . ' => ')
                        . static::prettyVarExport($value, array_merge($opts, ['indent' => $opts['indent'] . $opts['tab']]));
                }
                return "[\n" . implode(",\n", $r) . "\n" . $opts['indent'] . ']';
            case 'boolean':
                return $var ? 'true' : 'false';
            case 'NULL':
                return 'null';
            default:
                return var_export($var, true);
        }
    }
}
