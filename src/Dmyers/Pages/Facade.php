<?php namespace Dmyers\Pages;

use Illuminate\Support\Facades\Facade as FacadeProvider;

class Facade extends FacadeProvider
{
	protected static function getFacadeAccessor() { return 'pages'; }
}
