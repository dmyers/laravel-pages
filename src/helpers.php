<?php

if ( ! function_exists('page_url'))
{
	function page_url($page)
	{
		$pages = App::make('pages');
		
		if ($page == $pages->homePage()) {
			return url('/');
		}
		
		return url(page_path($page));
	}
}

if ( ! function_exists('page_path'))
{
	function page_path($page)
	{
		$pages = App::make('pages');
		
		return $pages->routePath().$page;
	}
}
