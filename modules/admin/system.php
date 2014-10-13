<?
/******************************
 * $File: system.php
 * $Description: 系统管理
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问


include_once(ROOT_PATH."core/system.class.php");


$_A['list_name'] = "系统管理";

if ($_A['query_class']=="list"){
	$_A['query_class'] = "user";
}
/**
 * 用户信息和密码修改
**/
if ($_A['query_class'] == "user"){
	$_A['list_title'] = "修改个人信息";
	include_once(ROOT_PATH."core/user.class.php");
	if (!isset($_POST['user_id'])){
		$_A['user_result'] = $user->GetOne(array("user_id"=>$_G['user_id']));
	}else{
		$var = array("email","realname","sex","qq","wangwang","tel","phone","address");
		$data = post_var($var);
		$data['user_id'] = $_G['user_id'];
		if (isset($_POST['password']) && $_POST['password'] != ""){
			$data['password'] = $_POST['password'];
			$result = $user->CheckUsernamePassword($data);
			if ($result  == false){
				$msg = array("原密码不正确");
			}else{
				$data['password'] = $_POST['password1'] ;
			}
		}
		if ($msg==""){
			$result = $user->UpdateUser($data);
			if ($result!==true){
				$msg = array($result);
			}else{
				$msg = array("信息修改成功");
			}
		}
	}
}

/**
 * 系统基本信息设置
**/
elseif ( $_A['query_class']== "info"){
	check_rank("system_info");//检查权限
	$_A['list_title'] = "系统参数";
	$_A['list_menu'] = "<a href='{$_A['query_url']}'>参数管理</a> | <a href='{$_A['query_url']}/new'>添加参数</a>";
	if ($_A['query_type'] == "list"){
		if (isset($_POST['value'])){
			$data['value'] = $_POST['value'];
			$data['class'] = "action";
			$data['style'] = "1";
			$result = systemClass::ActionSystem($data);
			$p = systemClass::createParameter_system();
			if ($result==true && $p>0){
				$msg = array("操作成功");
			}else{
				$msg = array($result);
			}
		}else{
			$data['class'] = "list";
			$data['style'] = "1";
			$_A['system_list'] = systemClass::ActionSystem($data);
		}
	}elseif ($_A['query_type'] == "new" || $_A['query_type'] == "edit"){
		if (isset($_POST['name'])){
			$var = array("name","nid","status","type","style");
			$data = post_var($var);
			$data['class'] = "add";
			if ($_A['query_type'] == "edit"){
				$data['id'] = $_REQUEST['id'];
				$data['class'] = "update";
			}
			if ($data['type']==0 || $data['type']==3){
				$data['value'] = $_POST['value1'];
			}else{
				$data['value'] = $_POST['value2'];
			}
			$result = systemClass::ActionSystem($data);
			$p = systemClass::createParameter_system();;
			if ($result===true && $p>0){
				$msg = array("操作成功");
			}else{
				$msg = array($result);
			}
			$user->add_log($_log,$result);//记录操作
		}elseif($_A['query_type'] == "edit"){
			$data['id'] = $_REQUEST['id'];
			$data['class'] = "view";
			$data['style'] = "1";
			$result = systemClass::ActionSystem($data);
			$_A['system_result'] = $result;
			/*if ($result['status']==0){
				$msg = array("此参数不能修改");
			}else{
				$_A['system_result'] = $result;
			}*/
		}
	}
	elseif ($_A['query_type'] == "action"){
		$data['value'] = $_POST['value'];
		$data['class'] = "action";
		$data['style'] = "1";
		$result = systemClass::ActionSystem($data);
		if ($result['status']==0){
			$msg = array("此参数不能修改");
		}else{
			$_A['system_result'] = $result;
		}
	
	}
	elseif ($_A['query_type'] == "del"){
		$result = $mysql->db_delete("system","id=".$_REQUEST['id']." and status=1");
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			$msg = array("删除成功");
		}
		systemClass::createParameter_system();
		$user->add_log($_log,$result);//记录操作
	}
}

/**
 * 图片水印设置
**/
elseif ($_A['query_class']== "watermark"){
	check_rank("system_watermark");//检查权限
	$list_name = "图片水印设置";
	$result = $mysql->db_selects("system","style=2");
	if ($result == false){
		$sql = "INSERT INTO `{system}` (`name` ,`nid` ,`value` ,`type` ,`style` ,`status`)VALUES ('上传的图片是否使用图片水印功能', 'con_watermark_pic', '0', '0', '2', '1'),
		( '采集的图片是否使用图片水印功能', 'con_watermark_caijipic', '0', '0', '2', '1'),
		( '选择水印的文件类型', 'con_watermark_type', '0', '0', '2', '1'),
		( '水印的字体', 'con_watermark_font', '', '0', '2', '1'),
		( '水印图片文件名', 'con_watermark_file', '0', '0', '2', '1'),
		( '水印图片文字字体大小', 'con_watermark_size', '20', '0', '2', '1'),
		( '水印图片文字颜色', 'con_watermark_color', '#FF0000', '0', '2', '1'),
		( '水印文字', 'con_watermark_word', '', '0', '2', '1'),
		( '水印位置', 'con_watermark_position', '4', '0', '2', '1'),
		( '添加图片水印后质量参数', 'con_watermark_imgpct', '0', '0', '2', '1'),
		( '添加文字水印后质量参数', 'con_watermark_txtpct', '0', '0', '2', '1');";
		$mysql->db_query($sql);
	}else{
		foreach ($result as $key => $value){
			$_result[$value['nid']] = $value['value'];
		}
		$magic->assign("result",$_result);
	}
	if (isset($_POST['con_watermark_pic'])){
		$var = array("con_watermark_pic","con_watermark_caijipic","con_watermark_type","con_watermark_font","con_watermark_color","con_watermark_txtpct","con_watermark_imgpct","con_watermark_word","con_watermark_size","con_watermark_position");
		$index = post_var($var);
		$pic_name = upload('con_watermark_file');
		if (is_array($pic_name)){
			$index['con_watermark_file'] = $pic_name[0];
		}
		foreach ($index as $key => $value){
			$sql = "update {system} set `value` = '".$value."' where nid='".$key."'";
			$mysql->db_query($sql);
		}
		$msg = array("信息修改成功");
	}
}


/**
 * 附件设置
**/
elseif ($_A['query_class']== "fujian"){
	check_rank("system_fujian");//检查权限
	$list_name = "附件设置";
	$result = $mysql->db_selects("system","style=3");
	
	if ($result == false){
		$sql = "INSERT INTO `{system}` (`name` ,`nid` ,`value` ,`type` ,`style` ,`status`)VALUES ( '缩略图是否裁切', 'con_fujian_imgstatus', '', '0', '3', '1'),
		('缩略图默认宽度', 'con_fujian_imgwidth', '', '0', '3', '1'),
		( '缩略图默认高度', 'con_fujian_imgheight', '', '0', '3', '1'),
		( '上传图片截取宽度', 'con_fujian_picwidth', '', '0', '3', '1'),
		( '上传图片截取高度', 'con_fujian_picheight', '', '0', '3', '1'),
		( '允许上传的图片类型', 'con_fujian_imgtype', '', '0', '3', '1'),
		( '允许上传的软件类型', 'con_fujian_annextype', '', '0', '3', '1'),
		( '允许的多媒体文件类型', 'con_fujian_mediatype', '', '0', '3', '1')
		;";
		$mysql->db_query($sql);
	}else{
		foreach ($result as $key => $value){
			$_result[$value['nid']] = $value['value'];
		}
		$magic->assign("result",$_result);
	}
	if (isset($_POST['con_fujian_imgwidth'])){
		$var = array("con_fujian_imgwidth","con_fujian_imgstatus","con_fujian_imgheight","con_fujian_picwidth","con_fujian_picheight","con_fujian_imgtype","con_fujian_annextype","con_fujian_mediatype");
		$index = post_var($var);
		foreach ($index as $key => $value){
			$sql = "update {system} set `value` = '".$value."' where nid='".$key."'";
			$mysql->db_query($sql);
		}
		$msg = array("信息修改成功");
	}
}


/**
 * 数据库备份和还原
**/
elseif ($_A['query_class']== "dbbackup"){
	check_rank("system_dbbackup");//检查权限
	$_A['list_title'] = "数据库备份还原";
	$_A['list_menu'] = "<a href='{$_A['admin_url']}&q=system/dbbackup/back'>数据备份</a> - <a href='{$_A['admin_url']}&q=system/dbbackup/revert'>数据还原</a> ";
	$filedir = "data/dbbackup";
	//add by weego 20120703
			die("关闭备份还原功能！");

	//数据库的备份
	if ($_A['query_type'] == "back"){
		die("关闭备份功能！");
		if (isset($_POST['name'])){
			$table = $_POST['name'];
			$size = $_POST['size'];
			if ($table==""){
				$msg = array("请选择表进行备份");
			}else if ($size<50 || $size >2000){
				$msg = array("<font color=red>备份大小必须在50k和2000k之间</font>");
			}else{
				/*
				 *删除文件里的内容并重新创建文件
				*/
				del_file($filedir);
				mk_dir($filedir);
				$_SESSION['dbbackup']['table'] = $table;
				$_SESSION['dbbackup']['size'] = $size;
				
				$data['table'] = $_SESSION['dbbackup']['table'];
				$data['size'] = $_SESSION['dbbackup']['size'];
				$data['tid'] = isset($_REQUEST['tid'])?$_REQUEST['tid']:0;
				$data['limit'] = isset($_REQUEST['limit'])?$_REQUEST['limit']:0;
				$data['filedir'] = $filedir;
				$data['table_page'] = isset($_REQUEST['table_page'])?$_REQUEST['table_page']:0;
				$result = systemClass::BackupTables($data);
				if ($result!=""){
					echo "正在备份（".$data['table'][$data['tid']]."）表 第 （{$data['limit']}） 条数据，请不要关闭浏览器。。。";
					$url = $_A['query_url']."/back&tid={$result['tid']}&limit={$result['limit']}&table_page={$result['table_page']}";
					echo "<script>location.href='{$url}';</script>";
					exit;
				}else{
					include_once(ROOT_PATH."core/pclzip.class.php");
					$archive = new PclZip('dbback.zip');
					$v_list = $archive->create('data/dbbackup');
					if ($v_list == 0) {
						die("Error : ".$archive->errorInfo(true));
					}
					$msg = array("备份成功");
				}
			}
		}elseif (isset($_REQUEST['tid'])){
			$data['table'] = $_SESSION['dbbackup']['table'];
			$data['size'] = $_SESSION['dbbackup']['size'];
			$data['tid'] = isset($_REQUEST['tid'])?$_REQUEST['tid']:0;
			$data['limit'] = isset($_REQUEST['limit'])?$_REQUEST['limit']:0;
			$data['filedir'] = $filedir;
			$data['table_page'] = isset($_REQUEST['table_page'])?$_REQUEST['table_page']:0;
			$result = systemClass::BackupTables($data);
			if ($result!=""){
				echo "正在备份（".$data['table'][$data['tid']]."）表 第 （{$data['limit']}） 条数据，请不要关闭浏览器。。。";
				$url = $_A['query_url']."/back&tid={$result['tid']}&limit={$result['limit']}&table_page={$result['table_page']}";
				echo "<script>location.href='{$url}';</script>";
				exit;
			}else{
				include_once(ROOT_PATH."core/pclzip.class.php");
				$archive = new PclZip(ROOT_PATH.'data/dbbackup/dbbackup.zip');
				$v_list = $archive->create('data/dbbackup');
				if ($v_list == 0) {
					die("Error : ".$archive->errorInfo(true));
				}

				$msg = array("备份成功","",$_A['query_url']."/back");
			}
		}else{
		
			$_result = systemClass::GetSystemTables();
			$magic->assign("result",$_result);
		
			
		}
	}
	else if($_A['query_type']=="show"){
		$table =$_REQUEST['table'];
		$sql = "show create table $table";
		$result = $mysql->db_fetch_array($sql);
		echo $result['Create Table'];
		exit;
	}elseif ($_A['query_type'] == "revert"){
		if (isset($_REQUEST['nameid'])){
			$data['nameid'] = $_REQUEST['nameid'];
			$data['filedir'] = $filedir;
			$data['table'] = $_SESSION['dbbackup']['vtable'];
			$result = systemClass::RevertTables($data);
			if ($result!=""){
				$nameid= $data['nameid']+1;
				echo "正在还原（".$result."）表 数据，请不要关闭浏览器。。。";
				$url = $_A['query_url']."/revert&nameid={$nameid}";
				echo "<script>location.href='{$url}';</script>";
				exit;
			}else{
				if($_SESSION['dbbackup']['delfile']!=""){
					del_dir($data['filedir']);
				}
				$msg = array("还原成功","",$_A['query_url']."/revert");
			}
		
		}elseif (isset($_POST['name'])){
			$show =!isset($_POST['show'])?"":$_POST['show'];
			$_SESSION['dbbackup']['delfile'] = !isset($_POST['delfile'])?"":$_POST['delfile'];
			$_SESSION['dbbackup']['vtable'] = !isset($_POST['name'])?"":$_POST['name'];
			if ( file_exists(ROOT_PATH.$filedir."/show_table.sql")){
				$sql = file_get_contents(ROOT_PATH.$filedir."/show_table.sql");
				$_sql = explode("\r\n",$sql);
				foreach ($_sql as $val){
					if ($val!=""){
						
						$mysql->db_query($val);
					}
				}
			}
			
			
			$url = $_A['query_url']."/revert&nameid=0";
			echo "<script>location.href='{$url}';</script>";
			exit;
				
			
			$msg = array("还原成功");
		}else{
			$result = get_file($filedir,"file");
			$magic->assign("result",$result);
		}
	}elseif ($_A['query_type'] == "revertok"){
		$show =!isset($_REQUEST['show'])?"":$_REQUEST['show'];
		$delfile = !isset($_REQUEST['delfile'])?"":$_REQUEST['delfile'];
		$table = !isset($_REQUEST['name'])?"":$_REQUEST['name'];
		if ($table!=""){
			if (file_exists($filedir."/show_table.sql")){
				$sql = file_get_contents($filedir."/show_table.sql");
				$_sql = explode("\r\n",$sql);
				foreach ($_sql as $val){
					if ($val!=""){
						$mysql->db_query($val,"true");
					}
				}
			}
			
			foreach($table as $key => $value){
				if ($value!="show_table.sql"){
					$sql = file_get_contents($filedir."/".$value);
					$_sql = explode("\r\n",$sql);
					foreach ($_sql as $val){
						if ($val!=""){
							$mysql->db_query($val,"true");
						}
					}
				}
			}
			if($delfile!=""){
				del_dir($filedir);
			}
			$msg = array("还原成功");
		}else{
			$msg = array("请选择要还原的字段");
		}
		
	}
	if ( !isset ($msg) || $msg==""){
		$template_tpl = "admin_dbbackup.html.php";
	}
}
/**
 * 手机管理
**/
elseif ($_A['query_class']== "phone"){
		if($_POST){
			$user_online = ROOT_PATH."core/phone.inc.php"; //保存人数的文件
			touch($user_online);//如果没有此文件，则创建
			if(strlen($_POST['phone_pass'])<>32) $_POST['phone_pass']=md5($_POST['phone_pass']);
			$new_date="<?php\r\n";
			$new_date.="\$_phone_username='".$_POST['phone_uid']."';\r\n";
			$new_date.="\$_phone_userpass='".$_POST['phone_pass']."';\r\n";
			$new_date.="\r\n?>";
		//写入文件
			$fp = fopen($user_online,"w");
			flock($fp,LOCK_EX); //flock() 不能在NFS以及其他的一些网络文件系统中正常工作
			fputs($fp,$new_date);
			flock($fp,LOCK_UN);
			fclose($fp);
			$msg = array("修改手机参数成功","",$_A['query_url']."/phone");
		}else{
			require_once(ROOT_PATH."core/phone.inc.php");
			$magic->assign("uid",$_phone_username);
			$magic->assign("pass",$_phone_userpass);
			$template_tpl = "admin_phone.html.php";
		}
}


/**
 * 自定义文档
**/
elseif ($_A['query_class']== "flag"){
	check_rank("system_flag");//检查权限
	$list_name = "自定义属性 ";
	if ($_A['query_type']=="order"){
		$result = $mysql->db_order("flag",$_REQUEST['order'],"id",$_REQUEST['id']);
		$msg = array("修改排序成功");
	}elseif ($_A['query_type']=="add"){
		$var = array("name","nid","order");
		$index = post_var($var);
		$result = $mysql->db_add("flag",$index,true);
		$msg = array("添加成功");
	}elseif ($_A['query_type']=="del"){
		if (isset($_REQUEST['id']) && $_REQUEST['id']>3){
			$result = $mysql->db_delete("flag","id=".$_REQUEST['id']);
			$msg = array("删除成功");
		}else{
			$msg = array("删除失败");
		}
	}else{
		$result = $mysql->db_selects("flag","","`order` desc");
		$magic->assign("result",$result);
	}
	$template_tpl = "admin_flag.html.php";
}

/**
 * 管理记录
**/
elseif ($_A['query_class']== "userlog"){
	check_rank("system_userlog");//检查权限
	$data['page'] = $_A['page'];
	$data['epage'] = 25;
	$data['username']  = isset($_REQUEST['username'])?$_REQUEST['username']:"";
	$data['quer']  = isset($_REQUEST['quer'])?$_REQUEST['quer']:"";
	$result = systemClass::GetUserLog($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['userlog_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
	$template_tpl = "admin_userlog.html";
}

/**
 * 图片管理
**/
/*
elseif ($_A['query_class']== "upfiles"){
	check_rank("system_userlog");//检查权限
	$data['page'] = $_A['page'];
	$data['epage'] = 40;
	$data['username']  = isset($_REQUEST['username'])?$_REQUEST['username']:"";
	$data['quer']  = isset($_REQUEST['quer'])?$_REQUEST['quer']:"";
	$result = systemClass::GetUpfiles($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['upfiles_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
	$template_tpl = "admin_upfiles.html.php";
}
*/
/**
 * 用户缓存信息管理
**/
/*
elseif ($_A['query_class']== "cache"){
	check_rank("system_userlog");//检查权限
	$data['page'] = $_A['page'];
	$data['epage'] = 40;
	$data['username']  = isset($_REQUEST['username'])?$_REQUEST['username']:"";
	$data['quer']  = isset($_REQUEST['quer'])?$_REQUEST['quer']:"";
	$result = systemClass::GetUpfiles($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['upfiles_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
	$template_tpl = "admin_upfiles.html.php";
}
*/
/**
 * 生成网站
**/
elseif ($_A['query_class']== "makehtml"){
	check_rank("system_makehtml");//检查权限
	$list_name = "生成网站";
	include_once("makehtml.php");

}

/**
 * 清空缓存
**/
elseif ($_A['query_class']== "clearcache"){
	check_rank("system_clearcache");//检查权限
	if ($_A['query_type']=="do"){
		del_file("data/compile");
		$msg = array("缓存已清空");
	}
}

/**
 * 邮箱设置
**/
elseif ($_A['query_class'] == 'email') {
	check_rank("system_email");//检查权限
	$list_name = "邮件设置";
	$style = 4;
	$result = $mysql->db_selects("system","style={$style}");
	if (!$result) {
		$sql = "INSERT INTO `{system}` (`name` ,`nid` ,`value` ,`type` ,`style` ,`status`)VALUES
					('SMTP服务器', 'con_email_host', '', '0', '{$style}', '0'),
					('SMTP服务器是否需要验证', 'con_email_auth', '1', '1', '{$style}', '0'),
					('邮箱地址', 'con_email_email', '', '0', '{$style}', '0'),
					('邮箱密码', 'con_email_pwd', '', '0', '{$style}', '0'),
					('发件人Email', 'con_email_from', '', '0', '{$style}', '0'),
					('发件人昵称或姓名', 'con_email_from_name', '', '0', '{$style}', '0')";
		$mysql->db_query($sql);
	}
    if (isset($_POST['value'])) {
        $value = isset($_POST['value'])?$_POST['value']:array();
        $sql = array();
        foreach ($value as $key => $var) {
            array_push($sql, "set value='{$var}' where style='{$style}' and nid='{$key}'");
        }
        $sql = 'update {system} ' . implode(';update {system} ', $sql) . ';';
        $mysql->db_querys($sql);
		systemClass::createParameter_system();
        $msg = array('操作成功');
    }
    else{
        $result = $mysql->db_fetch_arrays("select * from {system} where style={$style}");
        $magic->assign('result', $result);
        $template_tpl = "admin_email.html.php";
    }
}
/*
 * 生成parameter
 */
elseif ($_A['query_class'] == 'parameter') {
	if ($_A['query_type']=="do"){
		$p = systemClass::createParameter_system();//admin/system.php
		$p_1 = systemClass::createParameter_site();//admin/site.php
		$p_2 = systemClass::createParameter_biaotype();//biaotype/biaotype.php,fee/fee.php,links/links.php,scrollpic/scrollpic.php
		$p_3 = systemClass::createParameter_area();//area/area.php
		$p_4 = systemClass::createParameter_linkage();//linkage/linkage.php
		if($p>0 && $p_1>0 && $p_2>0 && $p_3>0 && $p_4>0){
			$p+=$p_1+$p_2+$p_3+$p_4;
			$msg = array("操作成功,操作{$p}个字节");
		}else{
			$msg = array("操作失败");
		}
	}
	$template_tpl = "admin_clearcache.html";
}
/**
 * 如果是其他模块则读取其他模块的配置文件
**/
else{
	$msg = array("您输入有误");
}

if($template_tpl!=""){
	$magic->assign("html_template",$template_tpl);
}else{
	$magic->assign("html_template","admin_".(empty($_A['query_class'])?'user':$_A['query_class']).".html");
}
$template = "admin_system.html.php";
?>