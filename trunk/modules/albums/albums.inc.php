<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
require_once 'albums.class.php';
$albums = new albumsClass();
$action = isset($_REQUEST['action'])?$_REQUEST['action']:"";
if ($_U['query_type'] == "apply"){
	if ($_U['user_id']==""){
		$_url = '/index.php?user&q=going/login';
		echo  "<bg><br>您还没有登录，请先登录<br /><br /><a href=$_url>登录</a><br /><br />系统将3秒后跳转<script>setTimeout('curl()',4000);function curl(){ 	location.href='$_url'}</script>";
		exit;
	}
	
	elseif ($_U['user_result']['vip_status']!=1){
		$_url = '/index.php?user&q=code/vipuser/active';
		$msg = "<bg><br>您不是vip会员，请先激活vip<br /><br /><a href=$_url>激活vip</a><br /><br />系统将3秒后跳转<script>setTimeout('curl()',4000);function curl(){ 	location.href='$_url'}</script>";
		echo $msg;
		exit;	
	}
	
	else{
		$id = $_REQUEST['id'];
		$result =  HuoDong::Apply($id,$_SESSION['user_id']);
		if ($result===true){
			echo "报名成功";
		}else{
			echo $result;
		}
		echo "";
		exit;
	}
}

elseif ($_U['query_type'] == "apply_list"){
	$result =  HuoDong::GetApply($_REQUEST['id'],"","huodong");
	$display ='';
	
	foreach ($result as $key => $value){
		$avatar = get_avatar(array("user_id"=>$value['user_id']));
		$display .='<li><div ><a href="/dzx/home.php?mod=space&uid='.$value['uc_user_id'].'" target=_blank><img src="'.$avatar.'" width=80 height=80 /></a></div><a href="">'.$value['realname'].'</a></li>';
	}
	echo "document.write('$display')";
	exit;

}

//退订
elseif ($_U['query_type'] == "cancel"){
	if (!isset($_REQUEST['id']) || $_REQUEST['id']==""){
	   $msg = array("输入有误");
	}
	$data['id'] = $_REQUEST['id'];
	$data['user_id'] = $_G['user_id'];
	$data['code'] =  "yghd";
	$result = applyClass::CancelApply($data);
	if ($result !==true){
		$msg = array($result);
	}else{
		$msg = array("退订成功","",$_U['query_url']);
	}
	

	
	
}elseif ($_U['query_type']=="join"){
	$data['user_id']   = $_G['user_id2'];
	$data['page']      = $_U['page'];
	$data['epage'] = $_U['epage'];
	$data['fuzeren'] = $_G['user_result']['username'];
	$data['code'] = "yghd1";

	$result = yghdClass::GetList($data);
	$_U['join_list'] = $result['list'];
	$_U['showpage'] = $pages->show(3);
	
}
//添加相片
elseif ($_U['query_type']=="pics"){
	if (isset($_POST['picname']) && trim($_POST['picname'])!=""){
		$data['name'] = $_POST['picname'];
		$data['id'] = $_POST['id'];
		$data['user_id'] = $_G['user_id'];
		$upload->UpdateMore($data);
		$msg = array("相片修改成功");
	}
	
}


//添加相片
elseif ($_U['query_type']=="edit"){
	if (!isset($_REQUEST['albums_id']) || $_REQUEST['albums_id']==""){
		$msg = array("您操作有误。CODE:-1");
	}elseif ( $_REQUEST['albums_id']==0){
		$msg = array("此类型为系统默认类型，不能编辑。CODE:-2");
	}else{
		if (isset($_POST['name']) && trim($_POST['name'])!=""){
			$var = array("name","order","content","type_id","id");
			$data = post_var($var);
			$data['user_id'] = $_G['user_id'];
			albumsClass::Update($data);
			$msg = array("相册修改成功");
		}else{
			$_U['albums_result'] = albumsClass::GetAlbumsOne(array("user_id"=>$_G['user_id'],"id"=>$_REQUEST['albums_id']));
			if ($_U['albums_result']==false){
				$msg = array("您的操作有误。CODE:-1");
			}
		}
	}
}

//添加相片
elseif ($_U['query_type'] == "upload"){
	if (isset($_POST['albums_id']) && $_POST['albums_id']!=""){
		$data['user_id'] = $_G['user_id'];
		$data['aid'] = $_POST['albums_id'];
		$data['code']= "albums";
		$data['name'] = $_POST['picname'];
		$data['file'] = "pic";
		$data['cut_status'] = 1;
		$data['cut_type'] = 2;
		$data['cut_width'] = 680;
		$data['cut_height'] = 680;
		$result = $upload->upfiles($data);
		$msg = array($result);
	}
}
//相片的操作
elseif ($_U['query_type'] == "going"){
	$pic_result = $upload -> GetOne(array("id"=>$_REQUEST['id'],"user_id"=>$_G['user_id']));
	
	$result = albumsClass::GetAlbumsList(array("user_id"=>$_G['user_id'],"limit"=>"all"));
	$_albums = "";
	foreach ($result as $key => $value){
		if ($pic_result['aid'] == $value['id']){
			$_albums .= "<option value='{$value['id']}' selected='selected'>{$value['name']}</option>";
		}else{
			$_albums .= "<option value='{$value['id']}'  >{$value['name']}</option>";
		}
	}
	$_select_no = $_select_yes = "";
	if ($pic_result['if_cover'] == 0){
		$_select_no = " checked";
	}else{
		$_select_yes = " checked";
	}
	echo "<br>是否封面：<input type='radio' id='if_cover' value='1' name='if_cover' {$_select_yes}>是 <input type='radio' id='if_cover' value='0' name='if_cover' {$_select_no}>否 <br/><br/>移动到：<select id='type_id' name='type_id'><option value=0>系统默认相册</option>{$_albums}</select><br/><br/><input type='button' value='确定添加' onclick='actionPic({$_REQUEST['id']})'>&nbsp;&nbsp; <input type='button' value='取消' onclick=closeW(); >";
	exit;
}


//相片的操作
elseif ($_U['query_type'] == "action_ok"){
	
	$result = albumsClass::UpdateCover(array("user_id"=>$_G['user_id'],"id"=>$_REQUEST['id']));
}


//添加相册的地址
elseif ($_U['query_type'] == "albums_new"){
	echo "<br>相册名：<input type='text' name='' size='15' id='ablums_name'><br/><br/><input type='button' value='确定添加' onclick='addAblums()'>&nbsp;&nbsp; <input type='button' value='取消' onclick=closeW(); >";
	exit;
}

//ajax删除相片
elseif ($_U['query_type'] == "del"){
	echo "<br>确定此相片要永久的删除。<br/><br/><input type='button' value='确定删除' onclick='delPic({$_REQUEST['id']})'>&nbsp;&nbsp; <input type='submit' value='取消' onclick=closeW(); >";
	exit;
}

//确定删除相片
elseif ($_U['query_type'] == "del_ok"){
	if ($_REQUEST['id']!=""){
		$result = $upload->Delete(array("user_id"=>$_G['user_id'],"id"=>$_REQUEST['id']));
		echo $result;
	}
	exit;
}

//添加相册的地址
elseif ($_U['query_type'] == "del_albums"){
	echo "<br>删除此相册，同时会永久的删除相册下所有相片。<br/><br/><input type='button' value='确定删除' onclick='delAlbums({$_REQUEST['albums_id']})'>&nbsp;&nbsp; <input type='submit' value='取消' onclick=closeW(); >";
	exit;
}

//确定删除相册
elseif ($_U['query_type'] == "del_albumsok"){
	if ($_REQUEST['albums_id']!=""){
		$result = albumsClass::DeleteAlbums(array("user_id"=>$_G['user_id'],"id"=>$_REQUEST['albums_id']));
		echo $result;
	}
	exit;
}

//ajax添加相册
elseif ($_U['query_type'] == "aladd"){
	if ($_REQUEST['name']!=""){
		$result = albumsClass::AlbumsAdd(array("user_id"=>$_G['user_id'],"name"=>$_REQUEST['name']));
		echo $result;
	}
	exit;
}

//查看相册
elseif ($_U['query_type'] == "view"){
	$result = albumsClass::GetOne(array("id"=>$_REQUEST['albums_id']));
	if ($result['user_id']!=$_G['user_id']){
		$msg = array("您没有权限查看别人的相册","",$_U['query_url']);
	}

}

$template  = "user_albums.html";
?>