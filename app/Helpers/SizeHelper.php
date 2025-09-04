<?php

namespace App\Helpers;

class SizeHelper
{
    public static function formatSize($bytes)
    {
        if ($bytes >= 1000000000) { // 1000 MB = 1 GB
            return number_format($bytes / 1000000000, 2) . ' GB';
        } elseif ($bytes >= 1000000) { // 1000 KB = 1 MB
            return number_format($bytes / 1000000, 2) . ' MB';
        } elseif ($bytes >= 1000) { // 1000 B = 1 KB
            return number_format($bytes / 1000, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }
}
