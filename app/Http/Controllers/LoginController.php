<?php

namespace App\Http\Controllers;

use App\GoogleAuth;
use App\SessionData;
use App\WebsiteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        SessionData::remove(SessionData::$lastErrKey);
        $constArr = config('const');
        $usernameKey = 'username';
        $pswdKey = 'pswd';
        SessionData::set(SessionData::$lastErrKey, $constArr['logInErr']);
        $inputChars = $constArr['inputChars'];
        $request->validate([
            $usernameKey => [
                'required',
                "min:" . $constArr['inputMinLen'],
                "max:" . $constArr['inputMaxLen'],
                "regex:/(^[$inputChars]+$)+/",
            ],
            $pswdKey => [
                'required',
                "min:" . $constArr['inputMinLen'],
                "max:" . $constArr['inputMaxLen'],
                "regex:/(^[$inputChars]+$)+/",
            ],
        ]);

        SessionData::remove(SessionData::$lastErrKey);
        $username = request()->post($usernameKey);
        $pswd = request()->post($pswdKey);
        $users = WebsiteUser::where('username', $username)->get();
        if (!$users->isEmpty()) {
            $user = $users->first();
            if (Hash::check($pswd, $user->password)) {
                SessionData::signIn($user, $constArr['authMethodPswd']);
                return redirect($constArr['homePage'], 303);
            } else {
                if (GoogleAuth::isOtpCorrect($user->secret_key, $pswd)) {
                    SessionData::signIn($user, $constArr['authMethodOtp']);
                    return redirect($constArr['homePage'], 303);
                } else {
                    SessionData::clearSession();
                    SessionData::set(SessionData::$lastErrKey, $constArr['logInWrongPswd']);
                    return redirect($constArr['homePage'], 303);
                }
            }
        } else {
            // user not found
            $msg = sprintf($constArr['userNotFound'], "$username");
            SessionData::set(SessionData::$lastErrKey, $msg);
            return redirect($constArr['homePage'], 303);
        }
    }
}
