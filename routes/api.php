<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// //login
// Route::post('/login','AuthController@login');
// Route::post('/login-ad','AuthController@loginad');

// //Menu
// Route::get('/issues-closed','Admin\ApiController@Closed');
// Route::get('/issues-new','Admin\ApiController@New');
// Route::get('/issues-progress','Admin\ApiController@Progress');
// Route::post('/issues-getissuesuser','Admin\ApiController@Getissuesuser');

// // Route::post('/appointmentlist','Admin\ApiController@Appointmentlist');
// Route::post('/commentlist','Admin\ApiController@Commentlist');
// Route::post('/commentliststatus','Admin\ApiController@CommentlistStatus');


// Route::post('/issues-poststatus','Admin\ApiController@poststatus');
// Route::post('/issues-checkclosedstatus','Admin\ApiController@updateclosedstatus');
// Route::post('/issues-checkkeepstatus','Admin\ApiController@updatekeepstatus');
// Route::post('/issues-getstatus','Admin\ApiController@getstatus');

// Route::post('/issues-getcountComment','Admin\ApiController@getcountComment');
// Route::post('/issues-getComment','Admin\ApiController@getComment');
// Route::post('/issues-postComment','Admin\ApiController@postComment');
// Route::post('/issues-postStatusComment','Admin\ApiController@postStatusComment');


// //service ย่อยต่างๆ
// Route::post('/issues-deviceid','Admin\ApiController@Deviceid'); //รับค่า MacAddress
// Route::post('/issues-postlogin', 'Admin\ApiController@postlogin'); //รับค่า MacAddress IpAddress Token วันหมดอายุ
// Route::post('/issues-delete', 'Admin\ApiController@delete'); //ไม่ได้ใช้
// Route::get('/issues-lastedVersion','Admin\ApiController@lastedVersion'); // เช็คเวอร์ชั้นล่าสุด

Route::post('/login','Admin\ApiController@login');
Route::post('/login-ad','Admin\ApiController@loginad');

Route::post('/checktoken','Admin\ApiController@checktoken');

Route::post('/checkin','Admin\ApiController@postcheckin');
Route::post('/checkout','Admin\ApiController@postcheckout');
Route::post('/getcheckin','Admin\ApiController@getcheckin');

Route::post('/history','Admin\ApiController@gethistorycheckin');
Route::post('/historybetween','Admin\ApiController@gethistorybetweencheckin');

Route::get('/getdepartment','Admin\ApiController@getdepartment');
Route::post('/getuser','Admin\ApiController@getuser');
Route::get('/getuserhis','Admin\ApiController@getuserhis');

Route::post('/posttask','Admin\ApiController@posttask');
Route::post('/gettask','Admin\ApiController@gettask');
Route::post('/updatetask','Admin\ApiController@updatetask');

Route::post('/getassigntask','Admin\ApiController@getassigntask');
Route::post('/poststatustask','Admin\ApiController@poststatustask');
Route::post('/postsubmittask','Admin\ApiController@postsubmittask');
Route::post('/postretask','Admin\ApiController@postretask');

Route::post('/gethistoryassigntask','Admin\ApiController@gethistoryassigntask');
Route::post('/gethistorybetweenassigntask','Admin\ApiController@gethistorybetweenassigntask');

Route::post('/gethistoryassignsolve','Admin\ApiController@gethistoryassignsolve');
Route::post('/gethistorybetweenassignsolve','Admin\ApiController@gethistorybetweenassignsolve');

Route::post('/countsolvework','Admin\ApiController@countsolvework');
Route::post('/getsolvework','Admin\ApiController@getsolvework');
Route::post('/updatesolve','Admin\ApiController@updatesolve');
Route::post('/postsubmitsolvework','Admin\ApiController@postsubmitsolvework');
Route::post('/poststatussolve','Admin\ApiController@poststatussolve');

Route::get('/download','Admin\ApiController@download');


