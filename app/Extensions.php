<?php

namespace App;

class Extensions
{
    private static $mimeTypes = [
        'application/pdf' => ".pdf",
        'application/zip' => ".zip",
        'image/jpeg' => ".jpg",
        'image/png' => ".png",
        'text/plain' => ".txt",
        'text/x-c' => ".cpp",  // works also for .asm
        'text/x-c++' => ".cpp",
    ];

    private static $mimeTypeSymbol = [
        //        ".pdf" => "📄",
        //        ".zip" => "🗜️",
        //        ".jpg" => "📷",
        //        ".png" => "📷",
        //        ".txt" => "📝",
        //        ".cpp" => "📄",
        //        ".myDir" => "📁",
        'application/pdf' => "PDF",
        'application/zip' => "ZIP",
        'image/jpeg' => "JPG",
        'image/png' => "PNG",
        'text/plain' => "TXT",
        'text/x-c' => "&nbsp;C&nbsp;",  // works also for .asm
        'text/x-c++' => "C++",
    ];

    private static $mimeTypeColor = [
        'application/pdf' => "rgba(213,0,0, 0.54)",
        'application/zip' => "rgba(120,144,156, 0.54)",
        'image/jpeg' => "rgba(253,216,53, 0.54)",
        'image/png' => "rgba(251,140,0, 0.54)",
        'text/plain' => "rgba(0,172,193, 0.54)",
        'text/x-c' => "rgba(156,39,176, 0.54)",  // works also for .asm
        'text/x-c++' => "rgba(132,15,153, 0.54)",
    ];

    public static function getHtmlIcon($mimeType)
    {
        //return self::$iconsMimeTypes[$ext];
        $icon = self::$mimeTypeSymbol[$mimeType];
        $color = self::$mimeTypeColor[$mimeType];
        return <<< HTML
            <div class="d-flex align-items-center justify-content-center mimeIconText"
            style="background-color: $color !important;" title="$mimeType">
            $icon
            </div>
        HTML;
    }

    public static function getAllHtmlIcons()
    {
        $content = "";
        foreach (array_keys(self::$mimeTypes) as $type) {
            $content .= "<div class='mt-1 mb-0 mx-0' style='display: inline-block; width: 5.2vmax;'>";
            $content .= self::getHtmlIcon($type);
            $content .= "</div>";
        }
        return $content;
    }

    public static function getExtensionSuffix($t)
    {
        return self::$mimeTypes[$t];
    }

    public static function getHtmlAccept()
    {
        $str = "accept='";
        foreach (self::$mimeTypes as $type) {
            $str .= "$type, ";
        }
        return $str . "'";
    }

    public static function isTypeLegal($mimeTypeToTest)
    {
        return (array_key_exists("$mimeTypeToTest", self::$mimeTypes));
    }
}
