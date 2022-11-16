<?php

namespace App\Helpers;

final class Console
{
    public static function log(mixed $output, bool $withScriptTags = true, bool $forceEncode = true): void
    {
        if ($forceEncode) {
            $code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
        } else {
            $code = 'console.log(' . $output . ');';
        }

        if ($withScriptTags) {
            $code = '<script>' . $code . '</script>';
        }

        echo $code;
    }

    public static function error(mixed $output, bool $withScriptTags = true, bool $forceEncode = true): void
    {
        if ($forceEncode) {
            $code = 'console.error(' . json_encode($output, JSON_HEX_TAG) . ');';
        } else {
            $code = 'console.error(' . $output . ');';
        }

        if ($withScriptTags) {
            $code = '<script>' . $code . '</script>';
        }

        echo $code;
    }
}
