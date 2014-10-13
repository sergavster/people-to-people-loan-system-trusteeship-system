<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("arealinks_".$_t);//检查权限
$sql_admin = "";
$admin_city = "";
$_sql_admin = "";
if (in_array("areaadmin_link",explode(",",$_SESSION['purview'])) && $_SESSION['usertype']==1){
	$sql = "select * from {user} where username = '".$_SESSION['username']."' ";
	$result = $mysql ->db_fetch_array($sql);
	$admin_city = $result['city'];
	if ($admin_city!=""){
	$_sql_admin = " where city =$admin_city";
	$sql_admin = " and city =$admin_city";
	}
}

//附注：如果全国性的话的
$sql = "select * from {xiuwcity} where area=$city_id";
$xiuwcity = $mysql->db_fetch_array($sql);

$result = $module->get_module("arealinks");
if ($result == false && $t!="install"){
	$msg = array("此模块尚未安装，请不要乱操作","",$url);
}else{

	/**
	 * 如果类型为空的话则显示所有的文件列表
	**/
	if ($t == ""){
		$result = $mysql->db_list_res("select * from {arealinks} $_sql_admin order by id desc",$p,$epage=10);
		$magic->assign("result",$result);
		$num = $mysql->db_count("arealinks");
		$page->set_data(array('total'=>$num,'perpage'=>$epage));
		$magic->assign("page",$page->show(3));
	}
	
	
	/**
	 * 安装此模块
	**/
	elseif ($t == "install"){
		
		/**
		 * 添加数据表
		**/
		$sql = file_get_contents("modules/arealinks/arealinks.sql");
		$mysql->db_querys($sql);
			
		
		/**获得模块info的信息
		**/
		$info = post_var("","module");
		$info['purview'] = serialize(array("arealinks"=>array("友情链接"=>array("arealinks_list"=>"友情链接列表","arealinks_new"=>"添加链接","arealinks_del"=>"删除链接","arealinks_type"=>"链接类型"))));
		
		if ($result == false){
			$module->add_module($info);
			$msg = array("安装成功，您可以继续返回使用此功能","",$url);
		}else{
			$msg = array("您已经安装了此模块，请勿重复添加。","",$url);
		}
		
		$user->add_log($_log,$result);//记录操作
	}
	
	/**
	 * 卸载此模块
	**/
	elseif ($t == "unstall"){
		$_result = $module -> unstall_module("arealinks","arealinks_type");
		$msg = array("您已经成功卸载了模块");
		$user->add_log($_log,$_result);//记录操作
		
	}
	
	/**
	 * 关闭此模块
	**/
	elseif ($t == "close"){
		$result = $module -> close_module("arealinks");
		$msg = array("您已经成功关闭了此模块，关闭后在内容添加的时候将不会看到此模块。");
		$user->add_log($_log,$result);//记录操作
	}
	
	/**
	 * 打开此模块
	**/
	elseif ($t == "open"){
		$result = $module -> open_module("arealinks");
		$msg = array("您已经成功开启了此模块，开启后在内容添加的时候将会看到此模块。");
		$user->add_log($_log,$result);//记录操作
	}
	
	
	/**
	 * 添加
	**/
	elseif ($t == "new" || $t == "edit" ){
		if ($t == "edit"){
			$result = $mysql->db_select("arealinks","id=".$_REQUEST['id']);
			if ($admin_city!="" && $admin_city!=$result['city']){
				$msg = array("你没有权限管理其他城市");
			}else{
			$magic->assign("result",$result);
			}
		}
	}
	
	elseif ($t == "add" || $t == "update"){
		if ($admin_city!="" && $admin_city!=$_POST['city']){
			$msg = array("你没有权限管理其他城市");
		}else{
		
			$var = array("area","status","order","url","webname","pr","email","province","city","summary","linkman","email");
			foreach ( $var as $val){
				$index[$val] = !isset($_POST[$val])?"":$_POST[$val];
			}
			$index["flag"] = !isset($_POST["flag"])?"":join(",",$_POST["flag"]);
			$pic_name = upload('logo');
			if (is_array($pic_name)){
				$index['logo'] = $pic_name[0];
			}
			if (isset($_POST['city']) && $_POST['city']!=""){
				$index['area'] = $_POST['city'];
			}else{
				$index['area'] = $_POST['province'];
			}
			
			if ($t == "update"){
				$result = $mysql->db_update("arealinks",$index,"id=".$_POST['id']);
			}else{
				$result = $mysql->db_add("arealinks",$index);
			}
			if ($result == false){
				$msg = array("输入有误，请跟管理员联系");
			}else{
				$msg = array("操作成功","返回上一页","$url",$msg_tpl);
			}
		}
		$user->add_log($_log,$result);//记录操作
	}
	
	/**
	 * 查看
	**/
	elseif ($t == "view"){
		$result = $mysql->db_select("arealinks","id=".$_REQUEST['id']);
		$magic->assign("result",$result);
	}
	
	
	/**
	 * 删除
	**/
	elseif ($t == "del"){
		$result = $mysql->db_select("arealinks","id=".$_REQUEST['id']);
		if ($admin_city!="" && $admin_city!=$result['city']){
			$msg = array("你没有权限管理其他城市");
		}else{
			$result = $mysql->db_delete("arealinks","id=".$_REQUEST['id']);
			if ($result == false){
				$msg = array("输入有误，请跟管理员联系");
			}else{
				$msg = array("删除成功");
			}
		}
		$user->add_log($_log,$result);//记录操作
	}
}


if ($msg!=""){
	$template_tpl = show_msg($msg,$msg_tpl);//如果是信息的则直接读取系统的信息模板
}else{
	$template_tpl = "arealinks.tpl";//如果是其他的，则直接读取模块所在的相应模板
	$magic->assign("template_dir","modules/arealinks/");
}

$magic->assign("module_tpl",$template_tpl);
?>