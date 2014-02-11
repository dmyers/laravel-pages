<?php

if ( ! function_exists('page_url'))
{
	function page_url($page)
	{
		return url(page_path($page));
	}
}

if ( ! function_exists('page_path'))
{
	function page_path($page)
	{
		return Pages::routePath().$page;
	}
}