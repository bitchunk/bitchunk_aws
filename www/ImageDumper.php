<?php
chdir(dirname(__FILE__));
require_once ('./properties/common.php');
class ImageDumper {
	static $DIRNAME = 'pictures';
	static $CONTENT_TYPE_THUMB = 'image/jpeg';

	static function dump() {
		$uri = $_SERVER['REQUEST_URI'];
		if (!empty($uri)) {
			$separate = explode('/', $uri);
			$dir = $separate[1];
			// $subdir = $separate[2];
			// 0: '/'
		} else {
			self::notfound();
			exit ;
		}
		if (empty($dir) || $dir != self::$DIRNAME) {
			self::notfound();
			exit ;
		}
		if (count($separate) <= 2) {
			self::notfound();
			exit ;
		}

		$info = parse_url($uri);
		self::thumbOutput($info['path']);

	}

	static function notfound() {
		header("HTTP/1.0 404 Not Found");
		exit ;
	}

	static function thumbOutput($uri) {
		$dir = array($uri, $_GET['id'], $_GET['sub'], $_GET['name']);
		$path = implode('/', $dir);
		$path = PICT_PATH . str_replace('/' . self::$DIRNAME . '/', "", $path. '.jpg');
		//-
		if (!file_exists($path)) {
			self::notfound();
			exit ;
		}

		header("Content-Type: " . self::$CONTENT_TYPE_THUMB);
		readfile($path);
		exit ;

		// echo file_get_contents($path);
		exit ;

	}

};
ImageDumper::dump();
?>