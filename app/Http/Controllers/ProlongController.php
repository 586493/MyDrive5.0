<?php

namespace App\Http\Controllers;

use App\GoogleAuth;
use App\SessionData;
use App\WebsiteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProlongController extends Controller
{
    public function prolong(Request $request)
    {
        if (SessionData::isLoggedIn()) {
            $username = request()->post("username");
            $pswd = request()->post("pswd");
            $currWebsiteUser = SessionData::getSessionUser();
            if (strcmp($username, $currWebsiteUser->username) === 0) {
                if (Hash::check($pswd, $currWebsiteUser->password) === true) {
                    SessionData::setLogoutTime(true);
                    return response()->json([]);
                } else if (GoogleAuth::isOtpCorrect($currWebsiteUser->secret_key, $pswd) === true) {
                    SessionData::setLogoutTime(true);
                    return response()->json([]);
                }
            }
        }

        SessionData::clearSession();
        return response()->json([]);
    }
}
