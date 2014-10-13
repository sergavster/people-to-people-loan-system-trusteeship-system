<?
/******************************
 * $File: module.php
 * $Description: 模块类处理文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/

if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问

/**
 * 如果模块为空的话则显示出文件夹里所有的模块
**/

if ($s == "uploadimg" || $s == "uploadannex"){
	include_once("upload.php");
}
?>