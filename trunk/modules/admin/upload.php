<?php
/******************************
 * $File: upload.php
 * $Description: 图片和附件上传
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问

if(isset($_POST['action']) && $_POST['action']=='img'){
	$fileType=array('jpg','gif','bmp','png');//允许上传的文件类型
	$upfileDir='data/upfiles/images/';
	$_upfileDir='data/upfiles/images/';
	$maxSize=1024; //单位：KB
	if(!in_array(strtolower(substr($_FILES['file1']['name'],-3,3)),$fileType))
		die("<script>alert('不允许上传该类型的文件！-808');window.parent.\$('divProcessing').style.display='none';history.back();</script>");
	if(strpos($_FILES['file1']['type'],'image')===false)
		die("<script>alert('不允许上传该类型的文件！');window.parent.\$('divProcessing').style.display='none';history.back();</script>");
	if($_FILES['file1']['size']> $maxSize*1024)
		die( "<script>alert('文件过大！');window.parent.\$('divProcessing').style.display='none';history.back();</script>");
	if($_FILES['file1']['error'] !=0)
		die("<script>alert('未知错误，文件上传失败！');window.parent.$('divProcessing').style.display='none';history.back();</script>");
	
	$targetFile=date('Ymd').time().substr($_FILES['file1']['name'],-4,4);
	$realFile=$upfileDir.$targetFile;
	
	if(function_exists('move_uploaded_file')){
		 move_uploaded_file($_FILES['file1']['tmp_name'],$realFile) && die("<script>window.parent.LoadIMG('$_upfileDir$targetFile');</script>");
	}
	else{
		@copy($_FILES['file1']['tmp_name'],$realFile) && die("<script>window.parent.LoadIMG('$_upfileDir$targetFile');</script>");
	}
}
if(isset($_POST['action']) && $_POST['action']=='annex'){
	$fileType=array('doc','rar','xls','ppt','pps','pdf','txt');//允许上传的文件类型
	
	$upfileDir='data/upfiles/annexs/';
	$_upfileDir='data/upfiles/annexs/';
	$maxSize=5500; //单位：KB
	if(!in_array(strtolower(substr($_FILES['file1']['name'],-3,3)),$fileType))
		die("<script>alert('不允许上传该类型的文件！-808');window.parent.\$('divProcessing').style.display='none';history.back();</script>");
	if($_FILES['file1']['size']> $maxSize*1024)
		die( "<script>alert('文件过大！');window.parent.\$('divProcessing').style.display='none';history.back();</script>");
	if($_FILES['file1']['error'] !=0)
		die("<script>alert('未知错误，文件上传失败！');window.parent.$('divProcessing').style.display='none';history.back();</script>");
	
	$targetFile=date('Ymd').time().substr($_FILES['file1']['name'],-4,4);
	$realFile=$upfileDir.$targetFile;
	
	if(function_exists('move_uploaded_file')){
		 move_uploaded_file($_FILES['file1']['tmp_name'],$realFile) && die("<script>window.parent.LoadIMG('$_upfileDir$targetFile');</script>");
	}
	else{
		@copy($_FILES['file1']['tmp_name'],$realFile) && die("<script>window.parent.LoadIMG('$_upfileDir$targetFile');</script>");
	}
}
?>


<html>
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
		<title><? if ($s == "uploadimg"){?>插入图片<? }else{?>插入附件 <? }?></title>
		<style type="text/css">
			body, td, span, div, input {
				font-size: 12px;
				font-family: "宋体", "Courier New", Courier, monospace;
				margin: 0px;
			}
		</style>
		<script language="JavaScript">
		<!--
		window.isIE = (navigator.appName == "Microsoft Internet Explorer");
		if(window.isIE) {
			if(navigator.userAgent.indexOf("Opera")>-1) window.isIE = null;
		}
		else {
			if(navigator.userAgent.indexOf("Gecko")==-1) window.isIE = null;
		}
		function $(sID) {
			return document.getElementById(sID);
		}
		function adjustDialog(){
			var w = $("tabDialogSize").offsetWidth + 6;
			var h = $("tabDialogSize").offsetHeight + 25;
			window.dialogLeft = (screen.availWidth - w) / 2;
			window.dialogTop = (screen.availHeight - h) / 2;
		}
		window.onload = init;
		function init () {
			adjustDialog();
			//$("imgpath").select();
		}
		function LoadIMG(imgpath){
		   oRTE = window.dialogArguments.document.getElementById('<? echo $_REQUEST['id'];?>');
			if(window.isIE) {
				try{
					oRTE.value =imgpath;
				}
				catch(e){}
			}
			else {
				oRTE.value =imgpath;
			}
			window.close();
		}
		function chk_imgpath () {
		  if($('radio1').checked==true){
			if($("imgpath").value == "http://" || $("imgpath").value == "") {
				window.close();
				return;
			}
			LoadIMG($("imgpath").value);
		  }else{
		    if($("file1").value == "") {
			   window.close();
			   return;
		    }
		    $('form1').submit();
			$('divProcessing').style.display='';
		  }
		}
		document.onkeydown = function (el) {
			var event = window.event || el;
			if(event.keyCode == 13) {
			    chk_imgpath();
			}  
		}
		//-->
		</script>
	</head>
	<body>
	<? if ($s == "uploadimg"){?>
	<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" id="tabDialogSize">
    <form name="form1" id="form1" method="post" action="?<? echo $query_site?>&q=plugins/uploadimg&id=<? echo $_REQUEST['id'];?>" enctype="multipart/form-data" target="myiframe">
    <tr>
      <td height="24" bgcolor="#DDE7EE" style="padding-left: 10px;">插入缩略图图片</td>
    </tr>
    <tr>
      <td height="50"><input type="radio" name="picurl" id="radio1" hidefocus="true" onClick="if(this.checked==true){$('imgpath').disabled='';$('file1').disabled='disabled';}">&nbsp;图片地址:
      <input type="text" value="http://" style="border: 1px solid #999999; width: 235px;" id="imgpath" name="imgpath" disabled="disabled"></td>
    </tr>
    <tr height="30"> 
      <td align="left" id="upid" width="400"><input type="radio" id="radio2" name="picurl" hidefocus="true" onClick="if(this.checked==true){$('imgpath').disabled='disabled';$('file1').disabled='';}" checked>&nbsp;上传图片: 
        <input type="file" name="file1" id="file1" style="width:300px; border:#999999 1px solid">
      </td>
    </tr>
    <tr>
      <td style="padding: 10px;">&nbsp;</td>
    </tr>
    <tr>
      <td height="40" align="center" style="padding-bottom: 10px;"><input type="button" value=" 确 认 " onClick="chk_imgpath()"> <input type="button" value= " 取 消 " onClick="window.close();"></td>
    </tr><tr><td bgcolor="#DDE7EE" height="5"></td>
    </tr>
	<input type="hidden" value="img" name="action"></form>
	</table>
<div id=divProcessing style="width:200px;height:30px;position:absolute;left:85px;top:75px;display:none">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#333333" width="100%" height="100%">
  <tr>
    <td bgcolor="#3A6EA5" align="center"><font color=#FFFFFF>图片上传中,请等待...</font></td>
  </tr>
</table>
<? }elseif ($s=="uploadannex"){?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" id="tabDialogSize">
    <form name="form1" id="form1" method="post" action="?<? echo $query_site?>&q=plugins/uploadannex&id=<? echo $_REQUEST['id'];?>" enctype="multipart/form-data" target="myiframe">
    <tr>
      <td height="24" bgcolor="#DDE7EE" style="padding-left: 10px;">插入附件</td>
    </tr>
    <tr>
      <td height="50"><input type="radio" name="picurl" id="radio1" hidefocus="true" onClick="if(this.checked==true){$('imgpath').disabled='';$('file1').disabled='disabled';}">&nbsp;附件地址:
      <input type="text" value="http://" style="border: 1px solid #999999; width: 235px;" id="imgpath" name="imgpath" disabled="disabled"></td>
    </tr>
    <tr height="30"> 
      <td align="left" id="upid" width="400"><input type="radio" id="radio2" name="picurl" hidefocus="true" onClick="if(this.checked==true){$('imgpath').disabled='disabled';$('file1').disabled='';}" checked>&nbsp;上传附件: 
        <input type="file" name="file1" id="file1" style="width:300px; border:#999999 1px solid">
      </td>
    </tr>
    <tr>
      <td style="padding: 10px;">&nbsp;</td>
    </tr>
    <tr>
      <td height="40" align="center" style="padding-bottom: 10px;"><input type="button" value=" 确 认 " onClick="chk_imgpath()"> <input type="button" value= " 取 消 " onClick="window.close();"></td>
    </tr><tr><td bgcolor="#DDE7EE" height="5">
	<input type="hidden" value="annex" name="action"></td>
    </tr></form>
	</table>
<div id=divProcessing style="width:200px;height:30px;position:absolute;left:85px;top:75px;display:none">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#333333" width="100%" height="100%">
  <tr>
    <td bgcolor="#3A6EA5" align="center"><font color=#FFFFFF>附件上传中,请等待...</font></td>
  </tr>
</table>
</div>
<? }?>
</div>
<iframe src="../upload.php" width="0" height="0" name="myiframe" id="myiframe" frameborder="0" scrolling="no"></iframe>
	</body>
</html>