<?php

if (Pages::homePage()) {
    Route::get(Pages::routePath().Pages::homePage(), function() {
        return Redirect::to('/');
    });
    Route::get('/', 'PagesController@render');
}

$pages = Pages::listPages();
$regex = implode('|', $pages);
Route::get(Pages::routePath().'{path?}', 'PagesController@render')->where('path', $regex);
