<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
if (isset($_t)){
	check_rank($s."_".$_t);//检查权限
}

$result = $module->get_module($s);
if ($t=="install"){
	$list_purview = array("help"=>array("添加帮助"=>array("help_list"=>"帮助列表","help_new"=>"添加帮助","help_del"=>"删除帮助","help_type"=>"帮助类型","help_type_new"=>"添加帮助类型","help_type_edit"=>"修改帮助类型")));//权限
}elseif ($result == false ){
	$msg = array("此模块尚未安装，请不要乱操作","",$url);
}else{

	$list_name = $result['name'];
	$list_menu = "<a href='{$url}/new{$site_url}'>添加帮助</a> - <a href='{$url}{$site_url}'>帮助列表</a> - <a href='{$url}/type{$site_url}'>帮助类型</a> - <a href='{$url}/type_new{$site_url}'>添加类型</a>";


 	if ($t == "type_edit"){
		$sql = "select * from {help_type} where pid!=".$_REQUEST['id'];
	}else{
		$sql = "select * from {help_type} ";
	}
	$result = $mysql -> db_fetch_arrays($sql);
	$i=0;
	foreach ($result as $key => $value){
		if ($value['pid']==0){
			$list[$i] = $value;
			$list[$i]['aname'] = "<b>".$value['name']."</b>";
			$list[$i]['ppid'] = 1;
			$i++;
			foreach ($result as $_key => $_value){
				if ($_value['pid']==$value['id']){
					$list[$i] = $_value;
					$list[$i]['aname'] = "-".$_value['name'];
					
					$i++;
					foreach ($result as $__key => $__value){
						if ($__value['pid']==$_value['id']){
							$list[$i] = $__value;
							$list[$i]['aname'] = "--".$__value['name'];
							$i++;
						}
					}
				}
			}
		}
	}
	$magic -> assign("list",$list);
	
	
	/**
	 * 如果类型为空的话则显示所有的文件列表
	**/
	if ($t == ""){
		$sql = "select p1.*,p2.name as type_name from {help} as p1 left join {help_type} as p2 on p1.type_id=p2.type_id";
		$result = $mysql->db_fetch_arrays($sql);
		
		$magic->assign("result",$result);
		$sql = "select count(*) as num from {help} as p1 left join {help_type} as p2 on p1.type_id=p2.type_id";
		$num = $mysql->db_num($sql);
		$page->set_data(array('total'=>$num,'perpage'=>$epage));
		$magic->assign("page",$page->show(3));
		
	}
	
	
	/**
	 * 添加
	**/
	elseif ($t == "new" || $t == "edit" ){
		if ($t == "edit"){ 
			$id= $_REQUEST['id'];
			$sql = "select p1.*,p2.name as type_name from {help} as p1 left join {help_type} as p2 on p1.type_id=p2.type_id where p1.id=$id";
			$result = $mysql->db_fetch_array($sql);
			$magic->assign("result",$result);
		}
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
		$var = array("name","summary","type_id","content","source","flag","author","publish","order","status","province","city","area");
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
			$msg = array("删除成功","返回上一页");
		}
		$user->add_log($_log,$result);//记录操作
	}
	
    elseif ('type_add' == $t || 'type_update' == $t) {
		   $var = array("name","nid","order","status","list_name","content_name","index_tpl","list_tpl","content_tpl","content","title","keywords","description");
		$index = post_var($var);
		$sql = "";
		if ($t=="type_update"){
			$sql = " and type_id!=".$_POST['type_id'];
		}
		$result = $mysql->db_fetch_array("select 1 from {help_type} where nid='{$index['nid']}' ".$sql);
        if ($result) {
            $msg = array("标示名已存在");
        }
        else {
            if ($t == "type_update"){
                $result = $mysql->db_update("help_type",$index,"type_id=".$_POST['type_id']);
                $msg = array("修改成功");
            }else{
                $result = $mysql->db_add("help_type",$index);
                $__url = $url."/type";
                if ($result == false){
                    $msg = array("输入有误，请跟管理员联系");
                }else{
                    $msg = array("操作成功","",$__url);
                }
            }
            $user->add_log($_log,$result);//记录操作
        }
		
		
    }
    elseif ('type_edit' == $t) {
        $id = isset($_GET['id'])?(int)($_GET['id']):0;

        if ($id) {
            $result = $mysql->db_fetch_array("select * from {help_type} where type_id={$id}");
            $magic->assign('result', $result);
        }
    }
    elseif ('type_del' == $t) {
        $result = $mysql->db_delete("help_type","type_id=".$_REQUEST['id']);
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			$msg = array("删除成功");
		}
		$user->add_log($_log,$result);//记录操作
    }
	 elseif ('type_order' == $t) {
        $result = $mysql->db_order("help_type",$_POST['order'],"type_id",$_POST['type_id']);
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			$msg = array("排序修改成功");
		}
		$user->add_log($_log,$result);//记录操作
    }
    elseif ($t == 'type') {
        //修改状态
		if (isset($_REQUEST['id']) && isset($_REQUEST['status'])){
			$sql = "update {flashroll_type} set status='".$_REQUEST['status']."' where type_id = ".$_REQUEST['id'];
			$mysql->db_query($sql);
		}
        
        $_sql = "";
		if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] !=""){
			$_sql .= " where `name` like '%".$_REQUEST['keywords']."%'";
		}
		$sql = "select * from {help_type} $_sql order by `order` desc";
		$num_sql = "select count(*) as num from {help_type} $_sql";

		$result = $mysql->db_list_res($sql,$p,$epage=10);
		
		$magic->assign("result",$result);

		$num = $mysql->db_num($num_sql);
		$page->set_data(array('total'=>$num,'perpage'=>$epage));
		$magic->assign("page",$page->show(3));
    }
}

?>