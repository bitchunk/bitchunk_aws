<?php
chdir(dirname(__FILE__));
chdir("../");
require_once("AjaxServerBase.php");

require_once(MODEL_PATH. "SoundPlayData.php");
//function updatePlayData($user, $method, $paramname, $data)
/**
 * サウンドファイルを読み出し
 */
function response()
{
	$db = new SoundPlayData();
	$userTable = new Users();
	$ajaxServ = new AjaxServerBase();
	$params = $ajaxServ->getParams('get');
	if(!isset($params['sound_id'])){
		$ajaxServ->sendErrorJson(DBERROR_SOUND_ID_INVALID, 'sound_id invalid');
		return;
	}
	
	if(empty($params['user_id'])){
		loadByGuest($params['sound_id']);
		return;
	}else{
		loadByOwner($params['user_id'], $params['sound_id']);
		return;
		
	}
	return;
	
	$res = $userTable->loadFromUserId($params['user_id']);
	if($res === false || is_null($res)){
		$ajaxServ->sendErrorJson(DBERROR_USER_NOTFOUND, 'user_id invalid');
		return;
	}
	//TODO SESSIONログインチェックするべき
	$login_id = $params['user_id'];
	if($login_id == $params['user_id']){
		$private = false;
	}else{
		$private = true;
	}
	$res = $db->loadFromUserIdSoundId($params['user_id'], $params['sound_id']);
	if($res === false || is_null($res)){
		$ajaxServ->sendErrorJson(DBERROR_USER_PERMISSION, 'user_id invalid');
		return;
	}
	//test
	$ajaxServ->sendJson($res);
	
}

function loadByGuest($sound_id)
{
	$db = new SoundPlayData();
	$ajaxServ = new AjaxServerBase();
	
	$res = $db->loadFromSoundIdByGuest($sound_id);
	if($res === false || is_null($res)){
		$ajaxServ->sendErrorJson(DBERROR_FILE_NOTFOUND, 'sound not found');
		return;
	}
	$ajaxServ->sendJson($res);
	
}

function loadByOwner($user_id, $sound_id)
{
	$db = new SoundPlayData();
	$ajaxServ = new AjaxServerBase();
	$userTable = new Users();
	
	$user = getUser();
	
	//ユーザーチェック
	$res = $userTable->loadFromUserId($user_id);
	if($res === false || is_null($res)){
		$ajaxServ->sendErrorJson(DBERROR_USER_NOTFOUND, 'user_id invalid');
		return;
	}
	
	//オーナーチェックチェック（なければ普通のロード）
	if($user_id !== @$user['user_id']){
		$db->loadFromId($sound_id);
		// $ajaxServ->sendErrorJson(DBERROR_USER_ID_INVALID, 'user logout');
		return;
	}	
	$user_id = @$user['user_id'];
	
	$res = $db->loadFromUserIdSoundId($user_id, $sound_id);
	if($res === false || is_null($res)){
		$ajaxServ->sendErrorJson(DBERROR_USER_PERMISSION, 'user_id invalid');
		return;
	}
	$ajaxServ->sendJson($res);
	
}




response();


exit;
