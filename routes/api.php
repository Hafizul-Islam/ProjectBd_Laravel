<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Vue 
Route::apiResources(
	[
		'post'     => 'Admin\API\PostController',
		'category' => 'Admin\API\CategoryController',
		'tag'      => 'Admin\API\TagController',
	]
);