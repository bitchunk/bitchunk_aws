<?php
class DBModel
{
	var $db;
	var $list;
	var $info;
	var $log;
	
	var $encode;
	var $tableName; // テーブル名、継承先で指定
	var $primaryKey; // 主キー、継承先で指定、基本クエリメソッドで使用
	
	public function __construct()
	{
		$this->db = getDBA();
		$this->log = new logBase(get_class($this));
		$this->encode = DB_ENCODE;
	}
	
	function query($sql, $params = array())
	{
		$res = $this->db->query($sql, $params);
		return $res;
	}
	
	function begin()
	{
		$this->db->beginTransaction();
	}
	
	function commit()
	{
		$this->db->commit();
	}
	
	function rollBack()
	{
		$this->db->rollBack();
	}
	
	function getListAll()
	{
		$this->list = $this->query('SELECT * FROM '. $this->tableName. ' WHERE remove_flg = 0');
		// var_dump($this->list, $this->tableName);
		if($this->list === false){
			// var_dump($this->list);
			$this->setlog('getList Faild');
			return false;
		}
		// var_dump($this->list);
		return $this->list;
	}
	
	function loadFromUserId($user_id)
	{
		$res = $this->query('SELECT * FROM '. $this->tableName. ' WHERE user_id = ? ', $user_id);
		if(!$res){
			$this->setlog('load faild user_id: '. $user_id);
			return false;
		}
		if(count($res) == 0){
			return null;
		}
		return $res[0];
	}
	
	function loadFromUserIdIndex($user_id, $index, $remove_flg = null)
	{
		$params = array();
		$params [] = $user_id;
		if(is_null($remove_flg)){
			$removesql = '';
		}else{
			$removesql = ' AND remove_flg = ? ';
			$params[] = $remove_flg;
		}
		$res = $this->query('SELECT * FROM '. $this->tableName. ' WHERE user_id = ? '. $removesql. ' LIMIT 1 OFFSET '. $index , $params);
		if(!$res){
			$this->setlog('load faild user_id: '. $user_id);
			return false;
		}
		if(count($res) == 0){
			return null;
		}
		return $res[0];
	}
	
	function loadListFromUserId($user_id)
	{
		// $map = $this->db->query('SELECT * FROM '. $this->tableName. ' WHERE user_id = ? AND remove_flg = 0 ', $user_id);
		$map = $this->query('SELECT * FROM '. $this->tableName. ' WHERE user_id = ? AND remove_flg = 0 ', $user_id);
		// var_dump($map);
		if($map === false){
			$this->setlog('load faild user_id: '. $user_id);
			return false;
		}
		return $map;
	}
	
	/**
	 * use primary id
	 */
	function loadFromId($id)
	{
		$res = $this->query('SELECT * FROM '. $this->tableName. ' WHERE '. $this->primaryKey. ' = ? ', $id);
		
		if(!$res){
			$this->setlog('load faild '. $this->primaryKey. ': '. $id);
			return false;
		}
		if(count($res) == 0){
			return null;
		}
		return $res[0];
	}
	
	/**
	 * 行挿入（主キーをいれなければ最後に追加）
	 */
	function insert($params)
	{
		$sql = 'INSERT INTO '. $this->tableName. ' ('. implode(', ', array_keys($params)). ') VALUES(' . $this->stmtKey($params). ')';
		$res = $this->query($sql, $params);
		return $res;
	}
	
	function update($id, $params)
	{
		$params[$this->primaryKey] = $id;
		$params['update_date'] = isset($params['update_date']) ? $params['update_date'] : date(DB_DATEFORMAT);
		$updstr = $this->updateStr($params);
		$res = $this->db->query('UPDATE '. $this->tableName. ' SET '. $updstr. ' WHERE '. $this->primaryKey. ' = :'. $this->primaryKey , $params);
		// var_dump('UPDATE '. $this->tableName. ' SET '. $updstr. ' WHERE '. $this->primaryKey. ' = :'. $this->primaryKey , $params);
		return $res;
	}
	
	function updateFromUserId($id, $params)
	{
		$params['user_id'] = $id;
		$updstr = $this->updateStr($params);
		$res = $this->db->query('UPDATE '. $this->tableName. ' SET '. $updstr. ' WHERE user_id = :user_id' , $params);
		return $res;
	}
	
	function updateStr($params)
	{
		$res = array();
		foreach($params as $key=>$value){
			$res[$key] = $key. ' = :'. $key;
		}
		return implode(', ', $res);
		
	}
	
	function stmtKey($keyedArray)
	{
		$res = array();
		foreach($keyedArray as $key=>$value){
			$res[$key] = ':'. $key;
		}
		// return implode(', ', $res);
		return implode(', ', $res);
	}
	
	function setlog($message)
	{
		$this->log->put($message);
	}
	
}
