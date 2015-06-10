<?php namespace Dmyers\Pages;

use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Pages
{
	public static function config($key, $default = null)
	{
		return \Config::get('laravel-pages::'.$key, $default);
	}
	
	public static function homePage()
	{
		return static::config('home_page');
	}
	
	public static function routePath()
	{
		return static::contentPath();
	}
	
	public static function contentPath()
	{
		return static::config('content_path', 'pages/');
	}
	
	public static function viewPath()
	{
		return static::config('view_path', 'pages/');
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
		$ignore_paths = static::config('ignore_paths', array());
		
		foreach ($files as $file) {
			$page = $file->getRelativePathname();
			$exts = \View::getExtensions();
			
			foreach ($exts as $ext => $name) {
				if (strpos($page, $ext) !== false) {
					$page = str_replace('.'.$ext, '', $page);
				}
			}
			
			if (!in_array($page, $ignore_paths)) {
				$pages[] = $page;
			}
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
		$page_view = static::pageView($path);
		
		return \View::exists($page_view);
	}
	
	public static function lastModified($path)
	{
		$view = static::view($path);
		$path = $view->getPath();
		$timestamp = \File::lastModified($path);
		$carbon = Carbon::createFromTimestamp($timestamp);
		
		return $carbon;
	}
	
	public static function view($path, array $params = array())
	{
		$page = static::currentPage($path);
		
		if (empty($page)) {
			$page = static::homePage();
		}
		
		$page_view = static::pageView($page);
		
		try {
			return \View::make($page_view, $params);
		} catch (\InvalidArgumentException $e) {
			// Catch view exceptions and throw exception when no page found.
			throw new NotFoundHttpException($e->getMessage());
		}
	}
	
	public static function show($path, array $params = array())
	{
		$view = static::view($path, $params);
		
		return $view->render();
	}
	
	public static function render($path, array $params = array())
	{
		$page = static::show($path, $params);
		
		$response = \Response::make($page, 200);
		
		$cache = static::config('cache', false);
		
		if ($cache) {
			$modified = static::lastModified($path);
			$expires = Carbon::now()->addSeconds($cache);
			
			$response->setPublic();
			$response->setMaxAge($cache);
			$response->setLastModified($modified);
			$response->setExpires($expires);
		}
		
		return $response;
	}
}