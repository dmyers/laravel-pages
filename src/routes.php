<?php

if (Pages::homePage()) {
	Route::get(Pages::routePath().Pages::homePage(), function() {
		return Redirect::to('/');
	});
	Route::get('/', 'PagesController@getIndex');
}

$pages = Pages::listPages();
$regex = implode('|', $pages);
Route::get(Pages::routePath().'{path?}', 'PagesController@getIndex')->where('path', $regex);
