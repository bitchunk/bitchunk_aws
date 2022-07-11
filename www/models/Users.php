<?php
chdir(dirname(__FILE__));
chdir("../");
// require_once(MODEL_PATH. "filer.php");
// require_once(MODEL_PATH. "MysqlPDO.php");

//id, account, password,
// user_name, mail, twitter,
// last_login, create_date, remove_date, remove_flag
// require_once("./lib/php/randomString.php");
class Users extends DBModel
{
	var $tableName = 'users';
	var $primaryKey = 'user_id';
	const SALT = '$1$somersau$';//md5
	var $loginParams = array("account", "debug");
	var $message = array();
	
	function create($info, $fileto = false)
	{
		if($fileto == false && !$this->createCheck($info)){
			return false;
		}
		
		$this->info = array();
		// $this->info['id'] = count(glob($this::FILE_PATH. "*.usr"));
		//アカウント名（固定）：外部SNSからの名前など
		$this->info['account'] = !empty($info['user_name']) ? $info['user_name'] : '';
		// $this->info['password'] = str_replace($this::SALT,  "", crypt($info['password'], $this::SALT));
		
		//SNS認証するからいらないかも
		if($fileto == false){
			$this->info['password'] = !empty($info['password']) ?  str_replace($this::SALT,  "", crypt($info['password'], $this::SALT)) : '';
		}else{
			$this->info['password'] = !empty($info['password']) ?  $info['password'] : '';
		}
		
		//表示名変更可の予定
		$this->info['user_name'] = $this->info['account'];
		
		//メール（オプション）
		$this->info['mail'] = $info['mail'];
		
		//SNSのタイプ
		$this->info['sns_type'] = !empty($info['sns_type']) ? $info['sns_type'] : "twitter";
		
		//SNSのユーザーID
		$this->info['sns_id'] = !empty($info['sns_id']) ? $info['sns_id'] : "";
		
		//SNS認証の際のトークン
		$this->info['sns_token'] = !empty($info['sns_token']) ? $info['sns_token'] : "";
		
		//SNS認証の際の秘密鍵
		$this->info['sns_secret'] = !empty($info['sns_secret']) ? $info['sns_secret'] : "";
		
		//デバッグユーザー
		$this->info['debug'] = 0;
		
		//削除フラグ
		$this->info['remove_flg'] = 0;
		
		//更新日
		$this->info['update_date'] = date(DB_DATEFORMAT);
		
		//作成日
		$this->info['create_date'] = $this->info['update_date'];
		
		$res = $this->insert($this->info);
		if(!$res){
			$this->setlog('insert failed user_id: '. $user_id);
			return false;
		}
		return true;
	}

	function createWithSns($params)
	{
		$this->info = array();
		//アカウント名（固定）：外部SNSからの名前など
		$this->info['account'] = $params['account'];
		// $this->info['password'] = str_replace($this::SALT,  "", crypt($info['password'], $this::SALT));
		
		//SNS認証するからいらないかも
		$this->info['password'] = '';
		
		//表示名変更可の予定
		$this->info['user_name'] = $params['account'];
		
		//メール（オプション）
		$this->info['mail'] = '';//$info['mail'];
		
		//SNSのタイプ
		$this->info['sns_type'] = $params['sns_type'];
		
		//SNSのユーザーID
		$this->info['sns_id'] = $params['sns_id'];
		
		//SNS認証の際のトークン
		$this->info['sns_token'] = $params['sns_token'];
		
		//SNS認証の際の秘密鍵
		$this->info['sns_secret'] = $params['sns_secret'];
		
		//デバッグユーザー
		$this->info['debug'] = 0;
		
		//削除フラグ
		$this->info['remove_flg'] = 0;
		
		//更新日
		$this->info['update_date'] = date(DB_DATEFORMAT);
		
		//作成日
		$this->info['create_date'] = $this->info['update_date'];
		
		$res = $this->insert($this->info);
		if(!$res){
			$this->setlog('insert failed user_id: '. $user_id);
			return false;
		}
		return true;	}


	function loginFromSnsId($type, $sns_id){
		$sql = 'SELECT user_id, account, sns_id, debug FROM users WHERE sns_type = :sns_type AND sns_id = :sns_id AND remove_flg = 0 ';
		$result = $this->query($sql, array('sns_type' => $type, 'sns_id' => $sns_id));
		
		if(!$result){
			return false;
		}
		
		if(count($result) == 0){
			return false;
		}

		return $result[0];
	}

	function login($user, $pass){
		$sql = 'SELECT user_id, account, debug FROM users WHERE account = :account AND password = :password AND remove_flg = 0 ';
		$result = $this->query($sql, array('account' => $user, 'password' => str_replace($this::SALT,  "", crypt($pass, $this::SALT))));
		
		if(!$result){
			return false;
		}
		
		if(count($result) == 0){
			return false;
		}

		// if($result['password'] != str_replace($this::SALT,  "", crypt($pass, $this::SALT))){
			// return false;
		// }

		//$this->info = $this->distriArray($datastr);

		// $this->updateFromUserId($result, $params);
		return $result[0];
	}
		
	function loadFromAccount($account)
	{
		$sql = ' SELECT * FROM '. $this->tableName. ' WHERE account = ? AND remove_flg = 0 ';
		$res = $this->query($sql, $account);
		if($res === false){
			$this->setlog('load user account faled. account: '. $account);
			return false;
		}
		if(empty($res)){
			return null;
		}
		
		return $res[0];
	}

	function createcheck($info)
	{
		//空入力チェック
		if(empty($info['user_name'])){
			$this->message[] = "名前を入れてください";
		}
		if(empty($info['password'])){
			$this->message[] = "パスワードを入れてください";
		}
		if (strlen($info['password']) < 6) {
			$this->message[] = "";
		}
		
		if(count($this->message) > 0){
			return false;
		}
		
		//文字の正当性チェック
		if(!mb_ereg('^[a-zA-Z0-9_\!\?]+$', $info['password'])){
			$this->message[] = "パスワードに無効な文字が含まれています。";
		}
		if(!mb_ereg('^[a-zA-Z0-9_\-\+\*]+$', $info['user_name'])){
			$this->message[] = "名前に無効な文字が含まれています。";
		}
		if(count($this->message)){
			return false;
		}
		
		if(empty($info['password2'])){
			$this->message[] = "確認用パスワードを入れてください";
		}
		if(count($this->message)){
			return false;
		}
		if($this->isDuplicateUserName($info['user_name'])){
			$this->message[] = "その名前は使われています";
		}
		if($info['password2'] != $info['password']){
			$this->message[] = "パスワードが一致しません";
		}
		if(count($this->message)){
			return false;
		}
		return true;
	}
	
		
	function isDuplicateUserName($userName)
	{
		$sql = 'SELECT user_id, account FROM '. $this->tableName. ' WHERE account = ? '; 
		$res = $this->query($sql, $userName);
		if($res === false){
			$this->setlog('duplicate check failed user_name: '.$userName);
		}
		if(empty($res[0])){
			//重複なし
			return false;
		}
		return true;
	}
	
	function snsLogoin(){
	}
	
}
