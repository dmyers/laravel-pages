<?php namespace Dmyers\Pages;

use Illuminate\Support\Facades\Redirect;
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
     * Renders a page for the request.
     * 
     * @param  mixed  $path
     * @return View
     */
    public function render($path = null)
    {
        $pages = \App::make('pages');
        
        try {
            return $pages->render($path);
        } catch (NotFoundHttpException $e) {
            abort(404, 'Page not found');
        }
    }
    
    /**
     * Redirect home route to index.
     * 
     * @return Redirect
     */
    public function home()
    {
        return Redirect::to('/');
    }
}
