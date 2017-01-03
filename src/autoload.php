<?php
function fsapi_remote_autoloader($class)
{
    $filename = $class . '.php';
    $path = dirname(__FILE__).DIRECTORY_SEPARATOR;
    if (file_exists($path.$filename)) {
        include($path.$filename);
    }else{
		$vendor = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor/autoload.php';
		if (file_exists($vendor)) {
			include($vendor);
		}
	}
}
spl_autoload_register('fsapi_remote_autoloader');