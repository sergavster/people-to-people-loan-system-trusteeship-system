<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问

check_rank("vipuser_".$_t);//检查权限

require_once 'vipuser.class.php';

$_A['list_purview'] = array("vipuser"=>array("vip用户"=>array("vipuser_list"=>"vip列表","vipuser_new"=>"添加vip","vipuser_del"=>"删除vip","vipuser_nvipuser"=>"激活vip卡","vipuser_ac"=>"添加新卡","vipuser_lc"=>"充值卡列表","vipuser_ct"=>"充值卡类型")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>VIP会员列表</a> - <a href='{$_A['query_url']}/nvipuser{$_A['site_url']}'>激活VIP会员</a> - <a href='{$_A['query_url']}/lc{$_A['site_url']}'>充值卡列表</a> - <a href='{$_A['query_url']}/ac{$_A['site_url']}'>添加新卡</a> - <a href='{$_A['query_url']}/ct{$_A['site_url']}'>VIP类型管理</a>";

$module_result = moduleClass::GetOne(array("code"=>"schoolresume"));
if (!$module_result){
    $msg = array("请先安装学生简历模块","",$url);
}else{
	
	/**
	 * 如果类型为空的话则显示所有的文件列表
	**/
	 # 用户关联VIP卡
    if ($_A['query_type']=="list") {
		$data['realname'] = isset($_REQUEST['realname'])?$_REQUEST['realname']:"";
		$data['serial_number'] = isset($_REQUEST['serial_number'])?$_REQUEST['serial_number']:"";
		$data['page'] = $_A['page'];
		$data['epage'] = $_A['epage'];
        //$user_name = isset($_REQUEST['user_name'])?$_REQUEST['user_name']:0;
       // $serial_number = isset($_REQUEST['serial_number'])?$_REQUEST['serial_number']:0;
        //$p = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $result = VipUser::GetVipuserList($data);

		if (is_array($result)){
			$pages->set_data($result);
			$_A['vipuser_list'] = $result['list'];
			$_A['showpage'] = $pages->show(3);
		
		}else{
			$msg = array($result);
		}
    }
	
    # VIP卡类型管理
    elseif('ct' == $_A['query_type']) {
        $rows = VipUser::ListCardType();
        $magic->assign('rows', $rows);
		if (isset($_POST['id']) && $_POST['id']!=""){
			var_dump($_POST);
		
		}elseif (isset($_REQUEST['id']) && $_REQUEST['id']!=""){
		 $result = VipUser::ViewCardType($_REQUEST['id']);
		 $magic->assign('result', $result);
		}
    }
	
    # 新增VIP卡类型
    elseif('act' == $_A['query_type']) {
        $name = trim($_POST['type']);
        $month_num = intval($_POST['month_num']);
        if (!VipUser::AddCardType($name, $month_num)) {
            $msg = array(VipUser::$msg,'','');
        }
        else {
            $msg = array('添加成功','',"{$_A['query_url']}/ct{$_A['site_url']}");
        }
    }
	
	# 修改VIP卡类型
    elseif('uct' == $_A['query_type']) {
        $name = trim($_POST['type']);
        $month_num = intval($_POST['month_num']);
        if (!VipUser::UpdateCardType($name, $month_num,$_POST['id'])) {
            $msg = array(VipUser::$msg,'','');
        }
        else {
            $msg = array('修改成功','',"{$_A['query_url']}/ct{$_A['site_url']}");
        }
    }
	
	
    # 删除VIP卡类型
    elseif('dct' == $_A['query_type']) {
       $id = (int)$_GET['id'];
       if ($id) {
         $mysql->db_query('delete from {vip_card_type} where id='.$id);
       }
       $msg = array('成功删除','',"{$_A['query_url']}/ct{$_A['site_url']}");
    }
	
    # VIP卡
    elseif('lc' == $_A['query_type']) {
        $status = isset($_REQUEST['status'])?(int)$_REQUEST['status']:'';
        $serial_number = isset($_REQUEST['serial_number'])?$_REQUEST['serial_number']:0;
        $p = isset($_REQUEST['page'])?$_REQUEST['page']:1;
		$export = isset($_GET['export'])?(int)$_GET['export']:0;
        $status = $status >= 7?'':$status;

		if ($export <= 0) {
			$result = VipUser::ListCard($status, $serial_number, $p);
        
			$magic->assign('rows', $result['list']);
			$pages->set_data(array('total'=>$result['record_num'],'nowindex'=>$_page,'perpage'=>$result['page_size']));
			$magic->assign("page",$pages->show(3));
		}
		# 导出
		else {
			$result = VipUser::ListCard($status, $serial_number, $p, 1000000);
			$result = $result['list'];
			$data = array();
			foreach ($result as $key =>$value){
				$data[$key] = array($value['serial_number'],$value['password'],$value['city'],$value['realname'],empty($value['open_time'])?"":date("Y-m-d",$value['open_time']),empty($value['end_date'])?"":date("Y-m-d",$value['end_date']),$value['vct_name'],$value['create_user']);
			}
			$filename = "充值卡列表-".date("Y-m-d",time());
			$title = array("卡号","密码","城市","充值备注","充值时间","到期时间","VIP类型","生成人");
			exportData($filename,$title,$data);
			exit();
		}
        
    }
	
    # 新增VIP卡
    elseif('ac' == $_A['query_type']) {
        $step = isset($_POST['step'])?(int)$_POST['step']:0;
        if ($step) {
            $index['city'] = (int)$_POST['city'];
            $index['year'] = (int)$_POST['year'];
			$index['semester'] = trim($_POST['semester']);
			$index['number'] = (int)$_POST['number'];
			$index['pwd_word_num'] = (int)$_POST['pwd_word_num'];
            if(!VipUser::AddCard($index)) {
                $msg = array(VipUser::$msg,'','');
            }
            else{
                $msg = array('生成成功','',"{$_A['query_url']}/lc{$_A['site_url']}");
            }
        }
        else{
			$city = '';
			VipUser::GetCardWord($city);
            $year = date('Y');
			$years = array($year, $year + 1, $year + 2, $year + 3);
			$semester = 0==floor(date('m') / 6)?'A':'B';
			$magic->assign('years', $years);
			$magic->assign('semester', $semester);
			$magic->assign('city', $city);
        }
    }
	
    # 查看VIP卡
    elseif ('preview' == $_A['query_type']) {

        $id = isset($_REQUEST['id'])?(int)$_REQUEST['id']:0;
        if (!$id) {
            $msg = array('ID无效','','');
        }
        else{
            $result = VipUser::GetUserCardInfoById($id);
            $magic->assign('result', $result);
        }
    }
    # 删除VIP卡
    elseif ('delcard' == $_A['query_type']) {
        $ids = isset($_REQUEST['aid'])?$_REQUEST['aid']:array();
		if (!empty ($ids)) {
			if(VipUser::DeleteCard($ids)) {
                $msg = array('删除成功','',"{$_A['query_url']}/lc");
            }
		}
		else {
			$msg = array('请选择要删除的记录','',"{$_A['query_url']}/lc");
		}
    }
	
    # 新增VIP会员
    elseif ('nvipuser' == $_A['query_type']) {

        $id = isset($_GET['id'])?$_GET['id']:0;
        $user_name = isset($_POST['user_name'])?$_POST['user_name']:0;
        $serial_number = isset($_POST['serial_number'])?$_POST['serial_number']:0;
		$vct_id = isset($_POST['vct_id'])?(int)$_POST['vct_id']:0;

        if ($user_name && $serial_number && $vct_id) {
            $result = VipUser::AddVipUser($user_name, $vct_id, $serial_number);

            if (true === $result) {
                $msg = array('操作成功', '', "{$_A['query_url']}/lc");
            }
            elseif (false !== $result) {
                $msg = array($result);
            }
            else {
                $msg = array('操作失败');
            }
        }
		else {
			$vct = $mysql->db_fetch_arrays("select id, name from {vip_card_type}");
			$magic->assign('vct', $vct);
			if ($id > 0){
				$row = VipUser::GetUserCardInfoById($id);
				if ($row) {
					$magic->assign('serial_number', $row['serial_number']);
					$magic->assign('password', $row['password']);
				}
			}
		}
    }
	
	# 修改状态
	elseif ('editcard' == $_A['query_type']) {
		
		$id = isset($_REQUEST['id'])?(int)$_REQUEST['id']:0;
		$status = isset($_POST['status'])?$_POST['status']:'';
		$year = isset($_POST['year'])?(int)$_POST['year']:'';
		$month = isset($_POST['month'])?(int)$_POST['month']:'';
		
        $remark = isset($_POST['remark'])?trim($_POST['remark']):'';

        if (!$id) {
            $msg = array('无效操作');
        }
        else {
			$month = $year * 12 + $month;
            switch ($status) {
                # 冻结
                case 'dj':
                    if ($month <= 0) {
                        $msg = array('冻结时间无效');
                        break;
                    }
					$result = VipUser::FreezeCard($id, $month, $remark);
                    if (true === $result){
                        $msg = array($result);
                        break;
                    }
                    $msg = array('操作成功', '', "{$_A['query_url']}/lc");
                    break;
                # 停卡
                case 'tz':
                    if ($month <= 0) {
                        $msg = array('停卡时间无效');
                        break;
                    }
                    if (!VipUser::StopCard($id, $month, $remark)){
                        $msg = array(VipUser::$msg);
                        break;
                    }
                    $msg = array('操作成功', '', "{$_A['query_url']}/lc");
                    break;
                # 封号
                case 'jy':
                    
                    if (!VipUser::InvalidCard($id, $remark)){
                        $msg = array(VipUser::$msg);
                        break;
                    }
                    $msg = array('操作成功', '', "{$_A['query_url']}/lc");
                    break;
                # 激活
                case 'jh' :
                     if (!VipUser::ActiveCard($id, $remark)){
                        $msg = array(VipUser::$msg);
                        break;
                     }
                    $msg = array('操作成功', '', "{$_A['query_url']}/lc");
                    break;
				# 延期
				case 'yq' :
					if ($month <= 0) {
                        $msg = array('延期时间无效');
                        break;
                    }
					if (VipUser::UpdateExpireDate($id, $month, $remark)){
						$msg = array('操作成功', '', "{$_A['query_url']}/lc");
					}
					else{
						$msg = array(VipUser::$msg);
					}
					break;
               default:
                    $result = VipUser::GetCardInfoById($id);
                    $magic->assign('result', $result);
                    break;
            }
        }
	}
	elseif ('history' == $_A['query_type']) {
		
		$id = isset($_GET['id'])?(int)$_GET['id']:0;
		$p = isset($_REQUEST['page'])?$_REQUEST['page']:1;
		if ($id) {
			$result = VipUser::GetHistory($id, $p);

			$magic->assign('result', $result['list']);
			$page->set_data(array('total'=>$result['record_num'],'perpage'=>$result['page_size']));
			$magic->assign("page",$page->show(3));
		}
		else {
			$msg = array('ID无效');
		}
	}
	
	# 获取符合VIP会员的岗位
	elseif ('getjob' == $_A['query_type']) {
		require_once ROOT_PATH . '/modules/jianzhi/jianzhi.class.php';
		$user_id = isset($_GET['id'])?(int)$_GET['id']:0;
		$p = isset($_GET['page'])?$_GET['page']:1;
		if ($user_id) {

			$vu = $mysql->db_fetch_array("select card_number from {vip_user} where user_id={$user_id}");
			$vip_user = VipUser::GetVipuserList(array("serial_number"=>$vu['card_number']));
			
			$result = VipUser::GetJobList($user_id, $p);
			$is_working = PartTime::IsWorking($user_id);
			$magic->assign('vip_user', $vip_user['list'][0]);
			$magic->assign('result', $result['list']);
			$magic->assign('user_id', $user_id);
			$magic->assign('is_working', $is_working);
			$pages->set_data(array('total'=>$result['record_num'],'nowindex'=>$_REQUEST['page'],'perpage'=>$result['page_size']));
			$magic->assign("page",$pages->show(3));
		}
		else {
			$msg = array('ID无效');
		}
	}
	elseif ('admit' == $_A['query_type']) {
		require_once ROOT_PATH . '/modules/jianzhi/jianzhi.class.php';
		$job_id   = isset($_GET['jid'])?(int)$_GET['jid']:0;
		$user_id = isset($_GET['uid'])?(int)$_GET['uid']:0;
		if ($job_id && $user_id) {
			PartTime::Admit($job_id, array($user_id));
			$msg = array('操作成功','','');
		}
		else {
			$msg = array('ID无效');
		}
	}
	elseif ('export' == $_A['query_type']) {
		$result = VipUser::ListCard($status, $serial_number, $p, 1000000);
			$result = $result['list'];
		$filename = "充值卡列表-".date("Y-m-d",time());
		$title = array("卡号","密码","城市","充值备注","充值时间","到期时间","VIP类型","生成人");
		exportData($filename,$title,$data);
		exit;
	}

}

?>