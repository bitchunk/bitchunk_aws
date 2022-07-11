<?php
chdir(dirname(__FILE__));
require_once ('./properties/common.php');

if(!defined('BLOG_HOST')){
	header("location: ". PROTOCOL_HOST);
}

class DispatchAPI {
	static $ROOT_PATH = APP_PATH;
	static $DEFAULT_CARDSMETA = 'common';
	static $cardsmeta = null;
	static $additionalScripts = array();
	static $donateButton = '';
	static $MODULE_DIRS = array('default' => 'default', 'page' => '', 'app' => 'app/', 'api' => 'api/', 'oauth' => 'oauth/' ,'users' => 'users/');
	static $DEFAULT_TEMPLATE = 'page';

	static $additionalHeaders = array();
	static $headerBase = 'common';
	static $breadcrumb = array(array('name' => 'TOP', 'link' => '/'));
	static $pagetitle = SITE_NAME;
	static $pagedescription = SITE_DESCRIPTION;
	static $pagekeywords = SITE_KEYWORDS;

	function dispatch() {
		$uri = $_SERVER['REQUEST_URI'];
		$get = $_GET;

		if (!empty($uri)) {
			$info = parse_url($uri);
			$name = @$info['path'];
			$query = @$info['query'];

			$info = pathinfo($name);

			$name = str_replace(self::$ROOT_PATH, '', $name);
		} else {
			$query = "";
		}
		if ($name == '/' || empty($name)) {
			$name .= 'index';
		}

		$module = self::parseModule($name);
//		var_dump($module);
		if($this->defaultView($name)){
			exit;
		}
		$module['module'] = trim($module['module'], '/');

		$this->moduleView($module);

		//htaccessで振り分け
		if(!empty($info['extension'])){
			self::contentView($name);
		}

		exit ;
	}

	static function parseModule($path){
		$dirs = self::$MODULE_DIRS;
		$found = false;
//		$path = trim($path, '/');
		$mod = array();
		preg_match("/^\/?(\w*)\/?/" , $path, $mod);
		$mod = $mod[1];
		foreach($dirs as $m => $d){
//		var_dump($d, $m, $path, $mod );
//			if(strpos($path, $m) > -1){
			if($mod == $m){
				$found = true;
				break;
			}
		}
		if(!$found){
			$m = self::$DEFAULT_TEMPLATE;
		}
		$module = $m;
		$query = preg_replace("/^\/?{$m}\/?/" , '', $path);
		return array('module' => $module, 'query' => $query);
	}

	function moduleView($module){
		if(array_key_exists($module['module'], self::$MODULE_DIRS)){
			$module['query'] = empty($module['query']) ? 'index' : $module['query'];
			$func = $module['module'].'View';
			$this->$func($module['query']);
		}else{
			$this->notfound();
		}
		exit;
	}

	static function isAPI($name = ""){
		$path = self::$MODULE_DIR_API;
		if(strstr($name, $path) != false){
			return true;
		}
		return false;
	}

	static function isAPP($name = ""){
		if(strstr($name, self::$MODULE_DIR_APP) != false){
			return true;
		}
		return false;
	}

	static function isOauth($name = ""){
		if(strstr($name, self::$MODULE_DIR_OAUTH) != false){
			return true;
		}
		return false;
	}

	static function isUsers($name = ""){
		if(strstr($name, self::$MODULE_DIR_USERS) != false){
			return true;
		}
		return false;
	}

	function notfound() {
		header("HTTP/1.0 404 Not Found");
		exit ;
	}


	function defaultView($name){
		///name
		$dpath = SYSDIR. 'default'. $name;
		$mt = preg_match('/\/$/', $name);
//		var_dump($dpath, $mt);
		if(file_exists($dpath. '/index.php')){
			if($mt === 0){
				header('location: '. $name. '/');
			}
			chdir($dpath);
			require_once ($dpath. '/index.php');
		}else if (file_exists($dpath)){
			chdir(dirname($dpath));
			if(preg_match('/\.+(gif|jpg|jpeg|png|webp)$/', $dpath, $match)){
				header("Content-Type: image/". $match[1]);
				echo file_get_contents(basename($dpath));
			}else{
				require_once (basename($dpath));
			}
		}else{
			return false;
//			require_once (VIEW_PATH. 'notfound.php');
		}
		exit;
	}

	function apiView($name){
		///api/name
		if (file_exists(API_DIR . $name . '.php')) {
			require_once(API_DIR. "AjaxServerBase.php");
			 //require内でセットすること
			$apiClass = '';
			require_once(API_DIR. $name. '.php');

			if(!empty($apiClass)){
				$api = new $apiClass();
				$api->process();
			}
		}else{
			require_once (API_DIR. 'notfound.php');
		}
		exit;
	}

	function oauthView($name){
		///api/name
		if (file_exists(OAUTH_DIR . $name . '/index.php')) {
			require_once (OAUTH_DIR. $name. '/index.php');
		}else{
			require_once (SYSDIR. 'notfound.php');
		}
		exit;
	}

	function usersView($name){
		///api/name
		if (file_exists(SYSDIR . $name . '/index.php')) {
			require_once (SYSDIR. $name. '/index.php');
		}else{
			require_once (USERS_DIR. 'notfound.php');
		}
		exit;
	}

	function appView($name){
		///api/name

		if (file_exists(APP_DIR. $name. '/index.php')) {
			require_once (APP_DIR. $name. '/index.php');
		}else if (file_exists(APP_DIR. $name. '/index.html')) {
			$content = file_get_contents(APP_DIR. $name. '/index.html');
			
			$name = str_replace('/', '', $name);
			if(file_exists(META_PATH. $name. '.php')){
				$insContents = file_get_contents(META_PATH. $name. '.php');
				$content = preg_replace("@<title>.*</title>@", "$0\n".$insContents, $content);
			}
			//$gads = self::is_mobile() ? GOOGLECODE_ADS_APP : GOOGLECODE_ADS_PC;
			//$content = preg_replace("/\<\/body\>\W*\<\/html\>/", $gads. "</body>\n\t</html>", $content);
			echo $content;
		}else{
			$this->notfound();
		}
		exit;
	}

	function contentView($name){
		///api/name
		// var_dump(basename(SYSDIR . $name . ''));
		if (file_exists(SYSDIR . $name)) {
			require_once (SYSDIR. $name);
		}else{
			$this->notfound();
		}
		exit;
	}

	static function pageView($viewPageName ) {
		$cpath = CONTROLLER_PATH . $viewPageName;
		$vpath = VIEW_PATH . $viewPageName;

		if (file_exists($cpath. '.php')){
			require_once (CONTROLLER_PATH . $viewPageName . '.php');
			require_once (VIEW_PATH . 'common/header.php');
			require_once (VIEW_PATH . $viewPageName . '.php');
			require_once (VIEW_PATH . 'common/footer.php');
		}else if(file_exists($vpath. '.php')){
			require_once (VIEW_PATH . $viewPageName . '.php');
			require_once (VIEW_PATH . 'common/header.php');
			require_once (VIEW_PATH . $viewPageName . '.php');
			require_once (VIEW_PATH . 'common/footer.php');
		}else if(file_exists($cpath . '/index.php')){
			chdir($cpath);
			require_once (CONTROLLER_PATH . $viewPageName . '/index.php');
			return;
		}else if(file_exists($vpath . '/index.php')){
			chdir($vpath);
			require_once (VIEW_PATH . $viewPageName . '/index.php');
			return;
		}else{
			$viewPageName = 'notfound';
			require_once (CONTROLLER_PATH . $viewPageName . '.php');
			require_once (VIEW_PATH . 'common/header.php');
			require_once (VIEW_PATH . $viewPageName . '.php');
			require_once (VIEW_PATH . 'common/footer.php');
		}


	}

	static function appendJS($filename)
	{
		if(!is_array($filename)){
			$filename = array($filename);
		}
		foreach($filename as $file){
			self::$additionalScripts[] = $file;
		}
	}

	static function appendBreadCrumb($str, $link){
		array_push(self::$breadcrumb, array('name' => $str, 'link' => $link));
	}

	static function outputBreadCrumb(){
		$str = '<ul itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">';
		foreach(self::$breadcrumb as $index=>$row){
			$str .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
			if($index == count(self::$breadcrumb) - 1){
				$str .= '<span itemprop="name">'. $row['name']. '</span>';
			}else{
				$str .= '<a itemprop="item" href="'. $row['link']. '" ><span itemprop="name">'. $row['name']. '</span></a>';
			}
			$str .= '<meta itemprop="position" content="'. ($index + 1). '" />';
			$str .= '</li>';
		}
		'</ul>';
		return $str;
	}
	static function setPageTitle($title, $fullEnable = false){
		self::$pagetitle = !$fullEnable ? $title. ' | '. self::$pagetitle : $title;
	}
	static function pageTitle(){
		return self::$pagetitle;
	}

	static function pageDescription(){
		return self::$pagedescription;
	}

	static function pageKeywords(){
		return self::$pagekeywords;
	}

	function recursive_array($arr, $rec){
		if(count($arr) > 0){
			$rec[] = array_pop($arr);
		}
	}

	static function getIgnore(){
		$csv = file_get_contents(PICTURE_IGNORE_FILES_PATH);
		$csv = str_replace("\r\n", "\n", $csv);
		$csv = trim($csv);
		$csv = explode("\n", $csv);
		$keys = array_shift($csv);
		$ignore = array();
		$keys = explode(',', $keys);
		// foreach($keys as $key){
			// $ignore[trim($key, '"')] = array();
		// }
		// category	year	name

		$keyLength = count($keys);
		foreach($csv as $row){
			$vals = explode(",", $row);
			foreach($vals as $index => $val){
				$val = trim($val, '"');
				if(empty($val)){
					continue;
				}
				$ignore[$val] = $index + 1 < $keyLength ? true : $val;
			}
		}

		return $ignore;
	}
	static function siteUpdatesLog(){
		$csv = new SplFileObject(VIEW_PATH . 'updates.csv');
		$csv->setFlags(SplFileObject::READ_CSV);

		$columns = $csv->current();
		$csv->next();
		$updates = array();
		while (!$csv->eof()) {
			$cols = $csv->current();
			$val = array();
			if(count($cols) != count($columns)){
				break;
			}
			foreach($cols as $i => $c){
				$val[$columns[$i]] = $c;
			}
			$updates[] = $val;
			$csv->next();
		}
		return $updates;
	}

	static function is_mobile(){
		$ua = filter_input(INPUT_SERVER , 'HTTP_USER_AGENT');
		if($ua === false){
			return false;
		}
		if((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false)) {
			return true;
		}
		return false;
	}
};
$dis = new DispatchAPI();
$dis->dispatch();
