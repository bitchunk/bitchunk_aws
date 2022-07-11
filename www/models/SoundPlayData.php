<?php
chdir(dirname(__FILE__));
chdir("../");
// require_once("./lib/php/randomString.php");
/**
 * プレイヤーデータ
 */
class SoundPlayData extends DBModel
{
	var $tableName = 'soundplaydata';
	var $primaryKey = 'sound_id';
	
	function create($user_id, $data, $hidden = 0)
	{
		$this->info = array();
		$this->info['sound_id'] = null;
		$this->info['user_id'] = $user_id;
		$this->info['hidden'] = $hidden;
		$this->info['data'] = $data;
		$this->info['create_date'] = date(DB_DATEFORMAT);
		$this->info['update_date'] = date(DB_DATEFORMAT);
		$this->info['remove_flg'] = 0;
		//初期セット

		//$this->filename = $this->info['account'];
		$sql = 'INSERT INTO '. $this->tableName. ' ('. implode(', ', array_keys($this->info)). ') VALUES(' . $this->stmtKey($this->info). ')';
		$res = $this->query($sql, $this->info);
		if(!$res){
			$this->setlog('insert failed user_id: '. $user_id);
			return false;
		}
		return true;
	}
	
	function loadFromUserIdSoundId($user = 0, $sound = 0, $hidden = true)
	{
		$sql = 'SELECT S.*, U.user_name FROM '. $this->tableName. ' AS S '.
		' INNER JOIN users AS U on U.user_id = S.user_id AND U.remove_flg = 0 '.
		' WHERE S.user_id = :user_id AND S.sound_id = :sound_id AND S.remove_flg = 0 '.
		($hidden ? ' AND S.hidden = 0 ' : '').
		' limit 1 offset 0 '.
		'';
		$params = array('user_id' => $user, 'sound_id' => $sound);
		$res = $this->query($sql, $params);
		if($res === false){
			return false;
		}
		if(count($res) == 0){
			return null;
		}
		
		return $res[0];
	}
	
	
	function loadFromSoundIdByGuest($sound = 0)
	{
		$sql = 'SELECT * FROM '. $this->tableName. ' AS S '.
		' INNER JOIN users AS U on U.user_id = S.user_id AND U.remove_flg = 0 '.
		' WHERE S.sound_id = :sound_id AND S.remove_flg = 0 '.
		' AND S.hidden = 0 '.
		' limit 1 offset 0 '.
		'';
		$params = array('sound_id' => $sound);
		$res = $this->query($sql, $params);
		if($res === false){
			return false;
		}
		if(count($res) == 0){
			return null;
		}
		
		return $res[0];
	}
	
	/**
	* 
	*/
	
	function loadFromPackQueryIds($user_id, $pack_query = 0, $hidden = true)
	{
		$limit = 16;
		$sql = 'SELECT S.*, U.user_name FROM '. $this->tableName. ' AS S '.
		' INNER JOIN users AS U on U.user_id = S.user_id AND U.remove_flg = 0 '. 
		// ' WHERE S.user_id = :user_id AND S.sound_id IN ('. $pack_query. ') AND S.remove_flg = 0 '.
		' WHERE S.user_id = :user_id AND S.sound_id IN (:sound_id) AND S.remove_flg = 0 '.
		' AND S.hidden = 0 '.
		' limit '. $limit. ' offset 0 '.
		'';
		$params = array('user_id' => $user_id, 'sound_id' => $pack_query);
		$res = $this->query($sql, $params);
		if($res === false){
			return false;
		}
		if(count($res) == 0){
			return null;
		}
		
		return $res;
	}
	
	function loadFromPackQueryName($user_id, $pack_query, $hidden = true)
	{
		$limit = 16;
		$sql = 'SELECT S.*, U.user_name FROM '. $this->tableName. ' AS S '.
		' INNER JOIN users AS U on U.user_id = S.user_id AND U.remove_flg = 0 '. 
		' INNER JOIN packs AS P on P.user_id = S.user_id AND P.remove_flg = 0 AND P.pack_name = :pack_name '. 
		' WHERE S.user_id = :user_id AND S.remove_flg = 0 '.
		' AND S.hidden = 0 '.
		' limit '. $limit. ' offset 0 '.
		'';
		$params = array('user_id' => $user_id, 'pack_name' => $pack_query);
		$res = $this->query($sql, $params);
		if($res === false){
			return false;
		}
		if(count($res) == 0){
			return null;
		}
		
		return $res;
	}		
	function loadFromPackQueryAtHome($pack_query = 0, $hidden = true)
	{
		$limit = 16;
		$sql = 'SELECT S.*, U.user_name FROM '. $this->tableName. ' AS S '.
		' INNER JOIN users AS U on U.user_id = S.user_id AND U.remove_flg = 0 '. 
		// ' INNER JOIN permit_domain AS D on D.user_id = S.user_id AND D.remove_flg = 0 AND domain = :domain '. 
		' WHERE S.sound_id IN ('. $pack_query. ') AND S.remove_flg = 0 '.
		' AND S.hidden = 0 '.
		' limit '. $limit. ' offset 0 '.
		'';
		$params = array('sound_id' => $pack_query);
		// $res = $this->query($sql, $params);
		$res = $this->query($sql);
		if($res === false){
			return false;
		}
		if(count($res) == 0){
			return null;
		}
		
		return $res;
	}
		
	function loadFromPackQueryAtDomain($pack_query = '', $domain = '')
	{
		// $domain = HOST_NAME;
		$limit = 16;
		$sql = 'SELECTS.*, U.user_name FROM '. $this->tableName. ' AS S '.
		' INNER JOIN users AS U on U.user_id = S.user_id AND U.remove_flg = 0 '. 
		' INNER JOIN permit_domain AS D on D.user_id = S.user_id AND D.remove_flg = 0 AND domain = :domain '. 
		' WHERE S.sound_id IN (:sound_id) AND S.remove_flg = 0 '.
		' AND S.hidden = 0 '.
		' limit '. $limit. ' offset 0 '.
		'';
		$params = array('sound_id' => $pack_query, 'domain' => $domain);
		$res = $this->query($sql, $params);
		if($res === false){
			return false;
		}
		if(count($res) == 0){
			return null;
		}
		
		return $res;
		
	}
	
	function loadFileList($user = 0, $limit = 5, $offset = 0)
	{
		$sql = 'SELECT S.sound_id, U.user_id, S.title, S.update_date  FROM '. $this->tableName. ' AS S '.
		' INNER JOIN users AS U ON U.user_id = S.user_id '.
		' WHERE S.user_id = :user_id AND S.hidden = 0 AND S.remove_flg = 0 '.
		' limit '. $limit. ' offset '. $offset.
		'';
		$params = array('user_id' => $user);
		$res = $this->query($sql, $params);
		if($res === false){
			return false;
		}
		
		return $res;
	}
	//TODO カウントテーブル用意する
	function checkFileCount($user_id)
	{
		$sql =
		'SELECT '.
		'	 COALESCE((SELECT COUNT(S.sound_id) AS file_count FROM '. $this->tableName. ' AS S '.
		'	 WHERE S.user_id = :user_id AND S.remove_flg = 0), 0) AS file_count '.
		', '.
		'	 COALESCE((SELECT SUM(num) AS add_count FROM extensions AS EX '.
		'	 WHERE EX.user_id = :user_id AND EX.key = "sounddatafiles" AND EX.remove_flg = 0), '. DEFAULT_FILE_NUM. ') AS add_count '.
		'';
		
		$params = array('user_id' => $user_id);
		$res = $this->query($sql, $params);
		
		if($res === false){
			return false;
		}
		$rest = $res[0]['add_count'] - $res[0]['file_count'];
		if($rest <= 0){
			return false;
		}
		
		return $rest;
	}
	
	/**
	 * つかわない
	 */
	function saveData($params)
	{
		$require = array('user_id', 'data', 'id');
		$bind = array();
		foreach($require as $key){
			$bind[$key] = @$params[$key];
		}
		if(empty($bind['id'])){
			return $this->create($bind['user'], $bind['data']);
		}
		
		return $this->update($bind['id'], $bind);
	}

}
