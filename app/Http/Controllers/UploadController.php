<?php

namespace App\Http\Controllers;

use App\Extensions;
use App\SessionData;
use App\UserFiles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    private static $successStatus = 200;
    private static $errorStatus = 403;
    private static $fileKey = 'fileToUpload';

    public function upload(Request $request)
    {
        if (SessionData::isLoggedIn() === true) {
            $user = SessionData::getSessionUser();
            $constArr = config('const');
            $file = $request->file(self::$fileKey) ?? null;
            if (!$request->isMethod('post') || is_null($file)
                || !$file->isValid() || !$file->isFile()) {
                return response($constArr['uploadErrorInfo'], self::$errorStatus);
            }
            $bytes = $file->getSize(); // in bytes
            $sizeStr = UserFiles::sizeToString($bytes);
            $filename = $file->getClientOriginalName();
            if ($bytes > $constArr['uploadLimit']) {
                return response($constArr['uploadSizeProblem'], self::$errorStatus);
            }
            if (!Extensions::isTypeLegal($file->getMimeType())) {
                $format = $constArr['uploadMimeProblem'];
                $formattedString = sprintf($format, "" . $file->getMimeType());
                return response($formattedString, self::$errorStatus);
            }
            $allFilesSize = UserFiles::allFilesSize($user);
            if ($constArr['storageLimit'] < ($bytes + $allFilesSize)) {
                $more = ($bytes + $allFilesSize) - $constArr['storageLimit'];
                $moreStr = UserFiles::sizeToString($more);
                $format = $constArr['storageLimitProblem'];
                $formattedString = sprintf($format, $moreStr);
                return response($formattedString, self::$errorStatus);
            }
            $filename = UserFiles::cleanName($user, $filename, $file->getMimeType()); // after MIME type if()
            $finalName = UserFiles::save($user, $file, $filename);
            $info = sprintf($constArr['uploadSuccessInfo'], $finalName, $sizeStr);
            UserFiles::addDataToDisplay($user, $finalName);
            return response($info, self::$successStatus);
        } else {
            return response('', self::$errorStatus);
        }
    }
}
