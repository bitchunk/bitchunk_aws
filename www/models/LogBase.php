<?php
chdir(dirname(__FILE__));
chdir("../");
require_once(MODEL_PATH. "filer.php");

class logBase extends filer
{
	// 	const FILE_PATH = "./log/";
	const FILE_PATH = LOG_PATH;
	const EXTENSION = ".csv";
	var $filestr;
	var $logArray;
	var $reqstr;
	var $result = "faild";
	var $date = "";

	public function __construct($name)
	{
		$this->setFile($this::FILE_PATH, $this::EXTENSION);
		$this->filenameKey = 'account';//ファイル名を参照する配列キー
		$this->filename = "log". date("Y_m_d");
		$this->ymdir = "log". date("Y_m");
		$this->filepath .= $name. "/";
		
		//その名前のフォルダ
		if(!is_dir($this::FILE_PATH)){
			mkdir($this::FILE_PATH);
		}
		//その名前のフォルダ
		if(!is_dir($this->filepath)){
			mkdir($this->filepath);
		}
		//年月フォルダ
		$this->ymdir = "log". date("Y_m");
		$this->filepath .= $this->ymdir. "/";
		if(!is_dir($this->filepath)){
			mkdir($this->filepath);
		}

		//読み込み
		// $path = $this->filepath. $this->filename. $this->extension;
		// if(!file_exists($path)){
			// $datastr = "";
		// }else{
			// $datastr = file_get_contents($path);
		// }
// 
		// //パース
		// $this->logArray = array();
		// if(!empty($datastr)){
			// $logArray = explode("\n", $datastr);
			// foreach($logArray as $line){
				// $this->logArray[] = explode("\t", $line);
			// }
		// }else{
			// $this->logArray[] = $this->create();
		// }
		// 		var_dump($logArray);
	}

	function create()
	{
		$this->info = array();
		$this->info['date'] = 'date';
		$this->info['request'] = "request";

		return $this->info;
	}


	function append($request){
			if(!is_array($request)){return;}
			if(!isset($request)){return;}
			foreach($request as $key=>$req){
			if(strlen($req) > 64){
				$request[$key] = $key. ":". substr($req, 0, 64);
			}else{
				$request[$key] = $key. ":". $req;
			}
		}
		$this->info['request'] = implode(",", $request);

		$this->logArray[] = $this->info;
	}
	
	function put($str)
	{
		$this->setResult($str);
		$path = $this->filepath. $this->filename. $this->extension;
		file_put_contents($path, $this->date. "\t". $this->result. "\n", LOCK_EX | FILE_APPEND);
		
		$logArray = array();
	}

	function setResult($result)
	{
		$this->result = $result;
		$this->date = date("Y-m-d H:i:s");
	}
	
	function pass()
	{
		$this->setResult('PASS');
	}

	function write()
	{
		$path = $this->filepath. $this->filename. $this->extension;
		$lineArray = array();
		if(empty($this->logArray)){
			return;
		}
		foreach($this->logArray as $logLine){
			$lineArray[] = implode("\t", $logLine);
		}
		file_put_contents($path, $this->date. "\t". $this->result. "\t". implode("\n", $lineArray). "\n", LOCK_EX | FILE_APPEND);
	}
}
