<?php namespace Dmyers\Pages;

use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Pages
{
	public function config($key, $default = null)
	{
		return \Config::get('laravel-pages::'.$key, $default);
	}
	
	public function homePage()
	{
		return $this->config('home_page');
	}
	
	public function routePath()
	{
		return $this->contentPath();
	}
	
	public function contentPath()
	{
		return $this->config('content_path', 'pages/');
	}
	
	public function viewPath()
	{
		return $this->config('view_path', 'pages/');
	}
	
	public function pageView($path)
	{
		$path = str_replace('/', '.', $path);
		$view_path = str_replace('/', '.', $this->viewPath());
		$page_view = $view_path.$path;
		return $page_view;
	}
	
	public function listPages($page_path = '')
	{
		$paths = \Config::get('view.paths');
		$path = $paths[0].'/'.$this->viewPath();
		
		if (!empty($page_path)) {
			$path .= '/' . $page_path;
		}
		
		$files = \File::allFiles($path);
		$pages = array();
		$ignore_paths = $this->config('ignore_paths', array());
		
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
	
	public function currentPage($path)
	{
		// Turn slashes into dots for folder views.
		// Ex: feature/feedback => feature.feedback
		$path = str_replace($this->viewPath(), '', $path);
		$page = trim($path, '/');
		
		return $page;
	}
	
	public function exists($path)
	{
		$page_view = $this->pageView($path);
		
		return \View::exists($page_view);
	}
	
	public function lastModified($path)
	{
		$view = $this->view($path);
		$path = $view->getPath();
		$timestamp = \File::lastModified($path);
		$carbon = Carbon::createFromTimestamp($timestamp);
		
		return $carbon;
	}
	
	public function view($path, array $params = array())
	{
		$page = $this->currentPage($path);
		
		if (empty($page)) {
			$page = $this->homePage();
		}
		
		$page_view = $this->pageView($page);
		
		try {
			return \View::make($page_view, $params);
		} catch (\InvalidArgumentException $e) {
			// Catch view exceptions and throw exception when no page found.
			throw new NotFoundHttpException($e->getMessage());
		}
	}
	
	public function show($path, array $params = array())
	{
		$view = $this->view($path, $params);
		
		return $view->render();
	}
	
	public function render($path, array $params = array())
	{
		$page = $this->show($path, $params);
		
		$response = \Response::make($page, 200);
		
		$cache = $this->config('cache', false);
		
		if ($cache) {
			$modified = $this->lastModified($path);
			$expires = Carbon::now()->addSeconds($cache);
			
			$response->setPublic();
			$response->setMaxAge($cache);
			$response->setLastModified($modified);
			$response->setExpires($expires);
		}
		
		return $response;
	}
}
