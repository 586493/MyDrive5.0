<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class SessionData
{
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    public static $lastErrKey = 'lastErr';
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    private static $loggedInUserKey = 'loggedInUser';
    private static $loggedInAuthMethodKey = 'loggedInAuthMethod';
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    private static $logoutTimeKey = 'logoutTime';
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    private static $userFilesKey = 'userAllFiles';
    private static $userDirLastModifiedKey = 'userDirLastModified';
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    public static function get($key)
    {
        return session($key, null);
    }

    public static function set($key, $value)
    {
        session([$key => $value]);
    }

    public static function remove($key)
    {
        session()->forget($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        return session()->has($key);
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    /**
     * @return array
     */
    public static function getSessionUserFiles()
    {
        return self::get(self::$userFilesKey);
    }

    /**
     * @return int
     */
    public static function getSessionDirLastModified()
    {
        if (self::has(self::$userDirLastModifiedKey)) {
            return self::get(self::$userDirLastModifiedKey);
        } else {
            return -1;
        }
    }

    public static function hasSessionDirLastModified()
    {
        return self::has(self::$userDirLastModifiedKey);
    }

    /**
     * @param array $arr
     * @param string $storagePath
     */
    public static function setSessionUserFiles(array $arr, string $storagePath)
    {
        self::set(self::$userFilesKey, $arr);
        self::set(self::$userDirLastModifiedKey, Storage::lastModified($storagePath));
        // lastModified: UNIX timestamp
    }

    /**
     * @return bool
     */
    public static function hasSessionUserFiles()
    {
        return self::has(self::$userFilesKey);
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    /**
     * @return WebsiteUser
     */
    public static function getSessionUser()
    {
        return self::get(self::$loggedInUserKey);
    }

    /**
     * @return string
     */
    public static function getSessionLogoutTime()
    {
        return self::get(self::$logoutTimeKey);
    }

    /**
     * @return string
     */
    public static function getSessionAuthMethod()
    {
        return self::get(self::$loggedInAuthMethodKey);
    }

    public static function setLogoutTime(bool $longSession)
    {
        $websiteUser = self::getSessionUser();
        WebsiteUser::updateLastActivityTime($websiteUser);
        $lifetime = ($longSession) ? (99 * 60) : (15 * 60);
        self::set(self::$logoutTimeKey, (time() + $lifetime));
    }

    public static function signIn(WebsiteUser $websiteUser, string $authMethod)
    {
        WebsiteUser::updateLastActivityTime($websiteUser);
        self::set(self::$loggedInUserKey, $websiteUser);
        self::set(self::$loggedInAuthMethodKey, $authMethod);
        self::setLogoutTime(false);
    }

    public static function clearSession()
    {
        self::set(self::$logoutTimeKey, 1);
        self::remove(self::$userFilesKey);
        session_unset();
    }

    /**
     * @return bool
     */
    public static function isLoggedIn()
    {
        $test1 = self::has(self::$loggedInUserKey);
        $test2 = self::has(self::$logoutTimeKey);
        $test3 = self::has(self::$loggedInAuthMethodKey);

        if ($test1 && $test2 && $test3) {
            $websiteUser = self::get(self::$loggedInUserKey);
            $users = WebsiteUser::where('username', $websiteUser->username)->get();
            if (!$users->isEmpty()) {
                $user = $users->first();
                if (strcmp($user->password, $websiteUser->password) === 0
                    && strcmp($user->secret_key, $websiteUser->secret_key) === 0) {
                    if (SessionData::getSessionLogoutTime() >= time()) {
                        return true;
                    } else {
                        self::clearSession();
                        return false;
                    }
                }
            }
        }
        return false;
    }

}
