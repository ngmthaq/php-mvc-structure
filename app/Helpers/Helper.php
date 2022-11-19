<?php

namespace App\Helpers;

final class Helper
{
    final public function upload($file, $dir)
    {
        $fileName = strtolower(pathinfo($file['name'], PATHINFO_FILENAME));
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $finalFileName = "$fileName.$fileExt";
        $tmpFile = $file['tmp_name'];
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        return move_uploaded_file($tmpFile, "$dir/$finalFileName") ? "/$dir/$finalFileName" : null;
    }
}
