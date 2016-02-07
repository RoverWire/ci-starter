<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * composer autoload is required in order to
 * initialize and use Whoops object.
 */

require_once('./vendor/autoload.php');
use Whoops\Run;

function load_whoops()
{
	$whoops = new Whoops\Run();
	$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());

	/* checks if is an ajax call */
	if (Whoops\Util\Misc::isAjaxRequest()) {
		$run->pushHandler(new Whoops\Handler\JsonResponseHandler());
	}

	/* checks if is a command line call */
	if (Whoops\Util\Misc::isCommandLine()){
		$run->pushHandler(new Whoops\Handler\PlainTextHandler());
	}

	$whoops->register();
}
