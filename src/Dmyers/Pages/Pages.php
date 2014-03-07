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
	
	public static function pageView($path)
	{
		$path = str_replace('/', '.', $path);
		$view_path = str_replace('/', '.', static::viewPath());
		$page_view = $view_path.$path;
		return $page_view;
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
		$path = str_replace(static::viewPath(), '', $path);
		$page = trim($path, '/');
		
		return $page;
	}
	
	public static function exists($path)
	{
		$pages = static::listPages();
		
		return in_array($path, $pages);
	}
	
	public static function lastModified($path)
	{
		$view = static::show($path);
		$path = $view->getPath();
		
		return \File::lastModified($path);
	}
	
	public static function show($path)
	{
		$page = static::currentPage($path);
		
		if (empty($page)) {
			$page = static::homePage();
		}
		
		$page_view = static::pageView($page);
		
		try {
			return \View::make($page_view);
		} catch (\InvalidArgumentException $e) {
			// Catch view exceptions and throw exception when no page found.
			throw new \Exception($e->getMessage());
		}
	}
}