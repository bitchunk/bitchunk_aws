<?php
chdir(dirname(__FILE__));
require_once ('./properties/common.php');
class MP3Dumper {
	static $DIRNAME = 'music';
	static $CONTENT_TYPE = 'audio/mpeg';

	static function dump() {
		$uri = $_SERVER['REQUEST_URI'];
		if (!empty($uri)) {
			$separate = explode('/', $uri);
			$dir = $separate[1];
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

		self::output($uri);

	}

	static function notfound() {
		header("HTTP/1.0 404 Not Found");
		exit ;
	}

	static function output($uri) {
		$path = MUSIC_PATH . str_replace('/' . self::$DIRNAME . '/', "", $uri . '.mp3');
		//-music
		if (!file_exists($path)) {
			self::notfound();
			exit ;
		}

		header("Content-Type: " . self::$CONTENT_TYPE);
		header('Accept-Ranges: bytes');
		header('Content-length: ' . filesize($path));
		header('Content-Disposition: filename="' . basename($path));
		header('Cache-Control: max-age=0');
		// header('X-Pad: avoid browser bug');
		readfile($path);
		exit ;

		// echo file_get_contents($path);
		exit ;

	}

};
MP3Dumper::dump();
?>