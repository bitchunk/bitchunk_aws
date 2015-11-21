<?php
chdir(dirname(__FILE__));
require_once('./properties/common.php');
//ま
class DispatchController {
	static $DEFAULT_CARDSMETA = 'common';
	static $cardsmeta = null;
	static $additionalScripts = array();
	static $additionalHeaders = array();
	static $donateButton = '';
	static $headerBase = 'common';
	static $breadcrumb = array(array('name' => 'とっぷ', 'link' => '/'));
	static $pagetitle = SITE_NAME;
	static $pagedescription = SITE_DESCRIPTION;
	static $pagekeywords = SITE_KEYWORDS;
	
	
	static function dispatch() {
		$uri = $_SERVER['REQUEST_URI'];
		$get = $_GET;
		$match = array();
		
		// preg_match('/\/([^\s\/]*)\/?([^\s\/\?]*)?/', $uri, $match);
		preg_match('/\/([^\s\/\?]*)\/?([^\s\/\?]*)?/', $uri, $match);
		
		
		if (!empty($match[0])) {
			$separate = explode('/', $uri);
			$name = $match[1];
			// 0: '/'
		} else {
			$name = 'index';
		}
		if (empty($name)) {
			$name = 'index';
		}
		// var_dump($uri);
		if(!empty($get['app'])){
			$name = $get['app'];
			self::appView($name);
			exit ;
			
		}
		
		$info = parse_url($name);
		self::pageView($info['path']);
		exit ;
	}

	static function notfound() {
		header("HTTP/1.0 404 Not Found");
		exit ;
	}
	
	static function appView($name){
		if (file_exists(CONTROLLER_PATH . $name . '.php')) {
			require_once (CONTROLLER_PATH . $name . '.php');
		}
		if (!file_exists(APP_PATH. $name)) {
			self::pageView('index');
		}
		require_once (APP_PATH. $name. '/index.php');
	}

	static function pageView($viewPageName){
		if (!file_exists(CONTROLLER_PATH . $viewPageName . '.php')) {
			$viewPageName = 'notfound';
		}
		require_once (CONTROLLER_PATH . $viewPageName . '.php');
		require_once (VIEW_PATH . '/common/header.php');
		require_once (VIEW_PATH . '/'. $viewPageName . '.php');
		require_once (VIEW_PATH . '/common/footer.php');
	}
	static function appendHeader($tag)
	{
		self::$additionalHeaders[] = $tag;
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

};
DispatchController::dispatch();
