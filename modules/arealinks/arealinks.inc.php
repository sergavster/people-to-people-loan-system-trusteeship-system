<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
include_once("arealinks.class.php");


//前台的
if ($_G['query_type'] == "list"){	
	$result =arealinksClass::GetList($data);
	$_G['magic_result'] = $result;
}


?>
