<?php
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

/*
 * Welcome route - link to any public API documentation here
 */
// Route::get('/', function () {
//     echo 'Welcome to our API';
// });

Route::group([], function () {
    include_route_files(__DIR__.'/API/');
});
