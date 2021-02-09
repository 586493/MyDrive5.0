<?php

namespace App;

use PHPGangsta_GoogleAuthenticator;

class GoogleAuth
{
    private static function getAuthObj()
    {
        return new PHPGangsta_GoogleAuthenticator();
    }

    /**
     * @param string $secret
     * @param string $input
     *
     * @return bool
     */
    public static function isOtpCorrect(string $secret, string $input)
    {
        // (int) $discrepancy
        // allowed time drift in 30 second units (8 ===> 4 minutes before or after)
        // [$discrepancy = 1] ===> 30sec before or after (clock tolerance)
        return GoogleAuth::getAuthObj()->verifyCode($secret, $input, 1);
        // return true: OK
        // return false: FAILED
    }

}
