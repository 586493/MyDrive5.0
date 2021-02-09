<?php

namespace App\Http\Controllers;

use App\GoogleAuth;
use App\SessionData;
use App\User;
use App\UserFiles;
use App\WebsiteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    private static $successStatus = 200;
    private static $errorStatus = 403;

    public function display(Request $request)
    {
        if (SessionData::isLoggedIn() === true) {
            /* ******************************* */
            $constArr = config('const');
            $properties = $constArr['properties'];
            $actions = $constArr['actions'];
            $lastModified = $constArr['lastModified'];
            $fileSize = $constArr['fileSize'];
            $filename = $constArr['filename'];
            $actionShare = $constArr['actionShare'];
            $actionDownload = $constArr['actionDownload'];
            $actionRename = $constArr['actionRename'];
            $actionDelete = $constArr['actionDelete'];
            $downloadURL = $constArr['download'];
            $downloadOkInfo = $constArr['downloadOkInfo'];
            $downloadErrInfo = $constArr['downloadErrInfo'];
            /* ******************************* */
            $oldHash = "" . (request()->post("contentHash"));
            $websiteUser = SessionData::getSessionUser();
            $all = UserFiles::getDataToDisplay($websiteUser);

            $md5String = "";
            foreach ($all as $myFile) {
                $md5String .= $myFile->fullName;
                $md5String .= $myFile->path;
                $md5String .= $myFile->sizeStr;
                $md5String .= $myFile->mimeType;
                $md5String .= $myFile->size;
                $md5String .= $myFile->date;
            }
            $md5Hash = md5($md5String);
            $update = strcmp($oldHash, $md5Hash) !== 0;

            $content = "";
            foreach ($all as $myFile) {
                if ($update !== true) {
                    break;
                }
                $content = $content .
                    <<<HEREDOC
                    <div class="px-3 py-2 m-2 d-flex justify-content-between align-content-center fileDiv fileDivSize" style="">
                        <div style="width: 14%;">
                        $myFile->icon
                        </div>
                        <div class="textOverflow" style="width: 66%;">
                        <span title="$myFile->fullName">$myFile->fullName<span>
                        </div>
                        <div class="dots dropup dropupDots" style="width: 15%; cursor: default !important;">
                            <span data-toggle="dropdown" style="cursor: pointer !important;"
                                        onclick="dotsClicked(this)">&nbsp;&nbsp;&nbsp;⋮&nbsp;</span>
                            <div class="dropdown-menu dropdown-menu-right fileDropdown"
                            style="max-width: 95vw !important; z-index: 1000;">
                                <div class="dropdown-header">$actions</div>
                                <div class="dropdown-item fileDropdownText"
                                    style="cursor: pointer;"
                                    onclick="openShareModal('$myFile->path', '$myFile->fullName')"
                                    ><span class="material-icons"
                                    style="display: inline-block; vertical-align: text-bottom;"
                                    >person_add</span>&nbsp;&nbsp;&nbsp;$actionShare</div>
                                <div class="dropdown-item fileDropdownText"
                                    onclick="downloadFile('$myFile->path', '$myFile->fullName', '$downloadOkInfo', '$downloadErrInfo', '$downloadURL')"
                                    style="cursor: pointer;"
                                    ><span class="material-icons"
                                    style="display: inline-block; vertical-align: text-bottom;"
                                    >cloud_download</span>&nbsp;&nbsp;&nbsp;$actionDownload</div>
                                <div class="dropdown-item fileDropdownText"
                                    onclick="openRename('$myFile->path', '$myFile->fullName', '$myFile->nameWithoutExt', '$myFile->extension')"
                                    style="cursor: pointer;"
                                    ><span class="material-icons"
                                    style="display: inline-block; vertical-align: text-bottom;"
                                    >edit</span>&nbsp;&nbsp;&nbsp;$actionRename</div>
                                <div class="dropdown-item fileDropdownText"
                                    style="cursor: pointer;"
                                    onclick="openDeleteModal('$myFile->path', '$myFile->fullName')"
                                    ><span class="material-icons"
                                    style="display: inline-block; vertical-align: text-bottom;"
                                    >delete_forever</span>&nbsp;&nbsp;&nbsp;$actionDelete</div>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-header">$properties</div>
                                <div class="dropdown-item dropdown-item-text fileDropdownText textOverflow"
                                    ><span style="font-weight: 600">$filename</span>$myFile->fullName</div>
                                <div class="dropdown-item dropdown-item-text fileDropdownText"
                                    ><span style="font-weight: 600">$lastModified</span>$myFile->date</div>
                                <div class="dropdown-item dropdown-item-text fileDropdownText mb-1"
                                    ><span style="font-weight: 600">$fileSize</span>$myFile->sizeStr</div>
                            </div>
                        </div>
                    </div>
                    HEREDOC;
            }
            return response()->json([
                'update' => $update,
                'content' => $content,
                'hash' => $md5Hash,
                'storageInfo' => UserFiles::storageInfo($websiteUser),
            ], self::$successStatus);
        } else {
            return response()->json([], self::$errorStatus);
        }
    }

    public function download(Request $request)
    {
        $path = "" . (request()->post("path"));

        if (SessionData::isLoggedIn() === true && strlen($path) >= 1) {
            $websiteUser = SessionData::getSessionUser();
            if (strpos($path, $websiteUser->username) !== false) {
                $all = UserFiles::getDataToDisplay($websiteUser);
                foreach ($all as $myFile) {
                    if (strcmp($myFile->path, $path) === 0) {
                        return Storage::download($path);
                    }
                }
            }
        }

        return response()->json([], self::$errorStatus);
    }

    public function clean(Request $request)
    {
        $path = "" . (request()->post("path"));
        $name = "" . (request()->post("name"));

        if (SessionData::isLoggedIn() === true && strlen($path) >= 1 && strlen($name) >= 1) {
            $websiteUser = SessionData::getSessionUser();
            if (strpos($path, $websiteUser->username) !== false) {
                $all = UserFiles::getDataToDisplay($websiteUser);
                foreach ($all as $myFile) {
                    if (strcmp($myFile->path, $path) === 0) {
                        return response()->json([
                            'text' => UserFiles::cleanName($websiteUser, $name, $myFile->mimeType),
                        ], self::$successStatus);
                    }
                }
            }
        }

        return response()->json([], self::$errorStatus);
    }

    private static function replaceLast($string, $find, $replace)
    {
        $result = preg_replace(
            strrev("/$find/"),
            strrev($replace),
            strrev($string),
            1);
        return strrev($result);
    }

    public static function rename(Request $request)
    {
        $newName = "" . (request()->post("newName"));
        $path = "" . (request()->post("path"));

        $lenGood = strlen($path) >= 1 && strlen($newName) >= 1;

        if (SessionData::isLoggedIn() === true && $lenGood) {
            $websiteUser = SessionData::getSessionUser();
            if (strpos($path, $websiteUser->username) !== false) {
                $all = UserFiles::getDataToDisplay($websiteUser);
                foreach ($all as $myFile) {
                    if (strcmp($myFile->path, $path) === 0) {
                        $finalName = UserFiles::cleanName($websiteUser, $newName, $myFile->mimeType);
                        $newPath = self::replaceLast(
                            $myFile->path,
                            $myFile->fullName,
                            $finalName);
                        Storage::move($myFile->path, $newPath);
                        $constArr = config('const');
                        $format = $constArr['renameOkInfo'];
                        $infoTxt = sprintf($format, $finalName);
                        UserFiles::removeDataToDisplay($websiteUser, $myFile->path);
                        UserFiles::addDataToDisplay($websiteUser, $finalName);
                        return response()->json([
                            'info' => $infoTxt,
                        ], self::$successStatus);
                    }
                }
            }
        }

        return response()->json([], self::$errorStatus);
    }

    public static function delete(Request $request)
    {
        $path = "" . (request()->post("path"));

        if (SessionData::isLoggedIn() === true && strlen($path) >= 1) {
            $websiteUser = SessionData::getSessionUser();
            if (strpos($path, $websiteUser->username) !== false) {
                $all = UserFiles::getDataToDisplay($websiteUser);
                foreach ($all as $myFile) {
                    if (strcmp($myFile->path, $path) === 0) {
                        $constArr = config('const');
                        Storage::delete($myFile->path);
                        $infoTxt = $constArr['deleteOkInfo'];
                        UserFiles::removeDataToDisplay($websiteUser, $myFile->path);
                        return response()->json([
                            'info' => $infoTxt,
                        ], self::$successStatus);
                    }
                }
            }
        }

        return response()->json([], self::$errorStatus);
    }

    public static function share(Request $request)
    {
        $path = "" . (request()->post("path"));
        $username = "" . (request()->post("username"));

        if (SessionData::isLoggedIn() === true && strlen($username) >= 1 && strlen($path) >= 1) {
            $users = WebsiteUser::where('username', $username)->get();
            $websiteUser = SessionData::getSessionUser();
            if (!$users->isEmpty()) {
                $target = $users->first();
                if (strcmp($target->username, $websiteUser->username) !== 0) {
                    if (strpos($path, $websiteUser->username) !== false) {
                        $all = UserFiles::getDataToDisplay($websiteUser);
                        foreach ($all as $myFile) {
                            if (strcmp($myFile->path, $path) === 0) {
                                $constArr = config('const');
                                $bytes = $myFile->size;
                                $allFilesSize = UserFiles::allFilesSize($target);
                                if ($constArr['storageLimit'] >= ($bytes + $allFilesSize)) {
                                    $finalName = UserFiles::cleanName($target, $myFile->fullName, $myFile->mimeType);
                                    $dest = $target->username . "/" . $finalName;
                                    $format = $constArr['shareOkInfo'];
                                    $infoTxt = sprintf($format, $myFile->fullName, $target->username);
                                    Storage::copy($myFile->path, $dest);
                                    return response()->json([
                                        'info' => $infoTxt,
                                    ], self::$successStatus);
                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json([], self::$errorStatus);
    }
}
