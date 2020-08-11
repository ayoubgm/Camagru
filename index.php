<?php
	define("ROOT", __DIR__ . DIRECTORY_SEPARATOR);
	define("APP", ROOT . 'app' . DIRECTORY_SEPARATOR);
	define("CONTROLLERS", APP . 'controllers' . DIRECTORY_SEPARATOR);
	define("HELPERS", APP . 'helpers' . DIRECTORY_SEPARATOR);
	define("MIDDLEWARES", APP . 'middlewares' . DIRECTORY_SEPARATOR);
	define("MODELS", APP . 'models' . DIRECTORY_SEPARATOR);
	define("VIEWS", APP . 'views' . DIRECTORY_SEPARATOR);
	define("CONFIG", ROOT . 'config' . DIRECTORY_SEPARATOR);
	define("PUBLIC_DIR", ROOT . 'public' . DIRECTORY_SEPARATOR);
	define("CSS", PUBLIC_DIR . 'css' . DIRECTORY_SEPARATOR);
	define("JS", PUBLIC_DIR . 'js' . DIRECTORY_SEPARATOR);
	define("IMAGES", PUBLIC_DIR . 'images' . DIRECTORY_SEPARATOR);
	define("EDITEDPICS", IMAGES . 'editedPics' . DIRECTORY_SEPARATOR);
	define("STICKERS", IMAGES . 'stickers' . DIRECTORY_SEPARATOR);
	define("SERVER",
		'http://'. $_SERVER['HTTP_HOST'].
		str_replace(
			$_SERVER['DOCUMENT_ROOT'],
			'',
			str_replace('\\', '/', dirname(__DIR__).'/camagru_git')
		)
	);
	define("PUBLIC_FOLDER",
		'http://'. $_SERVER['HTTP_HOST'].
		str_replace(
			$_SERVER['DOCUMENT_ROOT'],
			'',
			str_replace('\\', '/', dirname(__DIR__).'/camagru_git/public')
		)
	);
	$modules = [ROOT, APP, CONTROLLERS, MODELS, VIEWS, HELPERS, MIDDLEWARES, CONFIG, PUBLIC_DIR, CSS, JS, IMAGES];
	set_include_path( get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $modules) );	
	spl_autoload_register('spl_autoload', false);
	$router = new Router();
?>