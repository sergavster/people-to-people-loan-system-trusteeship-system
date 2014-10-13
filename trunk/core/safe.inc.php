<?php
/******************************
 * $File: safe.php
 * $Description: ���ݴ���ȫ���
 * $Author: 
 * $Time:2013-03-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
$referer=empty($_SERVER['HTTP_REFERER']) ? array() : array($_SERVER['HTTP_REFERER']);
$getfilter="'|\\b(and|or)\\b.+?(>|<|=|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$postfilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$cookiefilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";

/* ����ת���ַ� */
function safe_str($str){
	if(!get_magic_quotes_gpc())	{
		if( is_array($str) ) {
			foreach($str as $key => $value) {
				$str[$key] = safe_str($value);
			}
		}else{
			$str = addslashes($str);
		}
	}
	return $str;
}
$request_uri = explode("?",$_SERVER['REQUEST_URI']);
if(isset($request_uri[1])){
	$rewrite_url = explode("&",$request_uri[1]);
	foreach ($rewrite_url as $key => $value){
		$_value = explode("=",$value);
		if (isset($_value[1])){
		$_GET[$_value[0]] = $_REQUEST[$_value[0]] = addslashes(urldecode($_value[1]));
		}
	}
}
function StopAttack($StrFiltKey,$StrFiltValue,$ArrFiltReq){
	$StrFiltValue=arr_foreach($StrFiltValue);
	if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue, $arr)==1){

        slog("\r\n����IP: ".$_SERVER["REMOTE_ADDR"]."\r\n����ʱ��: ".strftime("%Y-%m-%d %H:%M:%S")."\r\n����ҳ��:".$_SERVER["PHP_SELF"]."\r\n�ύ��ʽ: ".$_SERVER["REQUEST_METHOD"]."\r\n�ύ����: ".$StrFiltKey."\r\n�ύ����: ".$StrFiltValue);
        ob_start();
		ob_get_clean();
		ob_clean();
		print "<div style=\"position:fixed;top:0px; color:red;font-weight:bold;border-bottom:5px solid #999;\"><br>�����ύ���в��Ϸ�����,лл����!<br> </div>";
		die("���ּ�ⷢ�ֿ�������  �����ڶܰ�ȫ��ʿ");
		exit();
	}
	if (preg_match("/".$ArrFiltReq."/is",$StrFiltKey)==1){
 
        slog("\r\n����IP: ".$_SERVER["REMOTE_ADDR"]."\r\n����ʱ��: ".strftime("%Y-%m-%d %H:%M:%S")."\r\n����ҳ��:".$_SERVER["PHP_SELF"]."\r\n�ύ��ʽ: ".$_SERVER["REQUEST_METHOD"]."\r\n�ύ����: ".$StrFiltKey."\r\n�ύ����: ".$StrFiltValue);
        ob_start();
		ob_get_clean();
		ob_clean();
		print "<div style=\"position:fixed;top:0px;  color:red;font-weight:bold;border-bottom:5px solid #999;\"><br>�����ύ���в��Ϸ�����,лл����!<br> </div>";
        die("���ּ�ⷢ�ֿ�������  �����ڶܰ�ȫ��ʿ");
		exit();
	}  
}

$get = array('page','epage','site_id','id','borrow_id','user_id','salesman');
foreach ($_GET as $key=>$value){
	if(in_array($key, $get)){
		$_GET[$key]=intval($value);
	}
}
if(isset($_GET['salesman'])){
	$_GET['salesman'] = 1;//ҵ��Ա��־����������˱���Ϊ1
}

foreach($_GET as $k=>$v){
	$_GET[$k] = safe_str(urldecode($v));
	StopAttack($k,$_GET[$k],$getfilter);
}
foreach($_POST as $k=>$v){
	$_POST[$k] = safe_str($v);
	
	if($k=='contentadmin' && isset($_GET['q']) && ($_GET['q']=='site/edit' || $_GET['q']=='module/article/new')){
		continue;
	}
	StopAttack($k,$_POST[$k],$getfilter);
}
foreach($_REQUEST as $k=>$v){
	$_REQUEST[$k] = safe_str(urldecode($v));
	if($k=='contentadmin' && isset($_GET['q']) && ($_GET['q']=='site/edit' || $_GET['q']=='module/article/new')){
		continue;
	}
	StopAttack($k,$_REQUEST[$k],$getfilter);
}
/*
foreach(array('_GET','_POST','_COOKIE','_REQUEST') as $key) {
	if (isset($$key)){
		foreach($$key as $_key => $_value){
			$$key[$_key] = safe_str($_value);
			$_value=urldecode($_value);
			StopAttack($_key,$_value,$getfilter);
		}
	}
	
}*/
/* д��safe��־/log/safe.log */
function slog($logs)
{
	$toppath=ROOT_PATH_SAFE;
	$Ts=fopen($toppath,"a+");
	fputs($Ts,$logs."\r\n/*---------------------------------------*/");
	fclose($Ts);
}

/* �и����� */
function arr_foreach($arr) {
	static $str;
	if (!is_array($arr)) {
	return $arr;
	}
	foreach ($arr as $key => $val ) {

	if (is_array($val)) {

		arr_foreach($val);
	} else {

	  $str[] = $val;
	}
	}
	return implode($str);
}

/* �ϴ��ļ��ļ�� */
function safe_file(){
	$not_allow_file = "php|asp|jsp|aspx|cgi|php3|shtm|html|htm|shtml";
	foreach ($_FILES as $key=>$value){
		$_name = $_FILES[$key]['name'];
		if (is_array($_name)){
			foreach($_name as $key){
				if ( !empty($key) && (eregi("\.(".$not_allow_file.")$",$key) || !ereg("\.",$key)) ){
					//die("�������ϴ�������");		
				}
			}
		}else{
			if ( !empty($_name) && (eregi("\.(".$not_allow_file.")$",$_name) || !ereg("\.",$_name)) ){
				//die("�������ϴ�������");		
			}
		}
	}
}
//safe_file();
?>