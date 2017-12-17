<?php
class Manage
{
	public static function classname($class)
	{

//this is useful to see what class and namespace is being asked for
//echo $class . '<br>';

	$path = str_replace('\\', '/', $class) . '.php';
//this is useful to see what path is being asked for

//echo $path . '<br>';

	if (is_file($path)) {
			include $path;
			return;
		}
	}
}

spl_autoload_register(array('Manage', 'classname'));
