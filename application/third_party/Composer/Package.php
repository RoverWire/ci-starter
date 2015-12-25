<?php
namespace CiStarter;
use Composer\Script\Event;
use Composer\Installer\PackageEvent;

/**
 * Class to handle composer events
 */

class Package {

	public static function postInstall(Event $event)
	{
		$vendor_dir = $event->getComposer()->getConfig()->get('vendor-dir');

		if (is_dir($vendor_dir . '/codeigniter/framework/application')) {
			exec("rm -R $vendor_dir/codeigniter/framework/application $vendor_dir/codeigniter/framework/user_guide && rm $vendor_dir/codeigniter/framework/*.*");
			$event->getIO()->write("Codeigniter installation cleaned");
		}

		if (is_dir($vendor_dir . '/google/apiclient/examples')) {
			exec("rm -R $vendor_dir/google/apiclient/examples $vendor_dir/google/apiclient/style $vendor_dir/google/apiclient/tests && rm $vendor_dir/google/apiclient/*.*");
			$event->getIO()->write("Google Api Client cleaned");
		}
	}

}
