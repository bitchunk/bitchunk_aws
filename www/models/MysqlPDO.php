<?php
chdir(dirname(__FILE__));
chdir("../");
require_once("./properties/env_db.php");
/**
 * DBAccess
 */
class MysqlPDO extends PDO{
	function query($sql, $params = array()) {

		if(!is_array($params)){
			$binds = array($params);
		}else{
			$binds = $params;
		}
		$keys = array();
		$values = array();
// 		$finds = array("type" => array(), "value" => array(), ":key" => array()); //位置でkeyを記憶
		
		$stmt = $this->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		// var_dump($stmt);
		$setparams = array();

		if($stmt->execute($binds)){
			if((strstr($sql, 'UPDATE') === false) && (strstr($sql, 'SELECT') || strstr($sql, 'DESC') != false)){
				$rowset = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}else{
				$rowset = $this->lastInsertId();
				// $rowset = true;
			}
		}else{
			// echo 1;
			$rowset = false;
		}
		// var_dump($rowset);
		// $stmt->closeCursor();
		$stmt->closeCursor();
		unset($stmt);
		$stmt=null;

		return $rowset;
	}

	function get_fieldstmt($stmt)
	{
		$fields = array();
		$result = $stmt->result_metadata();
		$f_fields = $result->fetch_fields();
		$fields = array();
		foreach($f_fields as $val){
			$fields[] = $val->name;
		}
		$result->free();
		return $fields;
	}
	
	function getFields($table_name)
	{
		$sql = "DESC ". $table_name;
		$res = $this->query($sql);
// 		$rowset = $result->fetch_all(MYSQLI_ASSOC);
		$fields = array();
		foreach($res as $val){
			$fields[] = $val['Field'];
		}
		return $fields;
	}
	
	function getNewRow($table_name)
	{
		$f = $this->getFields($table_name);
		array_fill_keys($f, null);
	}
	
	function isFalse($result)
	{
		if(empty($result)){
			return true;
		}else{
			return false;
		}
	}
}

class BCDBA{
	var $host = DB_HOST;
	var $user = DB_USER;
	var $password = DB_PASSWORD;
	var $name = DB_NAME;
	var $encode = "utf8";
	var $mysql;
	var $log;

	public static function exception_handler($exception) {
		// Output the exception details
		die('Uncaught exception: '. $exception->getMessage());
	}

	function __construct(){
		$this->log = new LogBase('BCDBA');
		if(defined('PDO::MYSQL_ATTR_INIT_COMMAND')){
			$command = PDO::MYSQL_ATTR_INIT_COMMAND;
		}else{
			//v5.3.0対応
			$command = 1002;
		}
		$options = array(
				$command => 'SET NAMES '. $this->encode,
		);
// 		$this->mysql = new mysqli_ten($this->host, $this->user, $this->password, $this->name);
		try {
			$this->mysql = @new MysqlPDO('mysql:host='. $this->host .';dbname='. $this->name, $this->user, $this->password, $options);
		}catch(PDOException $e){
			$this->log->put($e->getMessage());
			if(mb_check_encoding($e->getMessage(), $this->encode)){
				exit($e->getMessage());
			}else{
				exit('ERROR_CODE:'. $e->getCode(). '【サーバーに接続できませんでした】');
			}
		}
		$this->mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->mysql->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
		
		set_exception_handler(array(__CLASS__, 'exception_handler'));
		restore_exception_handler();
	}

}

/**
 *
 * DBに接続、インスタンスしたmysqliクラスを取得
 * @return mysqli
 */
function getDBA(){
	$dba = new BCDBA();
// 	var_dump($dba);
	return $dba->mysql;
}
