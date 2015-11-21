<?php
define("ROOTDIR", dirname(dirname(dirname(__FILE__))));//from properties
define("SYSDIR", ROOTDIR. '/www/');//from properties
define("IMAGE_PATH", SYSDIR. '/images/');
define("PICT_PATH", ROOTDIR. '/pictures/');
define("THUMB_PATH", ROOTDIR. '/thumbs/');
// define("MUSIC_PATH", SYSDIR. '/music/');
define("MUSIC_PATH", ROOTDIR. '/music/');
define("SOUNDS_PATH", SYSDIR. '/sounds/');
define('APP_PATH', SYSDIR. '/app/');
define("PHP_LIBRARY_DIR", SYSDIR. "/lib");

define("MODEL_PATH", SYSDIR. "models/");
define("CONTROLLER_PATH", SYSDIR. "controllers/");
define("DATA_PATH", SYSDIR. "datafiles/");
define("PROP_PATH", SYSDIR. "properties/");
define("VIEW_PATH", SYSDIR. "views");
define("TEMPLATE_PATH", VIEW_PATH. "/templates");


define("CSS_PATH", SYSDIR. "css/");
define("JS_BASE_DIR", SYSDIR. "js/");

define("WEBHOME", '/');
define("MUSIC_URI", WEBHOME. 'music/');
define("PICT_URI", WEBHOME. 'pictures');
define("THUMB_URI", WEBHOME. 'thumbs');
define("BANNER_URI", WEBHOME. 'img/banner');


define("DEFAULT_ENCODE", "utf-8");

define("SESSION_NAME_COMMON", 'HITOKUCHIHU');

define("PICTURE_IGNORE_FILES_PATH", PICT_PATH. "ignores.csv");


define("HOST_LOCAL", "localhost:58106");
define("HOST_BETA", "bitchunk.fam.cx");
define("HOST_PRODUCTION", "hitokuchihu.kemono.jp");

$svh = $_SERVER['HTTP_HOST'];
define("HOST_NAME", $svh);
define("PROTOCOL", 'http' . (@$_SERVER['HTTPS'] == 'on' ? 's' : '') . "://");

if(strstr($svh, HOST_PRODUCTION) != false){
	define('PROTOCOL_HOST', PROTOCOL. HOST_PRODUCTION. '/');
}else if($svh == HOST_BETA){
	define('PROTOCOL_HOST', PROTOCOL. HOST_BETA. '/');
	// ini_set('display_errors', 1);
}else if($svh == HOST_LOCAL){
	define('PROTOCOL_HOST', PROTOCOL. HOST_LOCAL. '/');
	ini_set('display_errors', 1);
}

define('IMAGES_PATH', PROTOCOL_HOST. '/img');

/**
 * common meta tags
 */
define("SITE_NAME", "bitchunk");
define("SITE_AUTHER", "bitchunk");
define("SITE_KEYWORDS", "pixel contents,letro sounds,web applicatioins");
define("SITE_DESCRIPTION", "Web Contents Creative Site");


