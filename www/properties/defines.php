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
define('APP_DIR', SYSDIR. '/app/');
define("PHP_LIBRARY_DIR", SYSDIR. "/lib/");

define("MODEL_PATH", SYSDIR. "models/");
define("CONTROLLER_PATH", SYSDIR. "controllers/");
define("DATA_PATH", SYSDIR. "datafiles/");
define("PROP_PATH", SYSDIR. "properties/");
define("VIEW_PATH", SYSDIR. "views/");
define("TEMPLATE_PATH", VIEW_PATH. "/templates");
define("META_PATH", VIEW_PATH. 'cardsmeta/');


define("CSS_PATH", SYSDIR. "css/");
define("JS_BASE_DIR", SYSDIR. "js/");

define("WEBHOME", '/');
define("MUSIC_URI", WEBHOME. 'music/');
define("PICT_URI", WEBHOME. 'pictures');
define("THUMB_URI", WEBHOME. 'thumbs');
define("BANNER_URI", WEBHOME. 'img/banner');
define('APP_URI', SYSDIR. 'app/');


define("DEFAULT_ENCODE", "utf-8");

define("SESSION_NAME_COMMON", 'BITCHUNK');

define("PICTURE_IGNORE_FILES_PATH", PICT_PATH. "ignores.csv");


define("HOST_LOCAL", "localhost:58106");
define("HOST_BETA", "bitchunk.fam.cx");
define("HOST_PRODUCTION", "bitchunk.net");

$svh = $_SERVER['HTTP_HOST'];
define("HOST_NAME", $svh);
define("PROTOCOL", 'http' . (@$_SERVER['HTTPS'] == 'on' ? 's' : '') . "://");

if(strstr($svh, HOST_PRODUCTION) != false){
	define('BLOG_HOST', PROTOCOL. 'blog.' .HOST_NAME. '/');
}else if($svh == HOST_BETA){
	ini_set('display_errors', 1);
}else if($svh == HOST_LOCAL){
	ini_set('display_errors', 1);
	define('BLOG_HOST', PROTOCOL. 'localhost:58107'. '/');
}

define('PROTOCOL_HOST', PROTOCOL. HOST_NAME. '/');

define('IMAGES_PATH', PROTOCOL_HOST. '/img');

/**
 * common meta tags
 */
define("SITE_NAME", "bitchunk");
define("SITE_AUTHER", "bitchunk");
define("SITE_KEYWORDS", "pixel contents,retro sounds,web applicatioins");
define("SITE_DESCRIPTION", "Web Contents Creative Site");

/**
 * google codes
 */
define("GOOGLECODE_ANALYTICS", '
');
define("GOOGLECODE_ADS_PC", '
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- PCフッター -->
<ins class="adsbygoogle"
     style="display:inline-block;width:234px;height:60px"
     data-ad-client="ca-pub-5168032248020758"
     data-ad-slot="2767325429"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
');
define("GOOGLECODE_ADS_APP", '
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- appフッター -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-5168032248020758"
     data-ad-slot="1153179391"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
');
