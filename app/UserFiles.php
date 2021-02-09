<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class UserFiles
{

    /**
     * @param float $val
     * @param int $decimals
     * @return string
     */
    private static function numberFormat(float $val, int $decimals)
    {
        $dec_point = '.';
        $thousands_sep = '';
        return "" . number_format($val, $decimals, $dec_point, $thousands_sep);
    }

    public static function sizeToString(int $bytes)
    {
        $MB = 1024.0 * 1024.0;
        $KB = 1024.0;

        if ($bytes < 3 * $KB) {
            return $bytes . "&nbsp;B";
        } else if ($bytes < 3 * $MB) {
            return self::numberFormat($bytes / $KB, 0) . "&nbsp;KB";
        } else {
            return self::numberFormat($bytes / $MB, 0) . "&nbsp;MB";
        }
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public static function disk()
    {
        return Storage::disk('private');
    }

    public static function save(WebsiteUser $websiteUser, $file, $name)
    {
        self::disk()->putFileAs($websiteUser->username, $file, $name);
        return $name;
    }

    /**
     * @param string $path
     * @return MyFile
     */
    public static function pathToMyFileObj(string $path)
    {
        $basename = basename($path);
        $basenameParts = self::splitFullFilename($basename);
        $myFile = new MyFile();
        $myFile->path = $path;
        $myFile->fullName = $basename;
        $myFile->extension = $basenameParts['extension'];
        $myFile->nameWithoutExt = $basenameParts['nameWithoutExt'];
        $myFile->mimeType = Storage::mimeType($path);
        $myFile->icon = Extensions::getHtmlIcon(Storage::mimeType($path));
        $myFile->sizeStr = self::sizeToString(Storage::size($path)); // size method: bytes
        $myFile->size = Storage::size($path); // size method: bytes
        $myFile->date = TimeData::timeToStr(Storage::lastModified($path)); // lastModified: UNIX timestamp
        return $myFile;
    }

    public static function addDataToDisplay(WebsiteUser $websiteUser, $finalName)
    {
        $path = $websiteUser->username . "/" . $finalName;
        $myFile = self::pathToMyFileObj($path);
        $arr = SessionData::getSessionUserFiles();
        array_push($arr, $myFile);
        self::sortMyFileArr($arr);
        $storagePath = $websiteUser->username;
        SessionData::setSessionUserFiles($arr, $storagePath);
    }

    public static function removeDataToDisplay(WebsiteUser $websiteUser, $path)
    {
        $arr = SessionData::getSessionUserFiles();
        for ($i = 0; $i < count($arr); $i++) {
            $myFile = $arr[$i];
            if (strcmp($myFile->path, $path) === 0) {
                unset($arr[$i]);
                $arr = array_values($arr);
            }
        }
        self::sortMyFileArr($arr);
        $storagePath = $websiteUser->username;
        SessionData::setSessionUserFiles($arr, $storagePath);
    }

    public static function sortMyFileArr(array &$arr)
    {
        usort($arr, function ($a, $b) {
            return strcmp($a->path, $b->path);
        });
    }

    /**
     * @param WebsiteUser $websiteUser
     * @return array
     */
    public static function getDataToDisplay(WebsiteUser $websiteUser)
    {
        $storagePath = $websiteUser->username;
        $storageLastModified = Storage::lastModified($storagePath);
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        if (SessionData::hasSessionUserFiles()
            && SessionData::hasSessionDirLastModified()
            && $storageLastModified === SessionData::getSessionDirLastModified()) {
            return SessionData::getSessionUserFiles();
        }
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        $allPaths = self::disk()->files($storagePath, false); //relatively long time to execute!
        $arr = [];
        //dd($allPaths)
        //  array:2 [▼
        //    0 => "175***/file1.txt"
        //    1 => "175***/file2.txt"
        //  ]
        foreach ($allPaths as $path) {
            $myFile = self::pathToMyFileObj($path);
            array_push($arr, $myFile);
        }
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        if (SessionData::hasSessionUserFiles() !== true
            || SessionData::hasSessionDirLastModified() !== true) {
            SessionData::setSessionUserFiles($arr, $storagePath);
        }
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        return $arr;
    }

    /**
     * @param WebsiteUser $websiteUser
     * @return int
     */
    public static function allFilesSize(WebsiteUser $websiteUser)
    {
        $sum = 0;
        $all = self::getDataToDisplay($websiteUser);
        foreach ($all as $myFile) {
            $sum += $myFile->size;
        }
        return $sum;
    }

    /**
     * @param WebsiteUser $websiteUser
     * @return string
     */
    public static function storageInfo(WebsiteUser $websiteUser)
    {
        $constArr = config('const');
        $storageLimit = $constArr['storageLimit'];
        $filesSize = self::allFilesSize($websiteUser);
        $pc = ((double)$filesSize * 100.0) / ((double)$storageLimit);
        $str1 = self::sizeToString($filesSize);
        $str2 = self::sizeToString($storageLimit);
        $str3 = "" . round($pc, 2);
        $format = $constArr['storageInfoFormat'];
        return sprintf($format, $str1, $str2, $str3);
    }

    public static function splitFullFilename($fullName)
    {
        $array = str_split($fullName);
        $nameWithoutExt = "";
        $extension = "";
        $extFinished = false;
        for ($i = count($array) - 1; $i >= 0; $i--) {
            $c = $array[$i];
            if ($extFinished === false) {
                if (strcmp($c, '.') === 0) {
                    $extFinished = true;
                }
                $extension = $c . $extension;
            } else {
                $nameWithoutExt = $c . $nameWithoutExt;
            }
        }
        return [
            'nameWithoutExt' => $nameWithoutExt,
            'extension' => $extension,
        ];
    }

    public static function cleanName(WebsiteUser $websiteUser, $name, $mimeType)
    {
        $replacement = [
            'ą' => 'a',
            'ć' => 'c',
            'ę' => 'e',
            'ł' => 'l',
            'ń' => 'n',
            'ó' => 'o',
            'ś' => 's',
            'ź' => 'z',
            'ż' => 'z',
            'Ą' => 'A',
            'Ć' => 'C',
            'Ę' => 'E',
            'Ł' => 'L',
            'Ń' => 'N',
            'Ó' => 'O',
            'Ś' => 'S',
            'Ź' => 'Z',
            'Ż' => 'Z',
        ];
        foreach ($replacement as $key => $value) {
            $name = str_replace($key, $value, $name);
        }
        $name = preg_replace('/[^A-Za-z0-9\[\]._\-]/', '_', $name);
        if (strpos($name, '.') === false) {
            $name .= Extensions::getExtensionSuffix($mimeType);
        }

        $fileNameMaxLen = 30;
        if (strlen($name) > $fileNameMaxLen) {
            $chars = array_reverse(str_split($name));
            $tmp = "";
            foreach ($chars as $c) {
                if (strlen($tmp) >= $fileNameMaxLen) {
                    break;
                } else {
                    $tmp = $c . $tmp;
                }
            }
            $name = $tmp;
        }

        $path = $websiteUser->username;
        $index = 2;
        $prefix = "";
        if (self::disk()->exists($path . "/" . $name)) {
            while (self::disk()->exists($path . "/" . $prefix . $name)) {
                $prefix = $index . "_";
                $index = $index + 1;
            }
        }

        return $prefix . $name;
    }

}
