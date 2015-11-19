<?php
//ファイル支援
//require_once(dirname(dirname(__FILE__)). "/properties/defines.php");
class filer
{
	var $filepath = "";
	var $extension = "";
	var $filename = "";
	var $filenameKey = "";

	var $info = array();
	var $message = array();

	/**
	 * '/' が追加される
	 */
	public function setFile($path = null, $ext = null)
	{
		$this->filepath = $path. "/";
		$this->extension = $ext;
	}

	function isDuplicate($name)
	{
		if(file_exists($this->filepath. $name. $this->extension)){
			return true;
		}else{
			return null;
		}
	}

	/**
	 *
	 * ファイル保存
	 * @param string infoのキー $infoKey
	 * @param string 名前とか $opt
	 */
	function save($infoKey = null, $opt = null)
	{
		//$name = $this->filename;
		$name = $this->info[$this->filenameKey];
		if(!empty($infoKey)){
			$name = $this->info[$infoKey];
		}else if(!empty($opt)){
			$name = $opt;
		}

		if(!isset($name)){
			return;
		}

		if(array_key_exists('update_date', $this->info)){
			$this->info['update_date'] = date("Y-m-d H:i:s");
		}

		$path = $this->filepath. $name. $this->extension;
		$res = file_put_contents($path, $this->buildString($this->info), LOCK_EX);
		
		if($res === false){
			$count = 5;
			while($count--){
				usleep(200000);
				$res = file_put_contents($path, $this->buildString($this->info), LOCK_EX);
				if($res !== false){break;}
			}
		}
	}

	/**
	 *
	 * 読み込み
	 * @param String $name
	 * @return boolean 成功
	 */
	function load($name)
	{
		$path = $this->filepath. $name. $this->extension;
		if(!file_exists($path)){
			return false;
		}
		$datastr = file_get_contents($path);
		if($datastr === false){
			$count = 5;
			while($count--){
				usleep(200000);
				$datastr = file_get_contents($path);
				if($datastr !== false){break;}
				
			}
		}
		
		$this->info = $this->distriArray($datastr);
		$this->cleanInfo();//filenameも通る
// var_dump($this->info);
		return true;
	}
	
	function getAll($extention)
	{
		$files = glob($this->filepath. '*');
		$data = array();
		foreach($files as $file){
			$name = basename($file, $extention);
			$this->load($name);
			$data[] = $this->info;
		}
		return $data;
	}
	

	/**
	 *
	 * 新しい配列に更新
	 */
	function cleanInfo(){
		$backupInfo = $this->info;
		$this->create($this->info);
		foreach($backupInfo as $key=>$info){
			if(array_key_exists($key, $this->info)){
				$this->info[$key] = $info;
			}
		}
	}

	/**
	 *
	 * 連想配列をキー・値をCSVしてさらにTSV
	 * @param array $array
	 * @return string
	 */
	function buildString($array)
	{
		$buildstr = "";
		$keydatArray = array();
		foreach ($array as $key=>$dat){
			if(is_array($dat)){
				$keydatArray[] = $key. ",". implode(",", $dat);
			}else{
				$keydatArray[] = $key. ",". $dat;
			}
		}
		$buildstr = implode("\t",$keydatArray);
		return $buildstr;
	}

	/**
	 *
	 * CSVinTSVに連想配列
	 * @param string CSVinTSV $str
	 * @return array
	 */
	function distriArray($str)
	{
		$distri = array();

		$valueKeyArray = explode("\t", $str);
		$distri = array();
		foreach($valueKeyArray as $valueKey){
			$data = explode(",", $valueKey);
			$key = array_shift($data);

			//空文字チェック
			$newdata = array();
			foreach($data as $d){
				if($d != ""){
					$newdata[] = $d;
				}
			}
			$data = $newdata;

			$data = implode(",", $data);

			//配列です
			/*			if(strpos($key, "s", strlen($key) -1 ) != false){
			if(!is_array($data)){
			$notarray = $data;
			$data = array();
			echo $key ."  "; var_dump($notarray);
			if(!isset($notarray)){
			$data[] = $notarray;
			}
			}
			}*/
			$distri[$key] = $data;
		}

		return $distri;
	}

	function setMessage($str)
	{
		$this->message[] = str;
	}
	function getMessage()
	{
		return $this->message;
	}

	function getFileCount()
	{
		$cnt = count(glob(DATA_PATH. $this::FILEDIR. "/*". $this::EXTENSION));
		return $cnt;
	}


}
