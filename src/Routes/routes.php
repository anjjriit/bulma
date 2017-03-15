<?php

Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->middleware('web', 'auth');