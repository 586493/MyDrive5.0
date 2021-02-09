<?php

use App\SessionData;
use http\Client\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Input\Input;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//header('HTTP/1.0 403 Forbidden');
//die;

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

//$useHttps = false;
$useHttps = true;

if ($useHttps) {
// sets HTTPS to 'off' for non-SSL requests
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        header('Strict-Transport-Security: max-age=31536000');
    } else {
        header('Location: https://' . $_SERVER['HTTP_HOST']
            . $_SERVER['REQUEST_URI'], true, 301);
        die();
    }
}

if (session_status() === PHP_SESSION_ACTIVE) {
    session_regenerate_id();
}

$constArr = config('const');

Route::get($constArr['noscriptRedirect'], function () use ($constArr) {
    $script = sprintf('window.location.replace(\'%s\');', $constArr['homePage']);
    echo "
            <HTML>
                <HEAD><TITLE>403 Forbidden</TITLE></HEAD>
            <BODY BGCOLOR=#FFFFFF>
                <H1>403 Forbidden</H1>JavaScript is disabled!
                <SCRIPT> $script </SCRIPT>
            </BODY>
            </HTML>
            ";
    exit;
});

Route::get($constArr['drive'], function () use ($constArr) {
    if (SessionData::isLoggedIn() === true) {
        return view('layouts.drive', $constArr);
    } else {
        return redirect($constArr['homePage'], 303);
    }
});

Route::get($constArr['homePage'], function () use ($constArr) {
    if (SessionData::isLoggedIn() === true) {
        return redirect($constArr['drive'], 303);
    } else {
        return view('layouts.sign_in', $constArr);
    }
});

Route::get('{any?}', function ($any) {
    $constArr = config('const');
    return redirect($constArr['homePage'], 303);
})->where('any', '.*');

Route::post($constArr['share'], 'FilesController@share');
Route::post($constArr['delete'], 'FilesController@delete');
Route::post($constArr['rename'], 'FilesController@rename');
Route::post($constArr['clean'], 'FilesController@clean');
Route::post($constArr['download'], 'FilesController@download');
Route::post($constArr['display'], 'FilesController@display');
Route::post($constArr['upload'], 'UploadController@upload');
Route::post($constArr['logout'], 'LogoutController@logout');
Route::post($constArr['prolongPost'], 'ProlongController@prolong');
Route::post($constArr['authentication'], 'LoginController@login');
Route::post($constArr['checkCSRF'], function () use ($constArr) {
    return response()->json([
        'refresh' => (strcmp(request()->post("tokenToCmp"), csrf_token()) !== 0)
    ]);
});
