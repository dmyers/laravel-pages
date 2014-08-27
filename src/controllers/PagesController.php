<?php

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
		try {
			return Pages::render($path);
		} catch (NotFoundHttpException $e) {
			App::abort(404, 'Page not found');
		}
	}
}
