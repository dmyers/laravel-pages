<?php

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
		} catch (Exception $e) {
			App::abort(404, 'Page not found');
		}
	}
}
