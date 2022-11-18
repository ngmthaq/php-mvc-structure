<?php

namespace App\Helpers;

final class Logger
{
    public const DIR = "../storage/logs";

    public static function write(string $fileName, string $content)
    {
        $date = gmdate("Y-m-d");
        $time = "[" . gmdate("H:i:s") . " UTC] ";
        $dir = self::DIR . "/" . $fileName . "-" . $date . "-utc.log";
        if (!is_dir(self::DIR)) mkdir(self::DIR);
        $file = fopen($dir, "a");
        $content = $time . $content . PHP_EOL . PHP_EOL;
        fwrite($file, $content);
        fclose($file);
    }
}
