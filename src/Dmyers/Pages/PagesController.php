<?php namespace Dmyers\Pages;

use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Pages controller.
 * 
 * @author Derek Myers
 */
class PagesController extends BaseController
{
	/**
	 * Index action.
	 * 
	 * @return View
	 */
	public function getIndex($path = null)
	{
		$pages = \App::make('pages');
		
		try {
			return $pages->render($path);
		} catch (NotFoundHttpException $e) {
			abort(404, 'Page not found');
		}
	}
}
