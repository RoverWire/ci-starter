<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('./vendor/autoload.php');

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;

function load_whoops()
{
	$whoops = new Whoops\Run();
	$whoops->pushHandler(new PrettyPageHandler());

	if (Whoops\Util\Misc::isAjaxRequest()) {
		$jsonHandler = new JsonResponseHandler();
		$run->pushHandler($jsonHandler);
	}

	$whoops->register();
}
