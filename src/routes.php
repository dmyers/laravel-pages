<?php

if (Pages::homePage()) {
	Route::get(Pages::routePath().Pages::homePage(), function() {
		return Redirect::to('/');
	});
	Route::get('/', array('uses' => 'PagesController@getIndex'));
}

$pages = Pages::listPages();
$regex = implode('|', $pages);
Route::get(Pages::routePath().'{path?}', array('uses' => 'PagesController@getIndex'))->where('path', $regex);
