<?php
//$user = $_SESSION['user'];
chdir(dirname(__FILE__));
chdir("../properties/");
define('IS_AJAX', true);
define("REDIRECT_TOP_THROUGH", true);
require_once ("env_common.php");
require_once ("env_db.php");
// require_once(MODEL_PATH. "LogBase.php");
require_once (MODEL_PATH . "Users.php");
require_once (MODEL_PATH . "SoundPlayData.php");
require_once (MODEL_PATH . "AllowURLs.php");

class AjaxServerBase {
	var $log = null;

	public function __construct() {
		$this -> log = new logBase(get_Class($this));
	}

	function getParams($method = 'request', $keys = null) {
		$params = array();
		switch($method) {
			case 'request' :
				$params = $_REQUEST;
				break;
			case 'post' :
				$params = $_POST;
				break;
			case 'get' :
				$params = $_GET;
				break;
		}
		if (!is_null($keys)) {
			foreach ($keys as $key) {
				$res[] = $params[$key];
			}
		} else {
			$res = $params;
		}
		return $res;
	}
	
	function checkReferer()
	{
		$hostparse = @parse_url($_SERVER['HTTP_REFERER']);
		$query = empty($hostparse['query']) ? "" : $hostparse['query'];
		if(empty($_SERVER['HTTP_REFERER']) || strstr($query, "sound_id") != false ){
			$ajaxServ->sendErrorJson(APIERROR_REFQUERY_REJECTED, 'reject refaccess', '[refer/query]'. (empty($hostparse['query']) ? @$_SERVER['HTTP_REFERER'] : $hostparse['query']));
			return false;
		}
		
		return true;
	}
	
	function checkedUserParams($postMethod = 'get')
	{
		$params = $this->getParams($postMethod);
		$user = getUser();
		
		if($params['user_id'] !== @$user['user_id']){
			$this->sendErrorJson(DBERROR_USER_ID_INVALID, 'user logout');
			return false;
		}
		$params['user_id'] = @$user['user_id'];
		return $params;
	}

	function sendJson($params) {
		$j = json_encode($params);
		echo $j;
		$this -> log -> append($j);
		$this -> log -> write();
	}

	function sendErrorJson($error_code = "99999", $message = "", $hint = "") {
		$params = array('error_code' => $error_code, 'message' => $message);
		echo json_encode($params);
		$this -> log -> put('e: ' . $error_code . ', m: ' . $message . ', h:' . $hint);
	}
	
	function allowOriginHeader($user_id)
	{
		$arrow = new AllowURLs();
		$url = $this->parseURLOrigin($_SERVER['HTTP_REFERER']);
		
		$host = $this->parseURLOriginHost($_SERVER['HTTP_REFERER']);
		$thisHost = $this->parseURLOriginHost(PROTOCOL_HOST);
		
		$this -> log -> put($user_id. ' '. $url);
			// header('Access-Control-Allow-Origin: '. $url);
			// header('Access-Control-Allow-Credential: true');
		if($arrow->isAllowUrl($user_id, $url) || $host == $thisHost){
		$url = trim($url, '/');
			header('Access-Control-Allow-Origin: '. $url);
			header('Access-Control-Allow-Credentials: true');
			return true;
		}else{
			return false;
		}
	}
	
	function parseURLOrigin($url){
		$parse = @parse_url($url);
		$basic = !empty($parse['user']) ? $parse['user']. ':'. $parse['pass']. '@' : '';
		$port = !empty($parse['port']) ? ':'. $parse['port'] : '';
		
		$url = $parse['scheme']. '://'. $basic. $parse['host']. $port. $parse['path'];
		
		$url = trim($url, '/');
		return $url;
	}
	
	function parseURLOriginHost($url){
		$parse = @parse_url($url);
		$basic = !empty($parse['user']) ? $parse['user']. ':'. $parse['pass']. '@' : '';
		$port = !empty($parse['port']) ? ':'. $parse['port'] : '';
		
		$url = $parse['scheme']. '://'. $basic. $parse['host']. $port;
		
		$url = trim($url, '/');
		return $url;
	}

}