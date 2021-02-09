<?php

namespace App\Http\Controllers;

use App\SessionData;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        if (SessionData::isLoggedIn()) {
            SessionData::clearSession();
        }
        return response()->json([]);
    }
}
