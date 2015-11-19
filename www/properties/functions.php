<?php
function template($tmp_name = null, $args = array()){
	$path = TEMPLATE_PATH. '/'. $tmp_name. '.php';
	if(!file_exists($path)){
		echo 'no Templates';
		return;
	}
	require $path;
}

function isURL($path)
{
	return preg_match("/^https?\:\/\/.*/", $path);
}
?>