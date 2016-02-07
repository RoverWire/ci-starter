<?php
namespace CiStarter;
use Composer\Script\Event;
use Composer\Installer\PackageEvent;

/**
 * Class to handle composer events
 */

class Package {

	public static function clean(Event $event)
	{
		$package = new static();
		$vendor_dir = $event->getComposer()->getConfig()->get('vendor-dir');

		$package->removeFolder($vendor_dir . '/codeigniter/framework/application');
		$package->removeFolder($vendor_dir . '/codeigniter/framework/user_guide');
		$package->removeFolder($vendor_dir . '/codeigniter/framework/.git');
		$package->deleteFiles($vendor_dir . '/codeigniter/framework');
		$event->getIO()->write('codeigniter package cleaned');

		$package->removeFolder($vendor_dir . '/google/apiclient/examples');
		$package->removeFolder($vendor_dir . '/google/apiclient/style');
		$package->removeFolder($vendor_dir . '/google/apiclient/tests');
		$package->removeFolder($vendor_dir . '/google/apiclient/.git');
		$package->deleteFiles($vendor_dir . '/google/apiclient');
		$event->getIO()->write('apiclient package cleaned');
	}

	public function removeFolder($element)
	{
		if (is_dir($element)) {
			$objects = scandir($element);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($element."/".$object)) {
						$this->removeFolder($element."/".$object);
					} else {
						@unlink($element."/".$object);
					}
				}
			}

			@rmdir($element);
			return TRUE;
		}

		return FALSE;
	}

	public function deleteFiles($element)
	{
		if (is_dir($element)) {
			$objects = scandir($element);
			foreach ($objects as $file) {
				$item = $element.'/'.$file;
				if ($item != '.' && $item != '..' && !is_dir($item)) {
					@unlink($item);
				}
			}

			return TRUE;
		} else if (is_file($element)) {
			@unlink($element);
			return TRUE;
		}

		return FALSE;
	}

}
