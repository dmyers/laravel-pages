<?php

if (Pages::homePage()) {
	Route::get(Pages::routePath().Pages::homePage(), function() {
		return Redirect::to('/');
	});
	Route::get('/', array('uses' => 'PagesController@getIndex'));
}

Route::get(Pages::routePath().'{path?}', array('as' => 'page', 'uses' => 'PagesController@getIndex'))->where('path', '.+');
//Route::get('{path?}', array('as' => 'page', 'uses' => 'PagesController@getIndex'))->defaults('path', 'home')->where('path', '.+');
