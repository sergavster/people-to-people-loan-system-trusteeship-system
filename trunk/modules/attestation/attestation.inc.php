<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
include_once("attestation.class.php");

if ($_U['query_type'] == "list"){
	
}
elseif($_U['query_type'] == "one"){
	if (isset($_POST['name'])){
		if(strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
			$msg = array("验证码错误");
		}else{
			unset($_SESSION['valicode']);
			$var = array("name","type_id");
			$data = post_var($var);
			$data['user_id'] = $_G['user_id'];
			$_G['upimg']['user_id'] = $_G['user_id'];
			$_G['upimg']['file'] = "litpic";
			$_G['upimg']['cut_status'] = 0;
			$_G['upimg']['code'] = "attestation";
			$pic_result = $upload->upfile($_G['upimg']);
			if ($pic_result!=""){
				$data['litpic'] = $pic_result['filename'];//上传的图片
				$result = attestationClass::Add($data);
				if ($result!==true){
					$msg = array($reuslt);
				}else{
					$msg = array("操作成功","","index.php?user&q=code/attestation/one");
				}
			}else{
				$msg = array("上传图片失败","","index.php?user&q=code/attestation/one");
			}
			
		}
	}else{
		$_U['attestation_type_list'] = attestationClass::GetTypeList(array("limit"=>"all"));
		
		if($_G['user_result']['user_type']==1){
			$nid = 'private';
		}else{
			$nid = 'wageearners';
		}
		$sql = 'select p1.type_id,p1.litpic,p2.pid,p2.name as attestation_name,p1.status from `{attestation}` p1 left join `{attestation_type}` p2 on p1.type_id=p2.type_id where p1.user_id='.$_G['user_id'].' and p1.upload_type=1';
		$user_u = $mysql->db_fetch_arrays($sql);//用户现有的资料
		$arr_user_up = array();
		foreach($user_u as $key=>$value){
			$arr_user_up[$value['type_id']][] = $value;
		}
		$pre = $mysql->db_fetch_array("select * from `{attestation_type}` where nid='$nid'");
		$result = $mysql->db_fetch_arrays("select * from `{attestation_type}` where type_id in({$pre['include']})");
		$prearr = unserialize($pre['borrow_must']);
		if (is_array($result)){
			foreach ($result as $k=>$v){
				if($v['nid']=='tow_report' && $_G['user_result']['marry']==3){
					unset($result[$k]);
					continue;
				}
				if($v['nid']=='tow_certificate' && $_G['user_result']['marry']==3){
					unset($result[$k]);
					continue;
				}
				$select = $prearr[$v['type_id']]['select'];
				unset($prearr[$v['type_id']]['select']);
				$re = attestationClass::GetTypeList(array('pid'=>$v['type_id'],'limit'=>'all'));
				if($re!=""){
					foreach ($re as $k_1=>$v_1){
						if(in_array($v_1['type_id'],$prearr[$v['type_id']])){
							$re[$k_1] = "<span style='color:red'>{$v_1['name']}</span>";
							$result[$k]['you_must'][$k_1]['attestation_name'] = $v_1['name'];
							$result[$k]['you_must'][$k_1]['is_u'] = 0;
							$result[$k]['you_must'][$k_1]['status'] = 0;
							if(array_key_exists($v_1['type_id'], $arr_user_up)){
								$result[$k]['you_must'][$k_1]['is_u'] = 1;
								foreach ($arr_user_up[$v_1['type_id']] as $u_k){
									$result[$k]['you_must'][$k_1]['status'] = $u_k['status'];
									$result[$k]['you_must'][$k_1]['litpic'] = $u_k['litpic'];
									if($u_k['status']==1){
										$result[$k]['you_must'][$k_1]['status'] = 1;
									}
								}
							}
						}else{
							$re[$k_1] = $v_1['name'];
							if(array_key_exists($v_1['type_id'], $arr_user_up)){
								$result[$k]['not_must'][] = $arr_user_up[$v_1['type_id']];
							}
						}
						rsort($result[$k]['you_must']);
					}
					$count = count($re,0);
					if($count>0 && $select>0){
						$x = $count.'选'.$select;
						array_push($re, $x);
					}
					$result[$k]['son'] = $re;
				}
				$result[$k]['att_num'] = count($result[$k]['you_must'],0)+count($result[$k]['not_must'][0],0);
			}
			$_U['include_type_list'] = $result;
			$_U['attestation_presult'] = $pre;
		}
	}
}
elseif($_U['query_type'] == "more"){
	
	if (isset($_POST['name'])){
		/*
		$var = array("name","type_id");
		$data = post_var($var);
		$data['user_id'] = $_G['user_id'];
		$_G['upimg']['file'] = "pics";
		$_G['upimg']['cut_status'] = 0;
		$_G['upimg']['code'] = "attestation";
		$pic_result = $upload->upfile($_G['upimg']);
		
		if ($pic_result!=""){
			foreach($pic_result as $key => $value){
				if($value!=""){
					$data['litpic'] = $value['filename'];
					$result = attestationClass::Add($data);
				}
			}
		}
		
		if ($result!==true){
			$msg = array($reuslt);
		}else{
			$msg = array("操作成功","","index.php?user&q=code/attestation");
		}
		*/
	}else{
		$_U['attestation_type_list'] = attestationClass::GetTypeList(array("limit"=>"all"));
	}	
}
elseif ($_U['query_type'] == 'upfile_type'){
	$type_id = intval($_GET['type_id']);
	if($type_id<1){
		echo '请不要乱操作';
		exit();
	}
	$sql = 'select * from {attestation_type} where pid='.$type_id;
	$result = $mysql->db_fetch_arrays($sql);
	if($result==false){
		echo '请不要乱操作';
		exit();
	}else{
		echo '<form action="" name="form1" method="post" enctype="multipart/form-data" onsubmit="return submit_fool()">';
		echo '<div class="user_right_border">';
		echo '<div class="l">资料上传：</div>';
		echo '<div class="c">';
		echo '<input type="file" name="litpic" />';
		echo '</div>';
		echo '</div>';
		
		echo '<div class="user_right_border">';
		echo '<div class="l">上传类型：</div>';
		echo '<div class="c">';
		echo '<select name="type_id">';
		foreach ($result as $key=>$value){
			echo '<option value="'.$value['type_id'].'">'.$value['name'].'</option>';
		}
		echo '</select></div></div>';
		echo '<div class="user_right_border">';
		echo '<div class="l">备注说明：</div>';
		echo '<div class="c">';
		echo '<textarea cols="50" rows="5" name="name"></textarea>';
		echo '</div>';
		echo '</div>';
			
		echo '<div class="user_right_border">';
		echo '<div class="l" style="font-weight:bold; float:left;">验证码：</div>';
		echo '<div class="c" style="margin-top:5px">';
		echo '<input name="valicode" type="text" size="11" maxlength="4"  tabindex="3"  style="float:left;" />';
		echo '&nbsp;<img src="/plugins/index.php?q=imgcode" alt="点击刷新" onClick="this.src=\'/plugins/index.php?q=imgcode&t=\' + Math.random();" align="absmiddle" style="cursor:pointer;float:left;" />';
		echo '</div>';
		echo '</div>';
		echo '<div class="user_right_border">';
		echo '<div class="e"></div>';
		echo '<div class="c">';
		echo '<input type="submit" class="btn-action" value="确认提交" size="30" />';
		echo '</div>';
		echo '</div>';
		echo '</form>';
	}
	exit();
}
$template = "user_attestation.html.php";
?>
