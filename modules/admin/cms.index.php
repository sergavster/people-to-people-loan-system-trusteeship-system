<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
if (isset($_t)){
	check_rank($s."_".$_t);//检查权限
}

$result = $module->get_module($s);
if ($t=="install"){
	$list_purview = "";//权限
}elseif ($result == false ){
	$msg = array("此模块尚未安装，请不要乱操作","",$url);
}else{

	$list_name = $result['name'];
	$list_menu = "<a href='{$url}/new{$site_url}'>添加内容</a> - <a href='{$url}{$site_url}'>内容列表</a>";

	/**
	 * 如果类型为空的话则显示所有的文件列表
	**/
	if ($t == ""){
		if (isset($_REQUEST['id']) && isset($_REQUEST['status'])){
			$sql = "update {".$s."} set status='".$_REQUEST['status']."' where id = ".$_REQUEST['id'];
			$mysql->db_query($sql);
		}
		if (isset($_REQUEST['id']) && isset($_REQUEST['dflag'])){
			$sql = "select flag from {".$s."} where id = ".$_REQUEST['id'];
			$result = $mysql->db_fetch_array($sql);
			$flag = explode(",",$result['flag']);
			$_flag = array();
			foreach ($flag as $key => $value){
				if ($value!=$_REQUEST['dflag']){
					$_flag[] = $value;
				}
			}
			$sql = "update {".$s."} set flag='".join(",",$_flag)."' where id = ".$_REQUEST['id'];
			$mysql->db_query($sql);
		
		}
		$result = $mysql->db_selects("flag","","`order` desc");
		foreach($result as $key => $value){
			$flagres[$value['nid']] = $value['name'];
		}
		$result = $module->get_module_content($s,$p,$epage,$site_id);
		foreach ($result['result'] as $key => $value){
			$flag = $value['flag'];
			if ($flag !=""){
				$_flag = "";
				$flags = explode(",",$flag);
				foreach ($flags as $_key => $_value){
					$_flag .= "<a href='$url$site_url&id=".$value['id']."&dflag=$_value' title='点击取消此属性'>".$flagres[$_value]."</a> ";
				}
				$result['result'][$key]['flagname'] = $_flag;
			}
		}
		$magic->assign("list",$result['result']);
		$page->set_data(array('total'=>$result['num'],'perpage'=>$epage));
		$magic->assign("page",$page->show(3));
	}
	
	
	/**
	 * 添加
	**/
	elseif ($t == "new" || $t == "edit" ){
		$site_list = $module->get_site_li("",$s);
		$magic->assign("site_list",$site_list);
		
		if ($t == "edit"){
			$result = $module->view_module_content($s,$_REQUEST['id']);
		}else{
			$result = array("site_id"=>$site_id);
		}
		$magic->assign("result",$result);
		
		$res = $module->get_module($s);
		$_res = explode(",",$res['default_field']);
		foreach ($article_field as $key => $value){
			if (count($_res)>0 && in_array($key,$_res)){
				$_filed[$key] = true;
			}else{
				$_filed[$key] = false;
			}
		}
		$magic->assign("field",$_filed);
		
		$fields = $module->get_fields($s);
		$input = array();
		if (is_array($fields)){
			for($i=0;$i<count($fields);$i++){
				$nid = $fields[$i]['nid'];
				if ($nid!=""){
					$fun = "input_".$fields[$i]['input'];
					if ($t == "edit"){
						$_default = $result[$nid];
					}else{
						$_default = $fields[$i]['default'];
					}
					$input[$i] = array($fields[$i]['name'],$fun($nid,$_default,$fields[$i]['select']));	
				}
			}
		}
		$magic->assign("input", $input);
	}
	
	/**
	 * 查看
	**/
	elseif ($t == "view"){
		$result = $module->view_module_content($s,$_REQUEST['id']);
		$magic->assign("result",$result);
		
		$res = $module->get_module($s);
		$_res = explode(",",$res['default_field']);
		
		foreach ($article_field as $key => $value){
			if (count($_res)>0 && in_array($key,$_res)){
				$_filed[$key] = true;
			}else{
				$_filed[$key] = false;
			}
		}
		$magic->assign("field",$_filed);
		
		$fields = $module->get_fields($s);
		$input = array();
		if (is_array($fields)){
			for($i=0;$i<count($fields);$i++){
				$nid = $fields[$i]['nid'];
				if ($nid!=""){
					$fun = $fields[$i]['input'];
					$val = $result[$nid];
					if ($fun == "image"){
						$val = "<a href='$val' target='_blank'> <img src='$val' width='100' border=0 ></a>";
					}elseif ($fun == "annex"){
						$val = "<a href='$val' target='_blank'>附件下载</a>";
					}
					$input[$i] = array($fields[$i]['name'],$val);	
				}
			}
		}
		$magic->assign("input", $input);
	}
	
	/**
	 * 添加和修改操作
	**/
	elseif ($t == "add" || $t == "update"){
		$var = array("title","is_jump","jumpurl","summary","content","source","flag","author","publish","order","status","site_id","province","city","area");
		$index = post_var($var);
		$index['area'] = post_area();
		$pic_name = upload('litpic');
		if (is_array($pic_name)){
			$index['litpic'] = $pic_name[0];
		}
		
		$fields = "";
		$_fields = $module->get_fields($s);
		if (is_array($_fields)){
			foreach($_fields as $key => $value){
				$fields[$value['nid']] = empty($_POST[$value['nid']])?"":$_POST[$value['nid']];
			}	
		}
		
		if ($t == "update"){
			$result = $module->update_module_content($s,$index,$fields,$_POST['id']);
		}else{
			$index['user_id'] = $_SESSION['user_id'];
			$result = $module->add_module_content($s,$index,$fields);
		}
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			$msg = array("操作成功","",$url.$site_url);
		}
		$user->add_log($_log,$result);//记录操作
	}
	
	/**
	 * 删除操作
	**/
	elseif ($t == "del"){
		$result = $module->del_module_content($s,$_REQUEST['id']);
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			$msg = array("删除成功！","返回上一页");
		}
		$user->add_log($_log,$result);//记录操作
	}

}

?>