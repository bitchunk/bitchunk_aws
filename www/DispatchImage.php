<?php
chdir(dirname(__FILE__));
require_once ('./properties/common.php');
class ImageDumper {
	static $PICTURE_DIR = 'pictures';
	static $THUMB_DIR = 'thumbs';
	static $CONTENT_TYPE_THUMB = 'image/jpeg';

	static function dump() {
		$uri = urldecode($_SERVER['REQUEST_URI']);
		if (!empty($uri)) {
			$separate = explode('/', $uri);
			$dir = $separate[1];
			// $subdir = $separate[2];
			// 0: '/'
		} else {
			self::notfound();
			exit ;
		}
		
		if (count($separate) <= 2) {
			self::notfound();
			exit ;
		}
		if($dir == self::$PICTURE_DIR){
			self::pictOutput($uri);
		}else if($dir == self::$THUMB_DIR){
			self::thumbOutput($uri);
		}else{
			self::notfound();
			exit ;
		}
	}

	static function notfound() {
		header("HTTP/1.0 404 Not Found");
		exit ;
	}

	static function pictOutput($uri) {
		$path = PICT_PATH. trim(str_replace(self::$PICTURE_DIR . '/', '', $uri), '/');
		$info = pathinfo($uri);
		if (!file_exists($path)) {
			self::notfound();
			exit ;
		}
		$mimeTypes = array(
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
		);
		header("Content-Type: " . $mimeTypes[$info['extension']]);
		readfile($path);
		exit ;
	}
	static function thumbOutput($uri) {
		$path = THUMB_PATH. trim(str_replace(self::$THUMB_DIR . '/', '', $uri), '/');
		$info = pathinfo($uri);
		if (!file_exists($path)) {
			self::notfound();
			exit ;
		}
		$mimeTypes = array(
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
		);
		header("Content-Type: " . $mimeTypes[$info['extension']]);
		readfile($path);
		exit ;
	}
};


ImageDumper::dump();
