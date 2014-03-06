<?php namespace Dmyers\Pages;

class Pages
{
	public static function config($key, $default = null)
	{
		return \Config::get('laravel-pages::'.$key, $default);
	}
	
	public static function homePage()
	{
		return static::config('home_page', false);
	}
	
	public static function routePath()
	{
		return static::contentPath();
	}
	
	public static function contentPath()
	{
		return static::config('content_path');
	}
	
	public static function viewPath()
	{
		return static::config('view_path');
	}
	
	public static function listPages($page_path = '')
	{
		$paths = \Config::get('view.paths');
		$path = $paths[0].'/'.static::viewPath();
		
		if (!empty($page_path)) {
			$path .= '/' . $page_path;
		}
		
		$files = \File::allFiles($path);
		$pages = array();
		
		foreach ($files as $file) {
			$page = $file->getRelativePathname();
			$parts = explode('.', $page);
			$page = $parts[0];
			
			$pages[] = $page;
		}
		
		return $pages;
	}
	
	public static function currentPage($path)
	{
		// Turn slashes into dots for folder views.
		// Ex: feature/feedback => feature.feedback
		$path = str_replace(static::contentPath(), '', $path);
		$page = str_replace('/', '.', $path);
		
		return $page;
	}
	
	public static function show($path)
	{
		$page = static::currentPage($path);
		
		if (empty($page)) {
			$page = static::homePage();
		}
		
		try {
			return \View::make(static::config('view_path').$page);
		} catch (\InvalidArgumentException $e) {
			// Catch view exceptions and throw exception when no page found.
			throw new \Exception($e->getMessage());
		}
	}
}