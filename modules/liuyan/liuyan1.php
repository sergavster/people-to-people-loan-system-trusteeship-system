<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("liuyan_".$_t);//检查权限
$module_table = "liuyan_set";//附加表

$result = $module->get_module("liuyan");
if ($t=="install"){
	$list_purview =  array("liuyan"=>array("留言管理"=>array("liuyan_list"=>"用户列表","liuyan_reply"=>"回复留言","liuyan_set"=>"留言设置","liuyan_new"=>"添加留言","liuyan_edit"=>"修改留言")));//权限
}elseif ($result == false ){
	$msg = array("此模块尚未安装，请不要乱操作","",$url);
}else{

	$list_name = $result['name'];
	$list_menu = "<a href='{$url}/new{$site_url}'>添加留言</a> - <a href='{$url}{$site_url}'>留言列表</a> - <a href='{$url}/set{$site_url}'>留言设置</a> ";

	/**
	 * 如果类型为空的话则显示所有的文件列表
	**/
	if ($t == ""){
		$result = $mysql->db_list("liuyan");
		$magic->assign("result",$result['result']);
		$page->set_data(array('total'=>$result['num'],'perpage'=>$epage));
		$magic->assign("page",$page->show(3));
	}
	
	
	/**
	 * 留言设置
	**/
	elseif ($t == "set"){
		$result = $mysql->db_selects("liuyan_set");
		if ($result !=false){
			foreach ($result as $key => $value){
				$_result[$value['nid']] = $value['value'];
			}
		}else{
			die("安装错误");
		}
		$magic->assign("result",$_result);
		
	}
	elseif ($t == "set_ok"){
		$result = $mysql->db_selects("liuyan_set");
		if ($result !=false){
			foreach ($result as $key => $value){
				$_value = $_POST[$value['nid']];
				$mysql -> db_query("update {liuyan_set} set `value` = '".$_value."' where id=".$value['id']);
			}
		}
		$msg = array("修改设置成功");
	}
	
	/**
	 * 添加
	**/
	elseif ($t == "new" || $t == "edit" ){
		$result = $mysql->db_select("liuyan_set","nid='type'");
		$magic->assign("type_list",explode(",",$result['value']));
		if ($t == "edit"){
			$magic->assign("result",$mysql->db_select("liuyan","id=".$_REQUEST['id']));
		}
	}
	
	elseif ($t == "add" || $t == "update"){
		$var = array("title","name","email","tel","fax","company","address","type","status","content");
		$index = post_var($var);
		$pic_name = upload('litpic');
		if (is_array($pic_name)){
			$index['litpic'] = $pic_name[0];
		}
		
		if ($t == "update"){
			$result = $mysql->db_update("liuyan",$index,"id=".$_POST['id']);
		}else{
			$index['user_id'] = $_SESSION['user_id'];
			$result = $mysql->db_add("liuyan",$index);
		}
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			$msg = array("操作成功","","$url");
		}
		$user->add_log($_log,$result);//记录操作
	}
	/**
	 * 查看
	**/
	elseif ($t == "view"){
		$result = $mysql->db_select("liuyan","id=".$_REQUEST['id']);
		$magic->assign("result",$result);
	}
	/**
	 * 回复
	**/
	elseif ($t == "reply"){
		$index['reply'] = $_POST['reply'];
		$index['replytime'] = time();
		$index['replyip'] = ip_address();
		$index['reply_id'] = $_SESSION['user_id'];
		$result = $mysql->db_update("liuyan",$index,"id=".$_POST['id']);
		$msg = array("操作成功","","$url");
		$user->add_log($_log,$result);//记录操作
	}
	
	//删除
	elseif ($t == "del"){
		$result = $mysql->db_delete("liuyan","id=".$_REQUEST['id']);
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			$msg = array("删除成功");
		}
		$user->add_log($_log,$result);//记录操作
	}
}

?>