<?
include_once(ROOT_PATH."modules/account/account.class.php");
include_once(ROOT_PATH."modules/borrow/amount.class.php");
include_once(ROOT_PATH."modules/credit/credit.class.php");
require_once(ROOT_PATH."modules/remind/remind.class.php");

require_once(ROOT_PATH."modules/borrow/biao/xinbiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/jinbiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/fastbiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/miaobiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/lzbiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/vouchbiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/gxdbiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/sydbiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/wqdbiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/yxlcbiao.class.php");
require_once(ROOT_PATH."modules/borrow/biao/jjtzbiao.class.php");
//liukun add for bug 52 begin
$firePHPEnable=TRUE;
if ($firePHPEnable){
	require_once(ROOT_PATH.'modules/FirePHPCore/FirePHP.class.php');
	require_once(ROOT_PATH.'modules/FirePHPCore/fb.php');
	ob_start();

	$firephp = FirePHP::getInstance(true);
}
//liukun add for bug 52 end

function isTimePatternT($str){
	if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $str, $match) == 0 ){
		return false;
	}else{
		return true;
	}
}
class borrowClass extends amountClass{
	
	const ERROR = '操作有误，请不要乱操作';
	const BORROW_NAME_NO_EMPTY = '借款的标题不能为空';
	const BORROW_ACCOUNT_NO_EMPTY = '借款金额不能为空';
	const BORROW_APR_NO_EMPTY = '借款利率不能为空';
	const BORROW_ACCOUNT_NO_MAX = '借款不能高于最高额度';
	const BORROW_ACCOUNT_NO_MIN = '借款不能低于最低限额';
	const BORROW_APR_NO_MAX = '借款利率不能高于最高限额';
	const BORROW_APR_NO_MIN = '借款利率不低于最低限额';
	const BORROW_REPAYMENT_NOT_ENOUGH = '帐户可用余额少于要还款的金额';
	const BORROW_ACCOUNT_MAZ_ACC = '借款额度不能大于最大额度';
	const NO_LOGIN = '还没有登录';
	const BORROW_DAY_MODEL = '此标必须为月标';
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql,$_G;
		$user_id = empty($data['user_id'])?"":intval($data['user_id']);
		$page = empty($data['page'])?1:intval($data['page']);
		$epage = empty($data['epage'])?10:intval($data['epage']);
		$_sql = "where 1=1 ";
		if (isset($data['username'])  && $data['username']!=""){
			$searchuser= $mysql->db_fetch_array("select user_id from {user} where username= '{$data['username']}'");
			$user_id=$searchuser['user_id'];
 		}
		if ($user_id!=""){
			$_sql .= " and p1.user_id = {$user_id}";
		}
		$type = '';
		if (isset($data['type'])){
			$type = $data['type'];
			if ($type==""){
				$_sql .= "  and p1.status=1 and (p1.verify_time+p1.valid_time*25*60*60)>".time()." and p1.account_yes<p1.account";
			}elseif ($type=="all"){
				$_sql .= "  and ((p1.status=1 and (p1.verify_time+p1.valid_time*24*60*60)>".time().") or p1.status=3 ) ";
            }elseif ($type=="allIndex"){
				$_sql .= "  and (p1.status=1 or p1.status=3 ) and (p1.verify_time+p1.valid_time*25*60*60)>".time()." ";        
			}elseif ($type=="review"){
				$_sql .= " and p1.account=p1.account_yes ";
			}elseif ($type=="reviews"){
				$_sql .= " and p1.account=p1.account_yes ";
				$_sql .= " and p1.status=1";
			}elseif ($type=="success"){
				$_sql .= " and p1.status=3";
			}elseif ($type=="vouch"){
				$_sql .= " and p1.is_vouch=1 and p1.status=1";
			}elseif ($type=="lz"){
				$_sql .= " and p1.is_lz=1 and p1.status=1";
			}elseif ($type=="now"){//正在还
				$_sql .= " and p1.repayment_account!=p1.repayment_yesaccount";
			}elseif ($type=="yes"){//已还
				$_sql .= " and p1.repayment_account=p1.repayment_yesaccount";
			}elseif ($type=="late"){//过期
				$_sql .= " and p1.verify_time+p1.valid_time*24*60*60<".time();
			}elseif ($type=="fast"){
                $_sql .= " and p1.is_fast=1 and (p1.status=1 or p1.status=3 ) and p1.isday != 1";
           }elseif ($type=="jin"){
                $_sql .= " and p1.is_jin=1 and (p1.status=1 or p1.status=3 )";
           }elseif ($type=="xin"){
                $_sql .= " and p1.is_jin !=1 and (p1.verify_time+p1.valid_time*25*60*60)>".time()."  and p1.is_fast !=1 and p1.is_vouch !=1 and p1.is_lz !=1 and p1.is_mb !=1 and (p1.status=1 or p1.status=3 )";
           }elseif ($type=="mb"){
                $_sql .= " and p1.is_mb =1  and (p1.verify_time+p1.valid_time*25*60*60)>".time()."  and p1.status=1 and isnull(p1.pwd)";
           }
		}
		if (isset($data['recMonth']) && $data['recMonth']=="1"){
			$curDate = time();
			$curDateStart = $curDate-24*60*60*30;
			$_sql .= " and p1.addtime <= ".$curDate." and p1.addtime >=".$curDateStart;
		}else{
			if (isset($data['dotime2'])  && $data['dotime2']!=""){
					$_sql .= " and p1.addtime <= ".get_mktime($data['dotime2'].' 23:59:59');
			}
			if (isset($data['dotime1']) && $data['dotime1']!=""){
					$_sql .= " and p1.addtime >= ".get_mktime($data['dotime1'].' 00:00:00');
			}
		}
		if (isset($data['status']) && $data['status']!=""){
			$_sql .= " and p1.status in ({$data['status']})";
		}
		//add by weego 2013-01-18
		if (isset($data['biaoType']) && $data['biaoType']!=""){
			$t_biaotype = array($data['biaoType']);
			if(strpos($data['biaoType'],',')){
				$t_biaotype = explode(',', $data['biaoType']);
			}
			$_biaotype = '';
			foreach($t_biaotype as $tk=>$tv){
				$_biaotype .= ",'".$tv."'";
			}
			$_biaotype = substr($_biaotype, 1);
			$_sql .= " and p1.biao_type in ({$_biaotype})";
		}
		//end add
		if (isset($data['is_vouch']) && $data['is_vouch']!=""){
			$_sql .= " and p1.is_vouch in ({$data['is_vouch']})";
		}
		
		if (isset($data['timelimit1']) && $data['timelimit1']!=""){
			$data['timelimit1'] = intval($data['timelimit1']);
			$_sql .= " and p1.time_limit*30+p1.time_limit_day >= {$data['timelimit1']}";
		}
		if (isset($data['timelimit2']) && $data['timelimit2']!=""){
			$data['timelimit2'] = intval($data['timelimit2']);
			$_sql .= " and p1.time_limit*30+p1.time_limit_day <= {$data['timelimit2']}";
		}
		if(isset($data['time_limit']) && $data['time_limit']!=''){
			$a = explode("-", $data['time_limit']);
			$s = isset($a[0])?$a[0]:0;
			$e = isset($a[1])?$a[1]:0;
			$_sql .= " and (p1.time_limit*30+p1.time_limit_day)>={$s} and (p1.time_limit*30+p1.time_limit_day)<={$e}";
		}
		if (isset($data['use']) && $data['use']!=""){
			$data['use'] = intval($data['use']);// Add by Liuyaoyao 2012-04-24
			$_sql .= " and p1.use in ({$data['use']})";
		}
		if (isset($data['award']) && $data['award']!=""){
			$data['award'] = intval($data['award']);// Add by Liuyaoyao 2012-04-24
			if($data['award']==1){
			$_sql .= " and p1.award >0";
			}else{
			$_sql .= " and p1.award = 0";
			}
		}
		if (isset($data['style']) && $data['style']!=""){
			$data['style'] = intval($data['style']);// Add by Liuyaoyao 2012-04-24
			$_sql .= " and p1.style in ({$data['style']})";
		}
		//add by weego for 我要投资搜索关键词 20120527
		if (isset($data['keywords']) && $data['keywords']!=""){
			$data['keywords']=urldecode($data['keywords']);
			$data['keywords']=safegl($data['keywords']);
			$_sql .= " and (p1.name like '%".$data['keywords']."%')";
		}
		if (isset($data['province']) && $data['province']!=""){
			$data['province'] = intval($data['province']);// Add by Liuyaoyao 2012-04-24
			$_sql .= " and p2.province ={$data['province']}";
		}
		if (isset($data['city']) && $data['city']!=""){
			$data['city'] = intval($data['city']);// Add by Liuyaoyao 2012-04-24
			$_sql .= " and p2.city ={$data['city']}";
		}
		if (isset($data['use']) && $data['use']!=""){
			$data['use'] = intval($data['use']);// Add by Liuyaoyao 2012-04-24
			$_sql .= " and p1.use in ({$data['use']})";
		}
		if (isset($data['account1']) && $data['account1']!=""){
			$data['account1'] = intval($data['account1']);// Add by Liuyaoyao 2012-04-24
			$_sql .= " and p1.account >= {$data['account1']}";
		}
		if (isset($data['account2']) && $data['account2']!=""){
			$data['account2'] = intval($data['account2']);// Add by Liuyaoyao 2012-04-24
			$_sql .= " and p1.account <= {$data['account2']}";
		}
		if (isset($data['apr1']) && $data['apr1']!=""){
			$data['apr1'] = intval($data['apr1']);
			$_sql .= " and p1.apr >= {$data['apr1']}";
		}
		if (isset($data['apr2']) && $data['apr2']!=""){
			$data['apr2'] = intval($data['apr2']);
			$_sql .= " and p1.apr <= {$data['apr2']}";
		}
		$_order = " order by p1.`order` desc,p1.id desc ";
		if (isset($data['order']) && $data['order']!=""){
			$order = $data['order'];
			if ($order == "account_up"){
				$_order = " order by p1.`account` desc ";
			}else if ($order == "account_down"){
				$_order = " order by p1.`account` asc";
			}
			if ($order == "credit_up"){
				//$_order_c = " order by p3.`value` desc ";
			}else if ($order == "credit_down"){
				//$_order_c = " order by p3.`value` asc  ";
			}
			if ($order == "apr_up"){
				$_order = " order by p1.`apr` desc,p1.id desc ";
			}else if ($order == "apr_down"){
				$_order = " order by p1.`apr` asc,p1.id desc ";
			}
			if ($order == "jindu_up"){
				$_order = " order by `scales` desc,p1.id desc ";
			}else if ($order == "jindu_down"){
				$_order = " order by `scales` asc,p1.id desc ";
			}
			if ($order == "flag"){
				$_order = " order by p1.is_vouch desc,p1.`flag` desc,p1.id desc ";
			}
			if ($order == "index"){
				$_order = " order by p1.status asc, p1.id desc ";
			}
			if($order == "biaoindex"){
				$_order = " order by scales,id desc ";
				//$_sql .= " and p1.status =1 and (p1.verify_time+p1.valid_time*24*60*60)>=".time()." ";
				//$_sql .= " and (p1.status =1 or (p1.status =3 and p1.is_lz!=1)) and ((p1.verify_time+p1.valid_time*25*60*60)>=".time()." or p1.account_yes=p1.account)";
			}
		}
		$_select = " p1.*,p1.account_yes/p1.account as scales";
		if (isset($data['province']) && $data['province']!=""){
			$sql = "select SELECT from `{borrow}` as p1 left join `{user}` as p2 on p1.user_id=p2.user_id $_sql ORDER LIMIT";
		}else{
			$sql = "select SELECT from `{borrow}` as p1 $_sql ORDER LIMIT";
		}
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = " limit ".$data['limit'];
			}
			$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
            foreach($list as $key => $value){
				//add by weego 20130219 for 标列表查询优化 begin
				$sql = "select p2.username,p2.area as user_area ,p2.qq ,p3.value as credit_jifen,(select pic from `{credit_rank}` as p4 where p3.value<=point2  and p3.value>=point1) as credit_pic from `{user}` as p2 left join `{credit}` as p3 on p2.user_id=p3.user_id  where p2.user_id='{$value['user_id']}'";
				$result = $mysql ->db_fetch_array($sql);
				$list[$key]['username']=$result['username'];
				$list[$key]['user_area']=$result['user_area'];
				$list[$key]['qq']=$result['qq'];
				$list[$key]['credit_jifen']=$result['credit_jifen'];
				$list[$key]['credit_pic']=$result['credit_pic'];
				$result = array();
				//add by weego 20130219  for 标列表查询优化 end
				$list[$key]['account_format'] =number_format($value['account']); //add by weego for format account 20120418
                //获取进度
                $scaleValue=100*($value['account_yes']/$value['account']);
                if($scaleValue>99.95 && $scaleValue<99.99999999){
                	$list[$key]['scale']=99.9;
                }else{
                	$list[$key]['scale'] = round(100*$value['account_yes']/$value['account'],1);
                }
				$list[$key]['scale'] = $list[$key]['scale']>0?$list[$key]['scale']:0;
                $list[$key]['other'] = $value['account'] - $value['account_yes'];
                $list[$key]['scale_width'] = round((20*$value['account_yes']/$value['account']))*7;
                $list[$key]['repayment_noaccount'] = $value['repayment_account'] - $value['repayment_yesaccount'];
                //获取担保进度
                $lave_time_t = $value['verify_time'] + $value['valid_time']*24*60*60-time();
                if($lave_time_t >0){
                	$iDay = intval($lave_time_t/24/3600);
                    $iHour = intval(($lave_time_t/3600)%24);
                    $iMinute = intval(($lave_time_t/60)%60);
                    if($iDay!=0) $list[$key]['lave_time'] = $iDay."天";
                    if($iHour!=0) $list[$key]['lave_time'] = $list[$key]['lave_time'].$iHour."小时";
                    if($iMinute!=0) $list[$key]['lave_time'] = $list[$key]['lave_time'].$iMinute."分";
                }else{
                	$list[$key]['lave_time'] = "已结束";
                }
                $list[$key]['lave_time_t'] = $lave_time_t;
                $list[$key]['vouch_scale'] = round(100*$value['vouch_account']/$value['account'],1);
                $list[$key]['vouch_other'] = $value['account'] - $value['vouch_account'];
                $list[$key]['vouchscale_width'] = round((20*$value['vouch_account']/$value['account']))*7;
                foreach ($value as $_key => $_value){
                    $list[$key][$_key] = $_value;
                }
				//标的类型图片
				if($value['borrow_rzlx']=='xyrz'){
					$list[$key]['borrow_rzlx_img'] = '<img src="'.$_G['tpldir'].'/images/xin.png"  border="0" />';
				}elseif($value['borrow_rzlx']=='sdrz'){
					$list[$key]['borrow_rzlx_img'] = '<img src="'.$_G['tpldir'].'/images/shi.png"  border="0" />';
				}elseif($value['borrow_rzlx']=='jgdb'){
					$list[$key]['borrow_rzlx_img'] = '<img src="'.$_G['tpldir'].'/images/bao.png"  border="0" />';
				}elseif($value['borrow_rzlx']=='znlc'){
					$list[$key]['borrow_rzlx_img'] = '<img src="'.$_G['tpldir'].'/images/zhi.png"  border="0" />';
				}
                //标的名称 add by weego 20130116
				$sql = "select * from `{biao_type}` where biao_type_name='{$value['biao_type']}'";
				$result = $mysql ->db_fetch_array($sql);
				$list[$key]['show_name']=$result['show_name'];
            }
			return $list;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$_list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $limit), $sql));
		$_list = $_list?$_list:array();
		$result = array();
		$list = array();
		foreach($_list as $key => $value){
			//add by weego 20130219 for 标列表查询优化 begin
			$sql = "select p2.username,p2.area as user_area ,p2.qq ,p3.value as credit_jifen,(select pic from `{credit_rank}` as p4 where p3.value<=point2  and p3.value>=point1) as credit_pic from `{user}` as p2 left join `{credit}` as p3 on p2.user_id=p3.user_id  where p2.user_id='{$value['user_id']}' ";
			$result = $mysql ->db_fetch_array($sql);
			$list[$key]['username']=$result['username'];
			$list[$key]['user_area']=$result['user_area'];
			$list[$key]['qq']=$result['qq'];
			$list[$key]['credit_jifen']=$result['credit_jifen'];
			$list[$key]['credit_pic']=$result['credit_pic'];
			$result = array();
			//add by weego 20130219  for 标列表查询优化 end
			$list[$key]['account_format'] =number_format($value['account']); //add by weego for format account 20120418
			//获取进度
            $scaleValue=100*($value['account_yes']/$value['account']);
            if($scaleValue>99.95 && $scaleValue<99.99999999){
            	$list[$key]['scale']=99.9;
            }else{
            	$list[$key]['scale'] = round(100*$value['account_yes']/$value['account'],1);
            }
			$list[$key]['scale'] = $list[$key]['scale']>0?$list[$key]['scale']:0;
			$list[$key]['other'] = $value['account'] - $value['account_yes'];
			$list[$key]['scale_width'] = round((20*$value['account_yes']/$value['account']))*7;
			$list[$key]['repayment_noaccount'] = $value['repayment_account'] - $value['repayment_yesaccount'];
			//获取担保进度
			$lave_time_t = $value['verify_time'] + $value['valid_time']*24*60*60-time();
			if($lave_time_t >0){
				$iDay = intval($lave_time_t/24/3600);
				$iHour = intval(($lave_time_t/3600)%24);
				$iMinute = intval(($lave_time_t/60)%60);
				if($iDay!=0) $list[$key]['lave_time'] = $iDay."天";
				if($iHour!=0) $list[$key]['lave_time'] = $list[$key]['lave_time'].$iHour."小时";
				if($iMinute!=0) $list[$key]['lave_time'] = $list[$key]['lave_time'].$iMinute."分";
			}else{
				$list[$key]['lave_time'] = "已结束";
			}
			$list[$key]['lave_time_t'] = $lave_time_t;
			$list[$key]['vouch_scale'] = round(100*$value['vouch_account']/$value['account'],1);
			$list[$key]['vouch_other'] = $value['account'] - $value['vouch_account'];
			$list[$key]['vouchscale_width'] = round((20*$value['vouch_account']/$value['account']))*7;
			foreach ($value as $_key => $_value){
				$list[$key][$_key] = $_value;
			}
			//标的类型图片
			if($value['borrow_rzlx']=='xyrz'){
				$list[$key]['borrow_rzlx_img'] = '<img src="'.$_G['tpldir'].'/images/xin.png"  border="0" />';
			}elseif($value['borrow_rzlx']=='sdrz'){
				$list[$key]['borrow_rzlx_img'] = '<img src="'.$_G['tpldir'].'/images/shi.png"  border="0" />';
			}elseif($value['borrow_rzlx']=='jgdb'){
				$list[$key]['borrow_rzlx_img'] = '<img src="'.$_G['tpldir'].'/images/bao.png"  border="0" />';
			}elseif($value['borrow_rzlx']=='znlc'){
				$list[$key]['borrow_rzlx_img'] = '<img src="'.$_G['tpldir'].'/images/zhi.png"  border="0" />';
			}
			//标的名称 add by weego 20130116
			if(isset($_G['biao_type'][$value['biao_type']])){
				$list[$key]['show_name']=$_G['biao_type'][$value['biao_type']]['show_name'];
			}else{
				$sql = "select * from `{biao_type}` where biao_type_name='{$value['biao_type']}'";
				$result = $mysql ->db_fetch_array($sql);
				$list[$key]['show_name']=$result['show_name'];
			}
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	/*
	 * 后台借款列表
	 */
	function GetListAdmin($data=array()){
		global $mysql,$_G,$HX_config;
		$page = isset($data['page'])?intval($data['page']):1;
		$epage = isset($data['epage'])?intval($data['epage']):10;
		$where = ' where 1=1';
		if(isset($data['username']) && $data['username']!=''){
			$where .= ' and u.username like \'%'.$data['username'].'%\'';
		}
		if(isset($data['biaoType']) && $data['biaoType']!=''){
			$where .= ' and p1.biao_type=\''.$data['biaoType'].'\'';
		}
		if(isset($data['dotime1']) && $data['dotime1']!=''){
			$where .= ' and p1.addtime>='.intval(strtotime($data['dotime1'].' 0:0:0'));
		}
		if(isset($data['dotime2']) && $data['dotime2']!=''){
			$where .= ' and p1.addtime<='.intval(strtotime($data['dotime2'].' 23:59:59'));
		}
		if(isset($data['status']) && $data['status']!=''){
			$where .= ' and p1.status='.$data['status'];
		}
		if($data['status']==1){
			$where .= ' and p1.status=1 and p1.verify_time+p1.valid_time*24*60*60-'.time().'>0';
		}
		if($data['type']=='review'){
			$where .= ' and p1.account=p1.account_yes and p1.status=1 and p1.biao_type!=\'lz\'';
		}
		$sql = 'select count(1) as count from {borrow} p1 left join {user} u on p1.user_id=u.user_id'.$where;
		$re = $mysql->db_fetch_array($sql);
		$total = $re['count'];
		$total_page = ceil($total/$epage);
		$limit = ' limit '.($page-1)*$epage.','.$epage;
		$order = ' order by id desc ';
		$sql = 'select p1.*,u.user_id,u.username from {borrow} p1 left join {user} u on p1.user_id=u.user_id '.$where.$order.$limit;
		$list = $mysql->db_fetch_arrays($sql);
		foreach ($list as $k=>$v){
			$list[$k]['show_name'] = $_G['biao_type'][$v['biao_type']]['show_name'];
			$list[$k]['is_liubiao'] = $list[$k]['verify_time']+$list[$k]['valid_time']*25*60*60-time();
			if($v['status']==3){
				$a = $mysql->db_fetch_array("select id as order_id,err_code,err_msg from {tg_order} where borrow_id={$v['id']} and tran_code='{$HX_config['fullTranCode']}' order by id desc limit 1");
				$a = empty($a)?array():$a;
				$list[$k] = array_merge($list[$k], $a);
				$a = $mysql->db_fetch_array("select * from {account_log} where type='risk_fee' and borrow_id={$v['id']}");
				if(empty($a)){
					$list[$k]['risk_fee'] = -1;
				}else{
					$list[$k]['risk_fee'] = $a['money'];
				}
			}
		}
		return array(
			'list' => $list,
			'total' => $total,
			'page' => $page,
			'epage' => $epage,
			'total_page' => $total_page
		);
	}
	/*
	 * 导出列表
	 */
	function borrowListForExcel($data=array()){
		global $magic,$_G;
		$filename=isset($data['filename'])?$data['filename']:"列表";
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=$filename.xls");
		$magic->assign("_G",$_G);
		$magic->assign("excelType",$data['type']);
		$magic->assign("title",$filename);
		$magic->assign("excel_title",$data['title']);
		$magic->assign("excelresult",$data['excelresult']);
		$magic->display("excel.html");
		exit();
	}
	/*
	 * 获取客服
	 */
	function Getkf(){
		global $_G,$mysql;
		if($_G['user_id']==0){
			$kfUserId=0;
		}else{
			$kfUserId=$_G['user_id'];
		}
		$sql="select u.username,u.qq,u.phone from `{user}` as u left join `{user_cache}` as uca on uca.kefu_userid=u.user_id where uca.user_id=".$kfUserId;
		$row = $mysql->db_fetch_array($sql);
		return $row;
	}
	/**
	 * 查看
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetOne($data = array()){
		global $mysql;
		$borrow_id = intval($data['id']);
		if($borrow_id<1) return false;
		$_sql = 'where p1.id='.$borrow_id;
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and  p1.user_id = '{$data['user_id']}' ";
		}
		$sql = "select p1.* ,p2.username,p2.realname,p2.user_type,p2.phone,p2.pIpsAcctNo,p3.username as verify_username from `{borrow}` as p1 
				  left join `{user}` as p2 on p1.user_id=p2.user_id 
				  left join `{user}` as p3 on p1.verify_user = p3.user_id 
				  $_sql
				";
		$result = $mysql->db_fetch_array($sql);
		if (isset($data['tender_userid']) && $data['tender_userid']!=""){
			$sql = "select sum(account) as num from `{borrow_tender}` where user_id='{$data['tender_userid']}' and borrow_id={$data['id']}";
			$_result = $mysql->db_fetch_array($sql);
			$result['tender_yes'] = !empty($_result['num'])?$_result['num']:0;
		}
		return $result;
	}
    /*
     * 获取某用户是否投过某个标，借款协议调用
     */
	public static function CheckBorrowTender($data = array()){
		global $mysql;
		$user_id = intval($data['user_id']);
		$borrow_id = intval($data['id']);
		if($user_id<1 || $borrow_id<1) return false;
		$_sql = " where p1.user_id =".$user_id.' and p1.borrow_id ='.$borrow_id;
		$sql = "select count(*) as checkStatus from `{borrow_tender}` as p1 $_sql ";
		$result = $mysql->db_fetch_array($sql);
		if($result['checkStatus']>0) return $result;
		$result = $mysql->db_fetch_array('select count(*) as checkStatus from {borrow} p1 where id='.$borrow_id.' and user_id='.$user_id);
		return $result;
	}
    /*
     * 获取用户的各个信息，用户中心首页调用
     */
	public static function GetUserLog($data = array()){
		global $mysql;
		//include_once(ROOT_PATH."modules/account/account.inc.php");
		//include_once(ROOT_PATH."modules/account/account.class.php");
		$_result = accountClass::GetUserLog($data);

		$user_id = $data['user_id'];
		$_result['borrow_account'] = 0;
		$sql = "select sum(account) as num from `{borrow}` where user_id = '{$user_id}' and (status=3)  ";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false){
			$_result['borrow_account'] = $result['num'];//借款总额
		}
		$_result['payment_times'] = 0;
		$sql = "select count(account) as num from `{borrow}` where user_id = '{$user_id}' and status=3  ";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false){
			$_result['payment_times'] = $result['num'];
		}
		
		$sql = "select count(*) as num from `{borrow}` where user_id = '{$user_id}' ";
		$result = $mysql->db_fetch_array($sql);
		$_result['borrow_times'] =$result['num'];
		$_result['max_account'] =$_result['amount'] - $_result['borrow_account'];//最大额度

		//投资详情
		$sql = "select status,sum(account) as total_account,count(id) as tender_times  from `{borrow_tender}`  where user_id = '{$user_id}' group by status ";
		$result = $mysql->db_fetch_arrays($sql);
		$_result['success_tender_account'] = 0;
		$_result['success_tender_times'] = 0;
		$_result['success_tender_avgaccount'] = 0;
		foreach ($result as  $key =>$value){
			$_result['invest_account'] += $value['total_account'];//投标总额
			if ($value['status']==1){
				$_result['success_tender_account'] = $value['total_account'];//成功的投标总额
				$_result['success_tender_times'] = $value['tender_times'];//投标次数
				$_result['success_tender_avgaccount'] = $value['total_account']/$value['tender_times'];
			}
		}

		//利息
		$sql = "select p1.status ,sum(p1.repay_account) as total_repay_account ,sum(p1.interest) as total_interest_account,sum(p1.capital) as total_capital_account  from `{borrow_collection}` as p1 left join `{borrow_tender}` as p2  on p1.tender_id = p2.id  where p2.status=1  and  p2.user_id = '{$user_id}' and p2.borrow_id in (select id from `{borrow}` where status=3 or (status=1 and is_lz=1))  group by p1.status ";
		$result = $mysql->db_fetch_arrays($sql);

		$_result['success_account'] = 0;
		$_result['collection_account1'] = 0;
		$_result['collection_interest1'] = 0;
		$_result['collection_capital1'] = 0;
		$_result['collection_account0'] = 0;
		$_result['collection_interest0'] = 0;
		$_result['collection_capital0'] = 0;
		$_result['collection_account2'] = 0;
		$_result['collection_interest2'] = 0;
		$_result['collection_capital2'] = 0;
		foreach ($result as  $key =>$value){
			$_result['success_account'] += $value['total_capital_account'];//投标总额
			if ($value['status']==1){
				$_result['collection_account1'] += $value['total_repay_account'];
				$_result['collection_interest1'] += $value['total_interest_account'];
				$_result['collection_capital1'] += $value['total_capital_account'];
			}
			if ($value['status']==0){
				$_result['collection_account0'] += $value['total_repay_account'];
				$_result['collection_interest0'] += $value['total_interest_account'];
				$_result['collection_capital0'] += $value['total_capital_account'];
			}
			if ($value['status']==2){
				$_result['collection_account2'] += $value['total_repay_account'];
				$_result['collection_interest2'] += $value['total_interest_account'];
				$_result['collection_capital2'] += $value['total_capital_account'];
			}
		}
		$_result['collection_wait'] = 	$_result['collection_capital0'] + $_result['collection_interest0'];//待回收
		$_result['collection_yes'] = 	$_result['collection_capital1'] + $_result['collection_interest1']+$_result['collection_capital2'] + $_result['collection_interest2'];//已回收
		$_result['collection_capital1'] = $_result['collection_capital1']+$_result['collection_capital2'];
		//$_result['success_account'] = $_result['collection_capital0'] + $_result['collection_capital1'] + $_result['collection_capital2'];//借出总额
		//最近收款日期
		$sql = "select p1.repay_time  from `{borrow_collection}` as p1 left join `{borrow_tender}` as p2  on p1.tender_id = p2.id  where p2.status=1 and p1.status=0  and  p2.user_id = '{$user_id}' and p1.repay_time>".time()." order by p1.repay_time asc";
		$result = $mysql->db_fetch_array($sql);
		
		$_result['collection_repaytime'] = isset($result['repay_time'])?$result['repay_time']:0;

		//待还总额
		$_result_wait = self::GetWaitPayment(array("user_id"=>$user_id));
		$_result = array_merge ($_result, $_result_wait);

		//额度管理
		$_result_amount = amountClass::GetAmountOne($user_id);
		$_result = array_merge ($_result, $_result_amount);
		 
		//可用担保额度应该是借要借入的担保标和已经成功借入的担保标
		
		//$sql = "select * from `{borrow_amountlog}` where user_id='{$user_id}' and type ='vouch' order by id desc";
		//$result = $mysql->db_fetch_array($sql);
		/*
		$result = self::GetAmountLogOne(array("user_id"=>$user_id,"amount_type"=>"credit"));
		if ($result!=""){
			$_result['credit_amount_total'] = $result['account_total'];//可用额度
			$_result['credit_amount_use'] = $result['account_use'];//可用额度
		}
		
		$result = self::GetAmountLogOne(array("user_id"=>$user_id,"amount_type"=>"vouch"));
		if ($result!=""){
			$_result['vouch_amount_total'] = $result['account_total'];//可用投资担保额度
			$_result['vouch_amount_use'] = $result['account_use'];//可用投资担保额度
		}
		
		$result = self::GetAmountLogOne(array("user_id"=>$user_id,"amount_type"=>"borrowvouch"));
		if ($result!=""){
			$_result['borrowvouch_amount_total'] = $result['account_total'];//可用借款担保额度
			$_result['borrowvouch_amount_use'] = $result['account_use'];//可用借款担保额度
		}
		
		*/
		
		//最近还款时间和总额
		$sql = "select repayment_time,repayment_account from `{borrow_repayment}` where status !=1 and borrow_id in (select id from `{borrow}` where user_id = {$user_id} and status=3 or (status=1 and is_lz=1)) order by repayment_time ";
		$result = $mysql->db_fetch_array($sql);
		$_result['new_repay_time'] = isset($result['repayment_time'])?$result['repayment_time']:0;
		$_result['new_repay_account'] = isset($result['repayment_account'])?$result['repayment_account']:0;
		 
		//最近收款时间和时间
        $curDayTime = date("Y-m-d");
        $curDayTimeStr = strtotime($curDayTime);
		$sql = "select repay_time,repay_account  from `{borrow_collection}` where tender_id in ( select p2.id from `{borrow_tender}`  as p2 left join `{borrow}` as p3 on p2.borrow_id=p3.id where (p3.status=3 or (p3.status=1 and p3.is_lz=1)) and p2.user_id = '{$user_id}' and p2.status=1) and repay_time > ".$curDayTimeStr." and status=0 order by repay_time asc";

        $result = $mysql->db_fetch_array($sql);
		$_result['new_collection_time'] = isset($result['repay_time'])?$result['repay_time']:0;
		$_result['new_collection_account'] = isset($result['repay_account'])?$result['repay_account']:0;
		
		//网站垫付总额
			//最近收款时间和时间
		$sql = "select sum(repay_account) as num_late_repay_account ,sum(interest) as num_late_interes from `{borrow_collection}` where tender_id in ( select id from `{borrow_tender}` where user_id = '{$user_id}' and status=1)  and status=2 order by repay_time asc";
		$result = $mysql->db_fetch_array($sql);
		$_result['num_late_repay_account'] = $result['num_late_repay_account'];
		$_result['num_late_interes'] = $result['num_late_interes'];
		
		$_result['jin_account'] = $_result['collection_wait']-$_result['wait_payment']+$_result['total'];//净资产

		//收购的债权总额
		$record = $mysql->db_fetch_array('select sum(account) as account,sum(creditor_account) as creditor_account from `{creditor_record}` where status=1 and user_id='.$user_id);
		$record['record_interes'] = $record['creditor_account']-$record['account'];
		$_result['sgzq'] = $record;
		return $_result;
		
	}
	/**
	 * 判断用户是否有未处理的标
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetOnes($data = array()){
		global $mysql,$_G;
		$user_id = $data['user_id'];
		$id = $data['id'];
		/*
		$sql = "select * from {borrow} where status<2 and user_id='{$user_id}' and biao_type!='lz'";
		$result = $mysql->db_fetch_array($sql);
		if ($result != false){
			echo "<script>alert('您已经有一个借款标，请处理好借款标再进行借款!');location.href='/index.php?user&q=code/borrow/publish';</script>";
			exit;
		}
		*/
		if ($id=="") {
			$sql = "select value from {credit} where user_id='{$user_id}'";
			$result = $mysql->db_fetch_array($sql);
			if ($result==false || $result['value']<30){
/*				echo "<script>alert('您的信用积分还未到30分，请先上传资料认证');location.href='/index.php?user&q=code/user/realname';</script>";
					exit;
*/			}
		}else{
			$sql = "select p1.* ,p2.username,p2.realname from {borrow} as p1 
					  left join {user} as p2 on p1.user_id=p2.user_id 
					  where p1.user_id=$user_id and p1.id=$id and (p1.status=0 or p1.status=-1)
					";
			$result = $mysql->db_fetch_array($sql);
			if ($result == false){
				echo "<script>alert('您操作有误，请不要乱操作');location.href='/index.php?user&q=code/borrow/publish';</script>";
				exit;
			}else{
				return $result;
			}
		}
	}
	
	/**
	 * 获取标的详情
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetInvest($data = array()){
		global $mysql,$_G;
		$id = $data['id'];
		//获取借款标的响应信息
		$sql = "select * from `{borrow}`  where  id = $id";
		$result['borrow'] = $mysql->db_fetch_array($sql);
		if ($result['borrow']==false){
			return self::ERROR;
		}
		$user_id = $result['borrow']['user_id'];
		//获取用户信息以及用户的积分
		$sql = "select p1.*,p2.value as credit_jifen,p3.pic as credit_pic from `{user}` as p1 
				left join {credit} as p2 on p1.user_id=p2.user_id 
				left join {credit_rank} as p3 on p2.value<=p3.point2  and p2.value>=p3.point1 
				where  p1.user_id=$user_id";
		$result['user'] = $mysql->db_fetch_array($sql);
		//获取用户的基本资料
		$sql = "select * from `{userinfo}`  where  user_id=$user_id";
		$result['userinfo'] = $mysql->db_fetch_array($sql);
		//获取进度
		$result['borrow']['other'] = $result['borrow']['account'] - $result['borrow']['account_yes'];
		$scaleValue = 100*$result['borrow']['account_yes']/$result['borrow']['account'];
        if($scaleValue>99.95 && $scaleValue<99.99999999){
             $result['borrow']['scale']=99.9;
        }else{
             $result['borrow']['scale'] = round(100*$result['borrow']['account_yes']/$result['borrow']['account'],1);
        }
		$result['borrow']['scale_width'] = round((20*$result['borrow']['account_yes']/$result['borrow']['account']))*7;
		$result['borrow']['lave_time'] = $result['borrow']['verify_time'] + $result['borrow']['valid_time']*24*60*60-time();
		$result['borrow']['rep_time'] = $result['borrow']['end_time'] - time();
		$_interest = self::EqualInterest(array("account"=>100,"year_apr"=> $result['borrow']['apr'],"month_times"=> $result['borrow']['time_limit'],"type"=>"all","borrow_style"=>$result['borrow']['style'],"isday"=>$result['borrow']['isday'],"time_limit_day"=>$result['borrow']['time_limit_day']));
		//repair by weego for 天标 20120525
		$result['borrow']['interest'] = $_interest['repayment_account']-100;
		//获取用户的资金账号信息
        if($_G['user_id'] == ""){
              $sql = "select * from `{account}`  where  user_id=-1";
        }else{
              $sql = "select * from `{account}`  where  user_id={$_G['user_id']}";
        }
		$result['user_account'] = $mysql->db_fetch_array($sql);
		//获取用户的资金账号信息
		$sql = "select p1.*,p2.username as kefu_username,p2.wangwang as kefu_wangwang,p2.qq as kefu_qq from `{user_cache}` as  p1 left join `{user}` as p2 on p2.user_id=p1.kefu_userid  where  p1.user_id={$user_id}";
		$result['user_cache'] = $mysql->db_fetch_array($sql);
		$result['borrow_all'] = self::GetBorrowAll(array("user_id"=>$user_id));
		//获取投资的担保额度
		/*
        if($_G['user_id'] == ""){
             $result['amount']=0;
        }else{
             $result['amount'] =  self::GetAmountOne($_G['user_id']);
        }*/
		//获取担保进度
		//$result['borrow']['vouch_other'] = $result['borrow']['account'] - $result['borrow']['vouch_account'];
		//$result['borrow']['vouch_scale'] = round(100*$result['borrow']['vouch_account']/$result['borrow']['account'],1);
		//$result['borrow']['vouchscale_width'] = round((20*$result['borrow']['vouch_account']/$result['borrow']['account']))*7;
		//获取前台显示的图片列表
		//$result['borrow']['attestation'] = self::GetBorrowAttestationList(array('attestationid'=>$result['borrow']['show_attestation']));
		$result['borrow']['xgxmzl'] = unserialize($result['borrow']['xgxmzl']);
		$result['borrow']['dyzl'] = unserialize($result['borrow']['dyzl']);
		$result['borrow']['bxbzzl'] = unserialize($result['borrow']['bxbzzl']);
		//获取用户所有认证资料的认证状态
		$result['user_attestation_list'] = $mysql->db_fetch_arrays("select p1.*,p2.name type_name from {attestation} p1 left join {attestation_type} p2 on p1.type_id=p2.type_id where p1.status=1 and p1.upload_type=1 and p1.user_id={$user_id}");
		return $result;
	}
	/**
	 * 获取用户的详情
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetU($data = array()){
		global $mysql,$_G;
		$user_id = $_G['U_uid'];
		if($user_id == ""){
			return false;
		}
		//获取用户信息以及用户的积分
		$sql = "select p1.*,p2.value as credit_jifen,p3.pic as credit_pic from `{user}` as p1 
				left join {credit} as p2 on p1.user_id=p2.user_id 
				left join {credit_rank} as p3 on p2.value<=p3.point2 and p2.value>=p3.point1 
				where  p1.user_id=$user_id";
		$result['user'] = $mysql->db_fetch_array($sql);
		//获取用户的基本资料
		$sql = "select * from `{userinfo}`  where  user_id=$user_id";
		$result['userinfo'] = $mysql->db_fetch_array($sql);
		//获取用户的资金账号信息
		$sql = "select * from `{account}` where user_id={$user_id}";
		$result['account'] = $mysql->db_fetch_array($sql);
		//获取用户的资金账号信息
		//$sql = "select p1.*,p2.username as kefu_username,p2.wangwang as kefu_wangwang,p2.qq as kefu_qq from `{user_cache}` as  p1 left join `{user}` as p2 on p2.user_id=p1.kefu_userid  where  p1.user_id={$user_id}";
		//$result['user_cache'] = $mysql->db_fetch_array($sql);
		//$result['borrow_all'] = self::GetBorrowAll(array("user_id"=>$user_id));
		//获取投资的担保额度
		//$result['amount'] =  self::GetAmountOne($_G['U_uid']);
		//$sql = "select * from `{user}` where user_id = '{$user_id}'  ";
		$result_se = $mysql->db_fetch_array($sql);
		$result['phone_status']=$result_se['phone_status'];
		$result['video_status']=$result_se['video_status'];
		$result['scene_status']=$result_se['scene_status'];
		return $result;
	}
	/**
	 * 添加借款标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Add($data = array()){
		global $mysql;global $_G;
		$biaotype_info = self::get_biao_type_info(array("biao_type"=>$data['biao_type']));
		$max_amount = $biaotype_info['max_amount'];
		$min_amount = $biaotype_info['min_amount'];
		$max_apr = $biaotype_info['max_interest_rate'] * 100;
		$min_apr = $biaotype_info['min_interest_rate'] * 100;
		$is_day = $biaotype_info['day_model'];
		if (!isset($data['user_id']) && trim($data['user_id'])==""){
			return self::NO_LOGIN;
		}
		if (!isset($data['name']) && trim($data['name'])==""){
			return self::BORROW_NAME_NO_EMPTY;
		}
		if (!isset($data['account']) || trim($data['account'])==""){
			return self::BORROW_ACCOUNT_NO_EMPTY;
		}
		if($data['account'] > $max_amount){
			return self::BORROW_ACCOUNT_NO_MAX;
		}
		if($data['account'] < $min_amount){
			return self::BORROW_ACCOUNT_NO_MIN;
		}
		if (!isset($data['apr']) || trim($data['apr'])==""){
			return self::BORROW_APR_NO_EMPTY;
		}
		if ($data['apr']>$max_apr){
			return self::BORROW_APR_NO_MAX;
		}
		if ($data['apr']<$min_apr){
			return self::BORROW_APR_NO_MIN;
		}
		if($biaotype_info['available'] == 0){
			$msg = "不能发此类型标";
			return $msg;
		}
		if($is_day == 0 && $data['isday']==1){
			return self::BORROW_DAY_MODEL;
		}
		$classname = $data['biao_type']."biaoClass";
		$dynaBiaoClass = new $classname();
		$re = $dynaBiaoClass->get_biaotype_info();
		if($re['is_vip_borrow']==1 && $_G['user_result']['vip_status']!=1 && $_G['user_result']['vip_status']!=2){
			return "必须成为vip才能发布此标";
		}
		$add_result = $dynaBiaoClass->add($data);
		return $add_result;
	}
	/**
	 * 修改借款标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Update($data = array()){
		global $mysql;
		$user_id = $data['user_id'];
        if ($data['user_id'] == "") {
            return self::ERROR;
        }
		$_sql = "";
		$sql = "update `{borrow}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where user_id = '$user_id' and id='{$data['id']}' and (status=0 or status=-1)";
        return $mysql->db_query($sql);
	}
	/**
	 * 修改借款标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Action($data = array()){
		global $mysql;
		$id = $data['id'];
        if ($data['id'] == "") {
            return self::ERROR;
        }
		foreach($data['id'] as $key => $value){
			$sql = "update `{borrow}` set ";
			$sql .= "`flag` = '{$data['flag'][$key]}',`view_type` = '{$data['view'][$key]}' where id = '{$value}'";
			 $mysql->db_query($sql);
		}
		 return true;
	}
	/**
	 * 审核借款标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Verify($data = array()){
		global $mysql;
		$id = $data['id'];
		unset($data['id']);
		$sql = "update `{borrow}` set id=$id";
		foreach ($data as $key=>$value){
			$sql .= ",`".$key."`='".$value."'";
		}
		$sql .= " where id=$id";
		$result = $mysql->db_query($sql);
        return $result;
	}
	/**
	 * 删除
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	/*
	public static function Delete($data = array()){
		global $mysql;
		$id = $data['id'];
		if (!is_array($id)){
			$id = array($id);
		}
		if (isset($data['status']) && $data['status']!=""){
			$_sql .= " and status ='".$data['status']."'";
		}
		if (isset($data['user_id'])  && $data['user_id']!=""){
			$_sql = " and user_id={$data['user_id']} ";
		}
		//$sql = "delete from {borrow}  where id in (".join(",",$id).") $_sql";
		//return $mysql->db_query($sql);
		return true;
	}
	*/
	/**
	 * 撤回借款标
	 * @param Array $data
	 * @return Boolen
	 */
	public static function Cancel($data = array()){
		global $mysql;
		global $_G;
		$_sql = " where 1=1 ";
		if (isset($data['id']) && $data['id']!=""){
			$_sql .= " and id={$data['id']} ";
		}else{
			return false;
		}
		if (isset($data['user_id'])  && $data['user_id']!=""){
			$_sql .= " and user_id={$data['user_id']} ";
		}
		$ssql = "select * from {borrow}  where id=".$data['id']." ";
		$borrow_repayment_result = $mysql->db_fetch_array($ssql);
		//liukun add for bug 60 begin
		if($borrow_repayment_result['status'] == 3) {
			return false;
		}
		if($borrow_repayment_result['biao_type']=="lz"){
			return false;
		}
		//liukun add for bug 60 end
		//设置借款标为取消状态
		mysql_query("start transaction");//开启事务
		$sql = "update  {borrow} set status=5 $_sql";
		$re = $mysql->db_query($sql);
		if ($re==false){
			mysql_query("rollback");
			return false;
		}
		$borrow_userid = $borrow_repayment_result['user_id'];
		//返回所有投资者的金钱。
		$sql = "select p1.*,p2.status as borrow_status,p2.name as borrow_name,p3.pIpsAcctNo from {borrow_tender} as p1 left join `{borrow}` as p2 on p1.borrow_id=p2.id left join {user} as p3 on p1.user_id=p3.user_id where p1.borrow_id={$data['id']}";
		$result = $mysql->db_fetch_arrays($sql);
		$sendRemind = array();
		foreach ($result as $key => $value){
			if($value['borrow_status']!=5){
				mysql_query("rollback");
				return false;
			}
			if($value['status']!=2 && $value['status']!=-1){
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$log['user_id'] = $value['user_id'];
				$log['type'] = "invest_false";
				$log['money'] = $value['account'];
				$log['total'] = $account_result['total'];
				$log['use_money'] = $account_result['use_money']+$log['money'];
				$log['no_use_money'] = $account_result['no_use_money']-$log['money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = 0;
				$log['borrow_id'] = $data['id'];
				$log['remark'] = "招标[<a href=\'/invest/a{$data['id']}.html\' target=_blank>{$value['borrow_name']}</a>]失败返回的投标额";
				$re = accountClass::AddLog($log);
				if($re==false || $account_result==false){
					mysql_query("rollback");
					return false;
				}
				$tender_result[] = array('user_id'=>$value['user_id'],'pIpsAcctNo'=>$value['pIpsAcctNo'],'account'=>$value['account']);
				//提醒设置
				$remind['nid'] = "loan_no_account";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $value['user_id'];
				$remind['title'] = "您所投资的标[{$value['borrow_name']}]已经撤消";
				$remind['content'] = "你所投资的标[<a href=\'/invest/a{$data['id']}.html\' target=_blank><font color=red>{$value['borrow_name']}</font></a>]在".date("Y-m-d",time())."已经撤消了，您所投标的金额已解冻了。";
				$remind['type'] = "system";
				$sendRemind[] = $remind;
				$re = $mysql->db_query("update `{borrow_tender}` set status=2 where id = '{$value['id']}'");
				$re_1 = $mysql->db_query("update `{borrow_collection}` set tender_status=2 where borrow_id={$data['id']}");
				if($re==false || $re_1==false){
					mysql_query("rollback");
					return false;
				}
			}
		}
		$classname = $borrow_repayment_result['biao_type']."biaoClass";
		$dynaBiaoClass = new $classname();
		$cancel_result = $dynaBiaoClass->cancel($borrow_repayment_result);
		if($cancel_result==false){
			mysql_query("rollback");
			return false;
		}else{
			if(IS_TG && !empty($tender_result)){
				$juser_result = $mysql->db_fetch_array('select * from {user} where user_id='.$borrow_repayment_result['user_id']);
				$a = tg_liubiao($borrow_repayment_result,$juser_result,$tender_result);
				$mysql->db_query("update {borrow} set `pErrCode`='{$a['pErrCode']}',`pErrMsg`='{$a['pErrMsg']}' where id={$borrow_repayment_result['id']} limit 1");
				if($a['pErrCode']=='0000'){
					mysql_query("commit");
					return true;
				}else{
					mysql_query("rollback");
					return false;
				}
			}else{
				mysql_query("commit");
				return true;
			}
			foreach($sendRemind as $key=>$value){
				remindClass::sendRemind($value);
			}
			return true;
		}
	}
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetTenderList($data = array()){
		global $mysql;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$username = empty($data['username'])?"":$data['username'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where 1=1 ";		 
		if (!empty($user_id)){
			$_sql .= " and p1.user_id = $user_id";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}
		if (isset($data['borrow_id']) && $data['borrow_id']!=""){
			$_sql .= " and p1.borrow_id = '{$data['borrow_id']}'";
		}
		if (isset($data['dotime2'])){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if( !isTimePatternT($dotime2))$dotime2 = "";
			if ($dotime2!=""){
				$_sql .= " and p1.addtime <= ".get_mktime($dotime2);
			}
		}
		if (isset($data['dotime1'])){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if( !isTimePatternT($dotime1))$dotime1 = "";
			if ($dotime1!=""){
				$_sql .= " and p1.addtime >= ".get_mktime($dotime1);
			}
		}
		if (isset($data['status']) && $data['status']!=""){
			$_sql .= " and p1.status in ({$data['status']})";
		}
		if(!isset($data['status']) || $data['status']==""){
			$_sql .= " and p1.status!=-1";
		}
		if (isset($data['borrow_status']) && $data['borrow_status']!=""){
			$_sql .= " and (p3.status in ({$data['borrow_status']}))";
		}
		if (isset($data['keywords']) && $data['keywords']!=""){
			$_sql .= " and p1.name like '%".safegl($data['keywords'])."%'";
		}
		$_select = "p1.*,p1.account as tender_account,p1.money as tender_money,p2.user_id as borrow_userid,p2.username as op_username,p4.username as username,p3.apr,p3.time_limit,p3.time_limit_day,p3.isday,p3.name as borrow_name,p3.id as borrow_id,p3.account ,p3.account_yes,p3.end_time,p3.style,p5.value as credit_jifen,p6.pic as credit_pic";
		$sql = "select SELECT from `{borrow_tender}` as p1
				left join `{borrow}` as p3 on p1.borrow_id=p3.id 
				left join `{user}` as p2 on p3.user_id = p2.user_id
				left join `{user}` as p4 on p4.user_id = p1.user_id
				 left join {credit} as p5 on p1.user_id=p5.user_id 
				left join {credit_rank} as p6 on p5.value<=p6.point2  and p5.value>=p6.point1
		 {$_sql}  order by p1.addtime desc LIMIT";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.id desc', $_limit), $sql));
			$i=1;
			foreach($result as $key => $value){
				//获取进度
				$len=strlen($value['username']);
				$content='';
$count=0;
for($i=0;$i<$len;$i++){
   if(ord(substr($value['username'],$i,1))>127){
    $content.=substr($value['username'],$i,2);
    $i++;
   }else{
    $content.=substr($value['username'],$i,1);
   }
   if(++$count==3){
    break;
   }
}
				$result[$key]['show_username'] = $content.str_repeat('*',$len-3);
				$result[$key]['other'] = $value['account'] - $value['account_yes'];
				$result[$key]['scale'] = round(100*$value['account_yes']/$value['account'],1);
				$result[$key]['scale_width'] = round((20*$value['account_yes']/$value['account']))*7;
				$result[$key]['repayment_noaccount'] = $value['repayment_account'] - $value['repayment_yesaccount'];
				$_data['year_apr'] = $value['apr'];
				$_data['account'] = $value['tender_account'];
				$_data['month_times'] = $value['time_limit'];
				$_data['borrow_style'] = $value['style'];
				$_data['type'] = "all";
				///add by weego for 天标
				$_data['isday'] = $value['isday'];
				$_data['time_limit_day'] = $value['time_limit_day'];
				$result[$key]['equal'] = self::EqualInterest($_data);
				$result[$key]['i'] = $i;
				$i++;
			}
			return $result;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		$i=1;
		foreach($list as $key => $value){
			//获取进度
			if(empty($value['account'])) $value['account']=1;
			$_data['year_apr'] = $value['apr'];
			$_data['account'] = $value['account'];
			$_data['month_times'] = $value['time_limit'];
			$_data['borrow_style'] = $value['style'];
			///add by weego for 天标
			$_data['isday'] = $value['isday'];
			$_data['time_limit_day'] = $value['time_limit_day'];
			$list[$key]['equal'] = self::EqualInterest($_data);
			$list[$key]['other'] = $value['account'] - $value['account_yes'];
			$list[$key]['scale'] = round(100*$value['account_yes']/$value['account'],1);
			$list[$key]['scale_width'] = round((20*$value['account_yes']/$value['account']))*7;
			$list[$key]['repayment_noaccount'] = $value['repayment_account'] - $value['repayment_yesaccount'];
			$result[$key]['i'] = $i;
			$i++;
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	/**
	 * 担保列表
	 *
	 * @return Array
	 */
	function GetVouchList($data = array()){
		global $mysql;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$username = empty($data['username'])?"":$data['username'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where 1=1";		 
		if (!empty($user_id)){
			$_sql .= " and p1.user_id = $user_id";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}
		if (isset($data['borrow_id']) && $data['borrow_id']!=""){
			$_sql .= " and p1.borrow_id = '{$data['borrow_id']}'";
		}
		if (isset($data['dotime2'])){
			if( !isTimePatternT($dotime2))$dotime2 = "";
			if ($dotime2!=""){
				$_sql .= " and p1.addtime < ".get_mktime($dotime2);
			}
		}
		if (isset($data['dotime1'])){
			if( !isTimePatternT($dotime1))$dotime1 = "";
			if ($dotime1!=""){
				$_sql .= " and p1.addtime > ".get_mktime($dotime1);
			}
		}
		if (isset($data['status']) && $data['status']!=""){
			$_sql .= " and p1.status in ({$data['status']})";
		}
		if (isset($data['borrow_status']) && $data['borrow_status']!=""){
			$_sql .= " and p3.status in ({$data['borrow_status']})";
		}
		$_select = "p1.*,p2.username,p3.name as borrow_name,p3.time_limit as borrow_period,p3.account as borrow_account,p4.username as borrow_username";
		$sql = "select SELECT from `{borrow_vouch}` as p1
				left join `{user}` as p2 on p2.user_id = p1.user_id
				left join `{borrow}` as p3 on p1.borrow_id = p3.id
				left join `{user}` as p4 on p4.user_id = p3.user_id
		 {$_sql}  order by p1.addtime desc LIMIT";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.id desc', $_limit), $sql));
			return $result;
		}			 
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	/**
	 * 投标的列表
	 *
	 * @return Array
	 */
	function GetTenderUserList($data = array()){
		global $mysql;
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where 1=1 ";		 
		if (!empty($data['user_id'])){
			$_sql .= " and p2.user_id = {$data['user_id']}";
		}
		if (isset($data['username'])){
			if ($data['username']=="request"){
				$_sql .= " and p3.username like '%{$_REQUEST['username']}%'";
			}
		}
		if (isset($data['borrow_id']) && $data['borrow_id']!=""){
			$_sql .= " and p1.borrow_id = '{$data['borrow_id']}'";
		}
		if (isset($data['borrow_status']) && $data['borrow_status']!=""){
			$_sql .= " and p2.status = '{$data['borrow_status']}'";
		}
		if (isset($data['dotime2'])){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if( !isTimePatternT($dotime2))$dotime2 = "";
			if ($dotime2!=""){
				$_sql .= " and p1.addtime < ".get_mktime($dotime2);
			}
		}
		if (isset($data['dotime1'])){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if( !isTimePatternT($dotime1))$dotime1 = "";
			if ($dotime1!=""){
				$_sql .= " and p1.addtime > ".get_mktime($dotime1);
			}
		}
		$_select = "p1.*,p2.name as borrow_name,p3.username";
		$sql = "select SELECT from {borrow_tender} as p1 
					left join {borrow} as p2 on p2.id=p1.borrow_id
					left join {user} as p3 on p1.user_id=p3.user_id
					$_sql order by p1.id desc
					";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.addtime desc', $_limit), $sql));
			return $result;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		foreach ($list as $key => $value){
			$list[$key]['repayment_noaccount'] = $value['repayment_account']-$value['repayment_yesaccount'];
			$list[$key]['repayment_nointerest'] = $value['repayment_account']-$value['repayment_yesaccount']-$value['account'];
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	/**
	 * 查看投标的信息
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetTenderOne($data = array()){
		global $mysql;
		$id = $data['id'];
		$sql = "select * from {borrow_tender}  where id=$id";
		$result = $mysql->db_fetch_array($sql);
		//获取用户的基本资料
		$sql = "select sum(money) as total from {borrow_tender}  where  borrow_id=$id";
		$_result = $mysql->db_fetch_array($sql);
		$result['other'] = $result['borrow']['account'] - $_result['total'];
		$result['scale'] = round(100*$_result['total']/$result['borrow']['account'],1);
		$result['scale_width'] = round((20*$_result['total']/$result['borrow']['account']))*7;
		return $result;
	}
	/**
	 * 添加投标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function AddTender($data = array()){
		global $mysql,$_G;
		include_once(ROOT_PATH."modules/account/account.class.php");
		if (!isset($data['borrow_id']) || $data['borrow_id']==""){
			return self::ERROR;
		}
		if ($_G['user_result']['islock']==1){
			return "您账号已经被锁定，不能进行投标，请跟管理员联系";
		}
		$borrow_id = $data['borrow_id'];
		$resultBorrow = self::GetOne(array("id"=>$data['borrow_id']));
		$classname = $resultBorrow['biao_type']."biaoClass";
		$dynaBiaoClass = new $classname();
		//liukun add for bug 122 begin
		$user_id = $data["user_id"];
		$sql = "Select count(*) as num From`{borrow_tender}` where borrow_id={$borrow_id} and user_id={$user_id}";
		$tenderResult = $mysql->db_fetch_array($sql);
		$tendNum=$tenderResult["num"];
		$max_tender_times = $dynaBiaoClass->get_max_tender_times();
		if ($tendNum >= $max_tender_times){
			//$msg = "对不起，你已经超过最大投标次数(".$max_tender_times."次),谢谢。";
			//return $msg;
		}
		mysql_query("start transaction");
		$sql = "update {borrow} set account_yes=account_yes+{$data['account']},tender_times=tender_times+1  where id='{$data['borrow_id']}' and account>=account_yes+{$data['account']}";
		$re = $mysql->db_query($sql);//更新已经投标的钱
		//add by weego for 投资超标 20130114
		$is_save=$mysql->db_affected_rows();//取得前一次 MySQL 操作所影响的记录行数
		if($is_save==1 && $re==true){
			//投资成功
		}else{
			mysql_query("rollback");
			return "此标已满！投标失败！";
		}
		if($data['is_update']==true){
			$sql = '';
			$tender_id = $data['tender_id'];
			$w = ' where id='.$data['tender_id'];
			unset($data['is_update']);
			unset($data['tender_id']);
			foreach($data as $key => $value){
				$sql .= ",`$key` = '$value'";
			}
			$sql = substr($sql,1);
			$sql = 'update `{borrow_tender}` set '.$sql.$w.' limit 1';
			$a = $mysql->db_query($sql);
			$tender_id = $a==true?$tender_id:0;
		}else{
			$sql = "insert into `{borrow_tender}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
			if(!isset($data['trade_no'])){
				$data['trade_no'] = 'tb'.time().$data['user_id'].rand(1,100);
			}
			foreach($data as $key => $value){
				$sql .= ",`$key` = '$value'";
			}
			$mysql->db_query($sql);
			$tender_id = $mysql->db_insert_id();
		}
		
		if ($tender_id>0){
			//liukun add for bug 278 begin
			//如果成功，则将还款信息输进表里面去
			$result = self::GetOne(array("id"=>$data['borrow_id']));
			$borrow_name = $result['name'];
			$borrow_id = $result['id'];
			$eq['account'] = $data['account'];
			$eq['year_apr'] = $result['apr'];
			$eq['month_times'] = $result['time_limit'];
			$eq['borrow_time'] = $result['repayment_time'];
			$eq['borrow_style'] = $result['style'];
			///add by weego for 天标
			$eq['isday'] = $result['isday'];
			$eq['time_limit_day'] = $result['time_limit_day'];
			$result = self::EqualInterest($eq);
			$repayment_account = 0;
			foreach ($result as $key => $value){
				$repayment_account += $value['repayment_account'];
				//将还款信息写进去
				//liukun add for bug 227 begin
				$sql = "insert into {borrow_collection} set `addtime` = '".time()."',`addip` = '".ip_address()."',`tender_id`='{$tender_id}',`tender_status`=5,`order`='{$key}',`repay_time`='{$value['repayment_time']}',
				`repay_account`='{$value['repayment_account']}',`interest`='{$value['interest']}',`capital`='{$value['capital']}',`borrow_id`={$data['borrow_id']},`user_id`={$data['user_id']}";
				$re = $mysql ->db_query($sql);
				if($re==false){
					mysql_query("rollback");
					return false;
				}
				//liukun add for bug 227 end
			}
			$_interest = round(($repayment_account-$data['account']),2);
			$sql = " update {borrow_tender} set repayment_account='{$repayment_account}',wait_account ='{$repayment_account}',interest = '{$_interest}',wait_interest = '{$_interest}' where id={$tender_id}";
			$re_1 = $mysql ->db_query($sql);
			//liukun add for bug 278 end
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$data['user_id']));//获取当前用户的余额
			$log['user_id'] = $data['user_id'];
			$log['type'] = "tender";
			$log['money'] = $data['account'];
			$log['total'] = $account_result['total'];
			$log['use_money'] =  $account_result['use_money']-$log['money'];
			$log['no_use_money'] =  $account_result['no_use_money']+$log['money'];
			$log['collection'] =  $account_result['collection'];
			$log['to_user'] = 0;
			$log['borrow_id'] = $borrow_id;
			$log['remark'] = "冻结投资者的投标资金,对标[<a href=\'/invest/a{$borrow_id}.html\'>{$borrow_name}<\/a>]";
			$re_2 = accountClass::AddLog($log);//添加记录
			if($re_1==false || $re_2==false ||$account_result==false){
				mysql_query("rollback");
				return false;
			}
			$tender_data['user_id'] = $data['user_id'];
			$tender_data['account'] = $data['account'];
			$tender_data['tender_id'] = $tender_id;
			$tender_data['borrow_result'] = $resultBorrow;

			$tender_result = $dynaBiaoClass->tender($tender_data);
			if($tender_result==false){
				mysql_query("rollback");
			}else{
				mysql_query("commit");
			}
			return $tender_result;
		}else{
			mysql_query("rollback");
			$msg = "投标失败。";
			return $msg;
		}
	}
	/**
	 * 添加担保
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function AddVouch($data = array()){
		global $mysql,$_G;
		if (!isset($data['borrow_id']) || $data['borrow_id']==""){
			return self::ERROR;
		}
		if ($_G['user_result']['islock']==1){
			return "您账号已经被锁定，不能进行投标，请跟管理员联系";
		}
		if (1==2){
		$sql = "insert into `{borrow_vouch}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$mysql->db_query($sql);
		$vouch_id = $mysql->db_insert_id();
		if ($vouch_id>0){
			$sql = "update  {borrow}  set vouch_account=vouch_account+{$data['account']},vouch_times=vouch_times+1  where id='{$data['borrow_id']}'";
			$mysql->db_query($sql);//更新已经担保的钱
			
			//添加额度记录
			$amountlog_result = self::GetAmountOne($data['user_id'],"tender_vouch");
			$amountlog["user_id"] = $data['user_id'];
			$amountlog["type"] = "tender_vouch_sucess";
			$amountlog["amount_type"] = "tender_vouch";
			$amountlog["account"] = $data['account'];
			$amountlog["account_all"] = $amountlog_result['account_all'];
			$amountlog["account_use"] = $amountlog_result['account_use'] - $amountlog['account'];
			$amountlog["account_nouse"] = $amountlog_result['account_nouse'] + $amountlog['account']; 
			$amountlog["remark"] = "担保成功";
			self::AddAmountLog($amountlog);
			return true;
		}else{
			return false;
		}
		}
		$borrow_result = self::GetOne(array("id"=>$data['id'],"tender_userid"=>$_G['user_id']));//获取借款标的单独信息
		$user_id = $_G['user_id'];
		$classname = $borrow_result['biao_type']."biaoClass";
		$dynaBiaoClass = new $classname();
		//liukun add for bug 123 end
		$userPermission = $dynaBiaoClass->getUserPermission($user_id);
		if ($userPermission['is_restructuring'] == 1){
			$msg =  array("你目前是债务重组中，不能担保。");
			return $msg;
		}
		//liukun add for bug 123 end
		if ($borrow_result['verify_time'] == "" || $borrow_result['status'] != 1){
			$msg = array("此标尚未通过审核");
			return $msg;
		}elseif ($borrow_result['verify_time'] + $borrow_result['valid_time']>time()){
			$msg = array("此标已过期");
			return $msg;
		}
		$data['borrow_result'] = $borrow_result;
		$vouch_result = $dynaBiaoClass->vouch($data);
		return $vouch_result;
	}
	/**
	 * 流转标自动回购
	 * add by weego 20121208
	 * @param Array $data
	 * @return Array
	 */
	public static function autoLZRepay($data = array()){
		global $mysql,$_G,$rdGlobal;
		$tqtime=$rdGlobal['lz_reBackTime']; //流转标提前回购的时间设置
		$tqtime=time()+$tqtime;
		//获取还款的列表
	 	$sql="select a1.id as tender_id,a2.id as borrow_id from (select p1.* from {borrow_tender} as p1 
				left join {borrow_collection} as p2 on ((p2.tender_id=p1.id)) where p2.status=0 and p2.repay_time<'{$tqtime}' ) as a1 
				join {borrow} as a2 on ((a1.borrow_id=a2.id)) 
				where a2.is_lz=1";
		$result = $mysql->db_fetch_arrays($sql);
		$rebuyAllmoney='0'; //初始化回购的总金额
		$biao_type = new lzbiaoClass();
		$lzbiaotype = $biao_type->get_biaotype_info();
		$_fee = $lzbiaotype['interest_fee_rate'];
		$sendSMS = array();
		$sendRemind = array();
		mysql_query("start transaction");
		if (!empty($result)){
			foreach ($result as $key => $value){
				//获取tender的信息
				$sql="select p1.*,p2.vip_status from {borrow_tender} as p2 left join {user_cache} as p2 on p1.user_id=p2.user_id where p1.id='{$value['tender_id']}'";
				$tender_result = $mysql->db_fetch_array($sql);
				if($tender_result['vip_status']==1){
					$_fee = $lzbiaotype['interest_fee_rate_vip'];
				}
				//获取borrow的信息
				$sql="select * from {borrow} where id='{$tender_result['borrow_id']}'";
				$borrow_repayment_result = $mysql->db_fetch_array($sql);
				///////////////////////////////////////////////////////////////////////////////////////////////
				$reBuyAccount=$tender_result['account']; //本次回购的本金
				$reBuyInterest=$tender_result['interest']; //本次回购的利息
				$reBuyAccountTotal=$tender_result['repayment_account']; //本次回购的本息
				$reBuyUid=$tender_result['user_id']; //本次被回购的投资者UID
				$reBuyBorrowUid=$borrow_repayment_result['user_id']; //本次回购的借款者UID
				$reBuyBorrowid=$borrow_repayment_result['id']; //本次回购的借款borrow_ID
				$borrow_account=$borrow_repayment_result['account'];//标的总借款金额
				$reBuyTenderid=$tender_result['id']; //本次回购tender_id
				//////////////奖励相关/////////////////////
				$part_account = $borrow_repayment_result['part_account']; //固定的奖励总金额
				$award =$borrow_repayment_result['award'];  //1 固定金额分配  2 按照比例
				$funds =$borrow_repayment_result['funds'];  //比例奖励的百分数

				$rebuyAllmoney =$rebuyAllmoney +$reBuyAccountTotal; //累计回购的本息总金额
				///////////////////////////////////////////////////////////////////////////////////////////////
				 
				//更新借款者积分   
				$credit['nid'] = "advance_day";
				$credit_result = creditClass::GetTypeOne(array("nid"=>$credit['nid']));
				$credit['user_id'] = $reBuyBorrowUid;
				$credit['value'] = $credit_result['value'];
				$credit['op_user'] = $_G['user_id'];
				$credit['op'] = 1;//增加
				$credit['type_id'] = $credit_result['id'];
				$credit['remark'] = "还款成功加{$credit_result['value']}分";
				$re = creditClass::UpdateCredit($credit);
				if($credit_result==false || $re==false || $borrow_repayment_result==false || $tender_result==false){
					mysql_query("rollback");
					return false;
				}
				//扣除借款者的还款金额
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$reBuyBorrowUid));
                $account_log['user_id'] =$reBuyBorrowUid;
                $account_log['type'] = "repayment";
                $account_log['money'] = $reBuyAccountTotal;
                $account_log['total'] =$account_result['total']-$account_log['money'];
                $account_log['use_money'] = $account_result['use_money']-$account_log['money'];
                $account_log['no_use_money'] = $account_result['no_use_money'];
                $account_log['collection'] = $account_result['collection'];
                $account_log['to_user'] = "0";
                $account_log['borrow_id'] = $borrow_repayment_result['id'];
                $account_log['remark'] = "对[<a href=\'/invest/a{$borrow_repayment_result['id']}.html\' target=_blank>{$borrow_repayment_result['name']}</a>]还款";
                $re = accountClass::AddLog($account_log);
				if($account_result==false || $re==false){
					mysql_query("rollback");
					return false;
				}
				$_username = $mysql->db_fetch_arrays("select username from {user} where user_id=".$reBuyBorrowUid);
				$sendSMS[] = array('user_id'=>$reBuyBorrowUid,'content'=>"温馨提醒：您的账号：".$_username['username']."，已还款成功，感谢您对我们的支持与信赖！");
				
				//更新投资人的分期信息
				$sql = "update  `{borrow_collection}` set repay_yestime='".time()."',repay_yesaccount = repay_account ,status=1   where tender_id = '{$reBuyTenderid}'";
				$re = $mysql->db_query($sql);
				
				//更新投资的信息
				$sql = "update  `{borrow_tender}` set status=1,repayment_yesaccount= repayment_yesaccount + ".$reBuyAccountTotal.",wait_account = wait_account  - ".$reBuyAccountTotal." ,wait_interest = wait_interest - ".$reBuyInterest.",repayment_yesinterest  = repayment_yesinterest  + {$reBuyInterest}  where id = '{$reBuyTenderid}'";
				$re_1 = $mysql->db_query($sql);
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$reBuyUid));
				$account_log['user_id'] =$reBuyUid;
				$account_log['type'] = "invest_repayment";
				$account_log['money'] = $reBuyAccountTotal;
				$account_log['total'] = $account_result['total'];
				$account_log['use_money'] = $account_result['use_money']+$account_log['money'];
				$account_log['no_use_money'] = $account_result['no_use_money'];
				$account_log['collection'] =$account_result['collection'] -$account_log['money'];
				$account_log['to_user'] = $reBuyBorrowUid;
				$account_log['borrow_id'] = $borrow_repayment_result['id'];
				$account_log['remark'] = "客户对[<a href=\'/invest/a{$borrow_repayment_result['id']}.html\' target=_blank>{$borrow_repayment_result['name']}</a>]借款的还款";
				$re_2 = accountClass::AddLog($account_log);
				if($re_2==false || $re_1==false || $re==false || $account_result==false){
					mysql_query("rollback");
					return false;
				}
				//扣除投资的管理费
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$reBuyUid));
				$log['user_id'] = $reBuyUid;
				$log['type'] = "tender_mange";
				$log['money'] = $reBuyInterest*$_fee;
				$log['total'] = $account_result['total']-$log['money'];
				$log['use_money'] = $account_result['use_money']-$log['money'];
				$log['no_use_money'] = $account_result['no_use_money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = 0;
				$log['borrow_id'] = $borrow_repayment_result['id'];
				$log['remark'] = "用户成功还款扣除利息的管理费";
				$re = accountClass::AddLog($log);
				if($account_result==false || $re==false){
					mysql_query("rollback");
					return false;
				}
				$remind['nid'] = "loan_pay";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $reBuyUid;
				$remind['title'] = "客户对[{$borrow_repayment_result['name']}]借款的还款";
				$remind['content'] = "客户在".date("Y-m-d H:i:s")."对[<a href=\'/invest/a{$borrow_repayment_result['id']}.html\' target=_blank>{$borrow_repayment_result['name']}</a>]借款的还款,还款金额为{$value['repay_account']}";
				$remind['type'] = "system";
				$sendRemind[] = $remind;
			//投标奖励扣除和增加。	
			if ($award==1 || $award==2){
				if (!$rdGlobal['lz_awardfirst']){
					if ($award==1){
						$money = round(($reBuyAccount/$borrow_account)*$part_account,2);
					}elseif ($award==2){
						$money = round((($funds/100)*$reBuyAccount),2);
					}
					//发放奖励
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$reBuyUid));
					$log['user_id'] = $reBuyUid;
					$log['type'] = "award_add";
					$log['money'] = $money;
					$log['total'] = $account_result['total']+$money;
					$log['use_money'] = $account_result['use_money']+$money;
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = $reBuyBorrowUid;
					$log['borrow_id'] = $borrow_repayment_result['id'];
					$log['remark'] = "借款[<a href=\'/invest/a{$borrow_repayment_result['id']}.html\' target=_blank>{$borrow_repayment_result['name']}</a>]的奖励";
					$re = accountClass::AddLog($log);
					if($account_result==false || $re==false){
						mysql_query("rollback");
						return false;
					}
					//扣除奖励
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$reBuyBorrowUid));
					$log['user_id'] = $reBuyBorrowUid;
					$log['type'] = "award_lower";
					$log['money'] = $money;
					$log['total'] = $account_result['total']-$money;
					$log['use_money'] = $account_result['use_money']-$money;
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = $value['user_id'];
					$log['borrow_id'] = $borrow_repayment_result['id'];
					$log['remark'] = "扣除借款[<a href=\'/invest/a{$borrow_repayment_result['id']}.html\' target=_blank>{$borrow_repayment_result['name']}</a>]的奖励";
					$re = accountClass::AddLog($log);
					if($account_result==false || $re==false){
						mysql_query("rollback");
						return false;
					}
				}
			}
				 //更新借款标的已经认购金额
			$sql = "update {borrow} set account_yes= account_yes - {$reBuyAccount} where id={$reBuyBorrowid}";
			$result = $mysql -> db_query($sql);
			if($result==false){
				mysql_query("rollback");
				return false;
			}
			} //foreach end
		}//if end
		mysql_query("commit");
		foreach($sendRemind as $remind){
			remindClass::sendRemind($remind);
		}
		foreach($sendSMS as $value){
			sendSMS($value['user_id'],$value['content'],1);
		}
		return $rebuyAllmoney;
	}
	/**
	 * 还款
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function Repay($data = array()){
		global $mysql,$_G;
		$id = $data['id'];
		if ($id == "request"){
			$id = $_REQUEST['id'];
		}
		if($id<1) return self::ERROR;
		$_sql = "";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p2.user_id = '{$data['user_id']}'";
		}else{
			return self::ERROR;
		}
		$user_id = $data['user_id'];
		$current_time = isset($data['tg_repayment_time'])?$data['tg_repayment_time']:time();
		$sql = "select p1.*,p2.monthly_repayment as monthly_repayment,p2.is_mb as is_mb,p2.is_jin as is_jin,p2.is_fast as is_fast,p2.name as borrow_name,p2.repayment_account as all_repayment_account,p2.repayment_yesaccount as all_repayment_yesaccount,p2.user_id as borrow_userid,p2.repayment_yesinterest ,p2.time_limit,p2.isday,p2.time_limit_day,p2.forst_account,p2.account as borrow_account,p2.is_vouch,p2.success_time, p2.biao_type,p2.p_contract_no from {borrow_repayment} as p1,{borrow} as p2   where (p1.status=0 or p1.status=2) and p1.id=$id and p1.borrow_id=p2.id $_sql";
		$borrow_repayment_result = $mysql->db_fetch_array($sql);
		$borrow_id = $borrow_repayment_result["borrow_id"];
		$success_time = $borrow_repayment_result["success_time"];
		$borrow_userid = $borrow_repayment_result["borrow_userid"];
		if ($borrow_repayment_result==false){
			return self::ERROR;
		}
		if ($borrow_repayment_result['status']==1){
			return "此期已经还款，请不要乱操作";
		}
		//判断上一期是否已还
		if ($borrow_repayment_result['order']!=0){
			$_order = $borrow_repayment_result['order']-1;
			$sql = "select status from `{borrow_repayment}` where `order`=$_order and borrow_id={$borrow_repayment_result['borrow_id']}";
			$result = $mysql->db_fetch_array($sql);
			if ($result!=false && $result['status']!=1){
				return "你上期的借款还没还，请先还上期的";
			}
		}

		$biao_type = $borrow_repayment_result['biao_type'];
		$classname = $biao_type."biaoClass";
		$dynaBiaoClass = new $classname();
		$borrow_repayment_result['tg_repayment_time'] = $current_time;
		$late_result = $dynaBiaoClass->getLateInterest($borrow_repayment_result);
		//判断可用余额是否够还款
		$sql = "select * from {account} where user_id = '{$borrow_userid}'";
		$account_result = $mysql->db_fetch_array($sql);
		if ($account_result['use_money']<$borrow_repayment_result['repayment_account']+$late_result['late_interest']){
			return self::BORROW_REPAYMENT_NOT_ENOUGH;
		}
		//扣除可用余额还款部分
		//判断是否逾期，
		//没逾期，逾期（担保标，非担保标）
		//TODO 逾期问题没有这么简单，有可能是在网站垫付之前还款
		//这里这个还款LOG写得不合适，正常还款时流水并不还给网站
		//不过也可以认为是先还网站，再由网站还给用户，这种方式可以接受
		//所有还款都可以看作直接还给网站，网站再还给真正的债权人
		mysql_query("start transaction");
		//撤回该标的债权转让
		require_once ROOT_PATH."modules/creditor/creditor.class.php";
		$creditor = new creditorClass;
		$_a = $creditor->transfer_cf(array('status'=>1,'borrow_id'=>$borrow_id));
		if($_a==false){
			mysql_query("rollback");
			return false;
		}
		$account_result =  accountClass::GetOneAccount(array("user_id"=>$data['user_id']));
		$account_log['user_id'] =$data['user_id'];
		$account_log['type'] = "repayment";
		$account_log['money'] = $borrow_repayment_result['repayment_account'];
		$account_log['total'] =$account_result['total']-$account_log['money'];
		$account_log['use_money'] = $account_result['use_money']-$account_log['money'];
		$account_log['no_use_money'] = $account_result['no_use_money'];
		$account_log['collection'] = $account_result['collection'];
		$account_log['to_user'] = "0";
		$account_log['borrow_id'] = $borrow_repayment_result['borrow_id'];
		$account_log['remark'] = "对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]借款标的还款";
		$re = accountClass::AddLog($account_log);
		if($re==false || $account_result==false){
			mysql_query("rollback");
			return false;
		}
		$sendSMS = array();
		$_username = $mysql->db_fetch_array("select username from {user} where user_id=".$data['user_id']);
		$sendSMS[]=array('user_id'=>$data['user_id'],'content'=>"温馨提醒：您的账号：".$_username['username']."，已还款成功，感谢您对我们的支持与信赖！");
		//liukun add for bug 133 begin
		$_repay_time = $borrow_repayment_result['repayment_time'];
		$re_time = (strtotime(date("Y-m-d",$_repay_time))-strtotime(date("Y-m-d",$current_time)))/(60*60*24);
		if($re_time>4){//提前4天以上
			$credit['nid'] = "advance_3day";
		}elseif ($re_time>2 && $re_time<=4){//提前3天，4天
			$credit['nid'] = "advance_1day";
		}else{
			$credit['nid'] = "advance_day";
		}
		$result = creditClass::GetTypeOne(array("nid"=>$credit['nid']));
		$credit['user_id'] = $data['user_id'];
		$credit['value'] = $result['value'];
		$credit['op_user'] = isset($_G['user_id'])?$_G['user_id']:0;
		$credit['op'] = 1;//增加
		$credit['type_id'] = $result['id'];
		$credit['remark'] = "提前还款成功加{$credit['value']}分";
		if($borrow_repayment_result['is_mb']!=1){//秒标、天标没有分 weego
			if($borrow_repayment_result['isday']!=1){
				$re = creditClass::UpdateCredit($credit);//更新积分
				if($re==false){
					mysql_query("rollback");
					return false;
				}
			}
		}
		//liukun add for bug 133 end
		//判断是否是最后的还款，是则解冻借款担保金
		//liukun add for bug 303 begin 当选择一次性还款时，这里无法正常执行，因为不管是几个月，order都只是0，但time_limit=月数
		if (round(($borrow_repayment_result['all_repayment_yesaccount']+$borrow_repayment_result['repayment_account']),2) == round($borrow_repayment_result['all_repayment_account'],2)){
			//liukun add for bug 164 begin
			/* add by jackfeng 20120-1-1*/
			if ($borrow_repayment_result['forst_account'] > 0){
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$data['user_id']));
				$account_log['user_id'] =$data['user_id'];
				$account_log['type'] = "borrow_frost";
				$account_log['money'] = $borrow_repayment_result['forst_account'];
				$account_log['total'] =$account_result['total'];
				$account_log['use_money'] = $account_result['use_money']+$account_log['money'];
				$account_log['no_use_money'] = $account_result['no_use_money']-$account_log['money'];
				$account_log['collection'] = $account_result['collection'];
				$account_log['to_user'] = "0";
				$account_log['borrow_id'] = $borrow_repayment_result['borrow_id'];
				$account_log['remark'] = "对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]借款的解冻";
				$re = accountClass::AddLog($account_log);
				if($re==false || $account_result==false){
					mysql_query("rollback");
					return false;
				}
			}
			//liukun add for bug 164 end
			$credit['nid'] = "borrow_success";
			$result = creditClass::GetTypeOne(array("nid"=>$credit['nid']));
			$credit['user_id'] = $data['user_id'];
			$credit['value'] = round($borrow_repayment_result['borrow_account']/100);
			$credit['op_user'] = $_G['user_id'];
			$credit['op'] = 1;//增加
			$credit['type_id'] = $result['id'];
			$credit['remark'] = "还款成功加{$credit['value']}分";
			if($borrow_repayment_result['is_mb']!=1){//秒标、天标没有分 weego
				if($borrow_repayment_result['isday']!=1){
					$re = creditClass::UpdateCredit($credit);//更新积分
					if($re==false){
						mysql_query("rollback");
						return false;
					}
				}
			}
		}
		$_order = $borrow_repayment_result['order'];
		//如果网站没有代还，则需要自己还款
		//如果网站没有代还，增加投资人收款记录
		$sendRemind = array();
		if ($borrow_repayment_result['status']!=2){
			$biaotype_info = $dynaBiaoClass->get_biaotype_info();
			//$sql = "select p1.*,p2.user_id,p3.vip_status from `{borrow_collection}` as p1 left join `{borrow_tender}` as p2 on p1.tender_id = p2.id left join `{user_cache}` as p3 on p2.user_id=p3.user_id where p1.`order` = '{$_order}' and p2.borrow_id='{$borrow_repayment_result['borrow_id']}' and p2.status=1";
			$sql = "select p1.*,p3.vip_status from `{borrow_collection}` as p1 left join `{user_cache}` as p3 on p1.user_id=p3.user_id where p1.`order` = '{$_order}' and p1.borrow_id='{$borrow_repayment_result['borrow_id']}' and p1.status=0";
			$result = $mysql->db_fetch_arrays($sql);
			foreach ($result as $key => $value){
				if($value['is_sell']==1){//债权已被出售
					continue;
				}
				//根据user_id找到是否是会员，决定利息管理费，太晚了，明天写
				if ($value['vip_status']==1){
					$interest_fee = round($value['interest'] * $biaotype_info['interest_fee_rate_vip'], 2);
				}else{
					$interest_fee = round($value['interest'] * $biaotype_info['interest_fee_rate'], 2);
				}
				//更新投资人的分期信息
				$sql = "update  `{borrow_collection}` set repay_yestime='".$current_time."',repay_yesaccount = repay_account ,status=1 , interest_fee = {$interest_fee},tg_status=1 where id = '{$value['id']}'";
				$re_1 = $mysql->db_query($sql);
				//更新投资的信息
				if($value['tender_id']>0){
					$sql = "update  `{borrow_tender}` set status=1,repayment_yesaccount= cast(repayment_yesaccount as decimal(11,2)) + {$value['repay_account']},wait_account = wait_account  - {$value['repay_account']} ,wait_interest = wait_interest - {$value['interest']},repayment_yesinterest  = repayment_yesinterest  + {$value['interest']}  where id = '{$value['tender_id']}'";
					$re_2 = $mysql->db_query($sql);
				}else{
					$re_2 = true;
				}
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$account_log['user_id'] =$value['user_id'];
				$account_log['type'] = "invest_repayment";
				$account_log['money'] = $value['repay_account'];
				$account_log['total'] = $account_result['total'];
				$account_log['use_money'] = $account_result['use_money']+$account_log['money'];
				$account_log['no_use_money'] = $account_result['no_use_money'];
				$account_log['collection'] =$account_result['collection'] -$account_log['money'];
				$account_log['to_user'] = $borrow_userid;
				$account_log['borrow_id'] = $borrow_repayment_result['borrow_id'];
				if($value['is_buy']==1){
					$account_log['remark'] = "客户对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]借款的还款,债权转让获得";
				}else{
					$account_log['remark'] = "客户对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]借款的还款";
				}
				$re = accountClass::AddLog($account_log);
				if($re_1==false || $re_2==false || $account_result==false || $re==false || $account_result==false){
					mysql_query("rollback");
					return false;
				}
				//扣除投资的管理费
				//$interest_fee = round($value['interest'] * $interest_fee_rate, 2);
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$log['user_id'] = $value['user_id'];
				$log['type'] = "tender_mange";
				$log['money'] = $interest_fee;
				$log['total'] = $account_result['total']-$log['money'];
				$log['use_money'] = $account_result['use_money']-$log['money'];
				$log['no_use_money'] = $account_result['no_use_money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = 0;
				$log['borrow_id'] = $borrow_repayment_result['borrow_id'];
				$log['remark'] = "用户对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]成功还款扣除利息的管理费";
				$re = accountClass::AddLog($log);
				if($re==false || $account_result==false){
					mysql_query("rollback");
					return false;
				}
				//TODO
				$late_customer_result = $dynaBiaoClass->getLateCustomerInterest($value);
				$late_customer_interest = $late_customer_result['late_customer_interest'];
				$late_days = $late_customer_result['late_days'];

				if($late_customer_interest > 0){
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
					$account_log['user_id'] =$value['user_id'];
					$account_log['type'] = "late_collection";
					$account_log['money'] = $late_customer_interest;
					$account_log['total'] = $account_result['total']+$account_log['money'];
					$account_log['use_money'] = $account_result['use_money']+$account_log['money'];
					$account_log['no_use_money'] = $account_result['no_use_money'];
					$account_log['collection'] =$account_result['collection'];
					$account_log['to_user'] = $borrow_userid;
					$account_log['borrow_id'] = $borrow_repayment_result['borrow_id'];
					$account_log['remark'] = "客户对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]偿还逾期利息(债权表),金额为{$account_log['money']}";
					$re = accountClass::AddLog($account_log);
					if($re==false || $account_result==false){
						mysql_query("rollback");
						return false;
					}
				}
				// liukun add for bug 309 保存逾期信息和利率管理费信息到collection表 begin
				// 更新collection的逾期信息， 利息管理费信息
				$sql = "update  `{borrow_collection}` set late_days={$late_days} ,late_interest = {$late_customer_interest}   where id = '{$value['id']}'";
				$re = $mysql->db_query($sql);
				if($re==false){
					mysql_query("rollback");
					return false;
				}
				// liukun add for bug 309 保存逾期信息和利率管理费信息到collection表 end
				//提醒设置
				if($borrow_repayment_result['is_mb'] != 1){//秒标不发送提醒
					$remind['nid'] = "loan_pay";
					$remind['sent_user'] = "0";
					$remind['receive_user'] = $value['user_id'];
					$remind['title'] = "客户对[{$borrow_repayment_result['borrow_name']}]借款的还款";
					$remind['content'] = "客户在".date("Y-m-d H:i:s",$current_time)."对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]借款的还款,还款金额为{$value['repay_account']}";
					$remind['type'] = "system";
					$sendRemind[]=$remind;
				}
			}
		}
		//逾期还款
		//判断逾期利息是还给谁
		//如果网站已经代还，则把逾期利息还给网站
		//网站代还之前，借款人还款，用户能收到逾期利息
		//liukun add for bug 52 begin
		//fb($late_result, FirePHP::TRACE);
		//liukun add for bug 52 end
		if ($late_result['late_days']>0){
			//支付逾期利息
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$data['user_id']));
			$account_log['user_id'] =$data['user_id'];
			$account_log['type'] = "late_repayment";
			$account_log['money'] = $late_result['late_interest'];
			$account_log['total'] =$account_result['total']-$account_log['money'];
			$account_log['use_money'] = $account_result['use_money']-$account_log['money'];
			$account_log['no_use_money'] = $account_result['no_use_money'];
			$account_log['collection'] = $account_result['collection'];
			$account_log['to_user'] = "0";
			$account_log['borrow_id'] = $borrow_repayment_result['borrow_id'];
			$account_log['remark'] = "对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]借款的逾期金额的扣除";
			$re = accountClass::AddLog($account_log);
			//如果是逾期还款，更新逾期时间和逾期利息信息
			$sql = "update`{borrow_repayment}` set late_days = '{$late_result['late_days']}',late_interest = '{$late_result['late_interest']}' where id = {$id}";
			$re_1 = $mysql->db_query($sql);
			if($account_result==false || $re==false || $re_1==false){
				mysql_query("rollback");
				return false;
			}
		}
		//添加最后的还款金额
		$sql = "update {borrow} set repayment_yesaccount=cast(repayment_yesaccount as decimal(11,2)) + {$borrow_repayment_result['repayment_account']} where id={$borrow_repayment_result['borrow_id']}";
		$result_1 = $mysql -> db_query($sql);
		//更新还款标的状态
		$sql = "update {borrow_repayment} set status=1,repayment_yesaccount='{$borrow_repayment_result['repayment_account']}',repayment_yestime='".$current_time."' where id=$id";
		$result_2 = $mysql -> db_query($sql);
		// 将逾期利息信息也传入
		$borrow_repayment_result['late_result'] = $late_result;
		$result_3 = $dynaBiaoClass->repay($borrow_repayment_result);
		if($result_1==false || $result_2==false || $result_3==false){
			mysql_query("rollback");
			return false;
		}else{
			if($borrow_repayment_result['status']==2){
				$a = $mysql->db_fetch_array("select card_id,realname,phone,pIpsAcctNo,virCardNo from {user} where user_id=".$user_id);
				if ($late_result['late_days']>0){
					$acc = $borrow_repayment_result['repayment_account'] + $late_result['late_interest'];
				}else{
					$acc = $borrow_repayment_result['repayment_account'];
				}
				$b['virCardNo'] = $a['virCardNo'];
				$b['merRemark1'] = "借款标还款CID:".$borrow_repayment_result['id'];
				$b['merOrderNum'] = time().rand(0001,9999);
				$b['tranAmt'] = $acc;
				$b['borrow_id'] = $borrow_repayment_result['borrow_id'];
				$b['user_id'] = $user_id;
				$b['collection_id'] = $borrow_repayment_result['id'];
				$a = tg_deduct($b);
				if($a['pErrCode']=='0000'){
					mysql_query("commit");
					return true;
				}else{
					mysql_query("rollback");
					return false;
				}
			}
			mysql_query("commit");
			//统一发送信息
			foreach($sendRemind as $key=>$value){
				remindClass::sendRemind($value);
			}
			/*
			foreach($sendSMS as $key=>$value){
				sendSMS($value['user_id'],$value['content'],1);
			}
			*/
		}
		return $result_3;
	}
	/**
	 * 查看投标的信息
	 *
	 * @param Array $data
	 * @return Array
	 */
	function GetRepaymentList($data = array()){
		global $mysql;
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = " where p1.borrow_id=p2.id and p2.user_id=p3.user_id and p2.status=3 ";
		if (isset($data['id']) && $data['id']!=""){
			if ($data['id'] == "request"){
				$_sql .= " and p1.borrow_id= '{$_REQUEST['id']}'";
			}else{
				$_sql .= " and p1.borrow_id= '{$data['id']}'";
			}
		}
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p2.user_id = '{$data['user_id']}'";
		}
		if (isset($data['biaoType']) && $data['biaoType']==1){
			$_sql .= " and p2.is_fast = 1";
		}
		if (isset($data['biaoType']) && $data['biaoType']==2){
			$_sql .= " and p2.is_jin = 1";
		}
		if (isset($data['biaoType']) && $data['biaoType']==3){
			$_sql .= " and p2.is_mb = 1";
		}
		if (isset($data['biaoType']) && $data['biaoType']==4){
			$_sql .= " and p2.is_xin = 1";
		}
		if (isset($data['username']) && $data['username']!=""){
			$_sql .= " and p3.username like '%{$data['username']}%'";
		}
		if (isset($data['borrow_id']) && $data['borrow_id']!=""){
			$_sql .= " and p1.borrow_id={$data['borrow_id']}";
		}
		if(isset($data['repayment_id'])){
			$_sql .= " and p1.id=".$data['repayment_id'];
		}elseif (isset($data['repayment_time']) && $data['repayment_time']!=""){
			if ($date['repayment_time']==0) $data['repayment_time'] = time();
                        $_repayment_time = get_mktime(date("Y-m-d",$data['repayment_time']));
                        if (isset($data['is_fast']) && $data['is_fast']==1){
                            $_sql .= " and p1.repayment_time > '{$_repayment_time}'";
                        }else{
                            $_sql .= " and p1.repayment_time < '{$_repayment_time}'";
                        }
		}
		if (isset($data['dotime2']) && $data['dotime2']>0){
			$_sql .= " and p1.repayment_yestime <= ".get_mktime($data['dotime2'].' 23:59:59');
		}
		if (isset($data['dotime1']) && $data['dotime1']>0){
			$_sql .= " and p1.repayment_yestime >= ".get_mktime($data['dotime1'].' 00:00:00');
		}
		if (isset($data['status'])){
			$_sql .= " and p1.status in ({$data['status']})";
		}
		if (isset($data['kefu_userid']) && $data['kefu_userid']!=""){
			$sql = "select 1 from `{user_cache}` where kefu_userid={$data['kefu_userid']} and user_id='{$data['user_id']}'";
			$result  = $mysql->db_fetch_array($sql);
			if($result=="" || $result==false){
				return "您的操作有误";
			}
		} 
		$keywords = empty($data['keywords'])?"":$data['keywords'];
		if ((!empty($keywords))){
		    if ($keywords=="request"){
				if (isset($_REQUEST['keywords']) && $_REQUEST['keywords']!=""){
					$_sql .= " and p2.name like '%".safegl($_REQUEST['keywords'])."%'";
				}
			}else{
				$_sql .= " and p2.name like '%".safegl($keywords)."%'";
			}
		}
		$_order = " order by p1.repayment_time asc";
		if (isset($data['order']) && $data['order']!="" ){
			if ($data['order'] == "repayment_time"){
				$_order = " order by p1.repayment_time asc ";
			}elseif ($data['order'] == "order"){
				$_order = " order by p1.order asc ,p1.id desc";
			}
		}
		$_select = " p1.*,p2.name as borrow_name,p2.time_limit,p2.style,p3.username,p3.user_id,p3.phone,p3.area,p2.biao_type";
		$sql = "select SELECT from `{borrow_repayment}` as p1,`{borrow}` as p2 ,`{user}` as p3  {$_sql} ORDER LIMIT";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  $_order, $_limit), $sql));
			foreach ($list as $key => $value){
				$repay_data['repayment_time']=$value['repayment_time'];
				$repay_data['repayment_account']=$value['repayment_account'];
				$repay_data['capital']=$value['capital'];
				$repay_data['status']=$value['status'];
				$repay_data['biao_type']=$value['biao_type'];
				$late = self::LateRepaymentInterest($repay_data);
				if ($value['status']!=1){
					$list[$key]['late_days'] = $late['late_days'];
					$list[$key]['late_interest'] = $late['late_interest'];
				}
				$list[$key]['mytime']=$value['repayment_time']-time();
				$list[$key]['tg_return'] = $value['tg_return']==''?array():unserialize($value['tg_return']);
				$a = $mysql->db_fetch_array('select count(1) as c from {borrow_collection} where interest_fee_status!=1 and borrow_id='.$value['borrow_id'].' and `order`='.$value['order']);
				$list[$key]['interest_fee_status'] = 1;
				if($a['c']>0) $list[$key]['interest_fee_status'] = 0;
			}
			return $list;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));	
		$list = $list?$list:array();
		foreach ($list as $key => $value){
			/*if($list[$key]['is_fast']==1){
				$sf = "select isqiye,id from `{daizi}` where borrow_id = {$list[$key]['borrow_id']}";
				$list_fast = $mysql->db_fetch_array($sf);		
				if($list_fast){
					$list[$key]['fastid'] = $list_fast['id'];
					$list[$key]['isqiye'] = $list_fast['isqiye'];
				}
			}*/
// 			$late = self::LateInterest(array("repayment_time"=>$value['repayment_time'],"account"=>$value['capital']));
			$repay_data['repayment_time']=$value['repayment_time'];
			$repay_data['repayment_account']=$value['repayment_account'];
			$repay_data['capital']=$value['capital'];
			$repay_data['status']=$value['status'];
			$repay_data['biao_type']=$value['biao_type'];
			$late = self::LateRepaymentInterest($repay_data);
			if ($value['status']!=1){
				$list[$key]['late_days'] = $late['late_days'];
				$list[$key]['late_interest'] = $late['late_interest'];
			}
			$list[$key]['mytime']=$value['repayment_time']-time();
			//标的名称 add by weego 20130116
			$sql = "select * from `{biao_type}` where biao_type_name='{$value['biao_type']}'";
			$result = $mysql ->db_fetch_array($sql);
			$list[$key]['show_name']=$result['show_name'];
			if($list[$key]['style']==2){//到期全额还款，总共就一期
				$list[$key]['time_limit'] = 1;
			}
			$list[$key]['tg_return'] = $value['tg_return']==''?array():unserialize($value['tg_return']);
			$a = $mysql->db_fetch_array('select count(1) as c from {borrow_collection} where interest_fee_status!=1 and borrow_id='.$value['borrow_id'].' and `order`='.$value['order']);
			$list[$key]['interest_fee_status'] = 1;
			if($a['c']>0) $list[$key]['interest_fee_status'] = 0;
		}
		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
	function GetVouchRepayList($data = array()){
		global $mysql;
	
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where p1.borrow_id=p2.id and p2.user_id=p3.user_id ";
		if (isset($data['id']) && $data['id']!=""){
			if ($data['id'] == "request"){
				$_sql .= " and p1.borrow_id= '{$_REQUEST['id']}'";
			}else{
				$_sql .= " and p1.borrow_id= '{$data['id']}'";
			}
		}
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p2.user_id = '{$data['user_id']}'";
		}	 
		if (isset($data['vouch_userid']) && $data['vouch_userid']!=""){
			$_sql .= " and p2.id in (select borrow_id from `{borrow_vouch}` where user_id={$data['vouch_userid']})";
		}	 
		if (isset($data['username']) && $data['username']!=""){
			$_sql .= " and p3.username like '%{$data['username']}%'";
		}	 
		if (isset($data['repayment_time']) && $data['repayment_time']!=""){
			if ($date['repayment_time']==0) $data['repayment_time'] = time();
			$_repayment_time = get_mktime(date("Y-m-d",$data['repayment_time']));
			$_sql .= " and p1.repayment_time < '{$_repayment_time}'";
		}	 
		
		if (isset($data['dotime2'])){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if( !isTimePatternT($dotime2))$dotime2 = "";
			if ($dotime2!=""){
				$_sql .= " and p2.addtime < ".get_mktime($dotime2);
			}
		}
		if (isset($data['dotime1'])){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if( !isTimePatternT($dotime1))$dotime1 = "";
			if ($dotime1!=""){
				$_sql .= " and p2.addtime > ".get_mktime($dotime1);
			}
		}
		if (isset($data['status'])){
			$_sql .= " and p1.status in ({$data['status']})";
		}
		$keywords = empty($data['keywords'])?"":$data['keywords'];
		if ((!empty($keywords)  ) ){
		    if ($keywords=="request"){
				if (isset($_REQUEST['keywords']) && $_REQUEST['keywords']!=""){
					$_sql .= " and p2.name like '%".safegl($_REQUEST['keywords'])."%'";
				}
			}else{
				$_sql .= " and p2.name like '%".safegl($keywords)."%'";
			}
			
		}
		
		$_order = " order by p1.id desc";
		if (isset($data['order']) && $data['order']!="" ){
			if ($data['order'] == "repayment_time"){
				$_order = " order by p1.repayment_time asc ";
			}elseif ($data['order'] == "order"){
				$_order = " order by p1.order asc ,p1.id desc";
			}
		}
		
// 		$_select = " p1.*,p2.name as borrow_name,p2.time_limit,p3.username as borrow_username";
		$_select = " p1.*,p2.name as borrow_name,p2.time_limit,p3.username as borrow_username,p2.biao_type";
		$sql = "select SELECT from `{borrow_repayment}` as p1 left join `{borrow}` as p2 on p1.borrow_id = p2.id left join `{user}` as p3 on p3.user_id=p2.user_id {$_sql} ORDER LIMIT";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  $_order, $_limit), $sql));
			foreach ($list as $key => $value){
// 				$late = self::LateInterest(array("repayment_time"=>$value['repayment_time'],"account"=>$value['capital']));
				$repay_data['repayment_time']=$value['repayment_time'];
				$repay_data['repayment_account']=$value['repayment_account'];
				$repay_data['capital']=$value['capital'];
				$repay_data['status']=$value['status'];
				$repay_data['biao_type']=$value['biao_type'];
				$late = self::LateRepaymentInterest($repay_data);
				if ($value['status']!=1){
					$list[$key]['late_days'] = $late['late_days'];
					$list[$key]['late_interest'] = $late['late_interest'];
				}
			}
			return $list;
		}			 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));		
		$list = $list?$list:array();
		foreach ($list as $key => $value){
// 			$late = self::LateInterest(array("repayment_time"=>$value['repayment_time'],"account"=>$value['capital']));
			$repay_data['repayment_time']=$value['repayment_time'];
			$repay_data['repayment_account']=$value['repayment_account'];
			$repay_data['capital']=$value['capital'];
			$repay_data['status']=$value['status'];
			$repay_data['biao_type']=$value['biao_type'];
			$late = self::LateRepaymentInterest($repay_data);
			if ($value['status']!=1){
				$list[$key]['late_days'] = $late['late_days'];
				$list[$key]['late_interest'] = $late['late_interest'];
			}
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
	//逾期利息计算
	//account 金额 repayment_time 还款时间
	/*
	function LateInterestxxx($data){
		global $mysql,$_G;
		//$late_rate = isset($_G['system']['con_late_rate'])?$_G['system']['con_late_rate']:0.008;
                $late_rate=0.008;
		$now_time = get_mktime(date("Y-m-d",time()));
		$repayment_time = get_mktime(date("Y-m-d",$data['repayment_time']));
		$late_days = ($now_time - $repayment_time)/(60*60*24);
		$_late_days = explode(".",$late_days);
		$late_days = ($_late_days[0]<0)?0:$_late_days[0];
 
                if($late_days<=30){
                    $late_interest = round($data['account']*0.008*$late_days,2);
                }else{
                    $late_interest = round($data['account']*0.008*30 + $data['account']*0.012*($late_days-30),2);
                }

		if ($late_days==0) $late_interest=0;
		return array("late_days"=>$late_days,"late_interest"=>$late_interest );
	}*/
	
	//liukun add for bug 307 begin 更新系统计算逾期利息的方法
	function LateRepaymentInterest($data){
		global $mysql, $_G;

		$biao_type = $data['biao_type'];
		
		if (isset($_G['biao_type'][$biao_type])){
			$result = $_G['biao_type'][$biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}

		$late_interest_rate['late_interest_rate'] = $result['late_interest_rate'];
		$late_interest_rate['late_customer_interest_rate'] = $result['late_customer_interest_rate'];
		$late_interest_rate['late_interest_scope'] = $result['late_interest_scope'];
		
		//1:逾期利息是应还本息的基础上罚息
		//0:逾期利息是应还本金的基础上罚息
		if ($late_interest_rate['late_interest_scope'] == 1){
			$loan_account = $data['repayment_account'];
		}else{
			$loan_account = $data['capital'];
		}
		
		$late_rate=$late_interest_rate['late_interest_rate'];
		
		$now_time = get_mktime(date("Y-m-d",time()));
		$repayment_time = get_mktime(date("Y-m-d",$data['repayment_time']));
		$late_days = ($now_time - $repayment_time)/(60*60*24);
		$_late_days = explode(".",$late_days);
		$late_days = ($_late_days[0]<0)?0:$_late_days[0];
		
		$late_interest = round($loan_account*$late_rate*$late_days,2);
		
		
		
		
		$interest_result['late_days'] = $late_days;
		$interest_result['late_interest'] = $late_interest;
		
		return $interest_result;

		return $late_interest_rate;
	}
	
	function LateCollectionInterest($data){
		global $mysql, $_G;
		
		$biao_type = $data['biao_type'];
		
		if (isset($_G['biao_type'][$biao_type])){
			$result = $_G['biao_type'][$biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}
		
		$late_interest_rate['late_interest_rate'] = $result['late_interest_rate'];
		$late_interest_rate['late_customer_interest_rate'] = $result['late_customer_interest_rate'];
		$late_interest_rate['late_interest_scope'] = $result['late_interest_scope'];
		
		//1:逾期利息是应还本息的基础上罚息
		//0:逾期利息是应还本金的基础上罚息
		if ($late_interest_rate['late_interest_scope'] == 1){
			$loan_account = $data['repayment_account'];
		}else{
			$loan_account = $data['capital'];
		}
		
		
		
		
		$now_time = get_mktime(date("Y-m-d",time()));
		$repayment_time = get_mktime(date("Y-m-d",$data['repayment_time']));
		$late_days = ($now_time - $repayment_time)/(60*60*24);
		$_late_days = explode(".",$late_days);
		$late_days = ($_late_days[0]<0)?0:$_late_days[0];
		
		
		$late_customer_rate=$late_interest_rate['late_customer_interest_rate'];

		if ($data["status"] == 2){
			//如果网站已经垫付，投资人没有逾期利息收入
			$late_customer_interest = 0;
		}
		else{
			$late_customer_interest = round($loan_account*$late_customer_rate*$late_days,2);
		}
		
		
		$interest_result['late_days'] = $late_days;
		$interest_result["late_customer_interest"] = $late_customer_interest;
		
		return $interest_result;
	}
	//liukun add for bug 307  end  更新系统计算逾期利息的方法
	

	//逾期利息计算
	//account 金额 repayment_time 还款时间
	/*
	function LateInterestFastxxx($data){
		global $mysql,$_G;
		//$late_rate = isset($_G['system']['con_late_rate'])?$_G['system']['con_late_rate']:0.008;
                $late_rate=0.008;
		$now_time = get_mktime(date("Y-m-d",time()));
		$repayment_time = get_mktime(date("Y-m-d",$data['repayment_time']));
		$late_days = ($now_time - $repayment_time)/(60*60*24);
		$_late_days = explode(".",$late_days);
		$late_days = ($_late_days[0]<0)?0:$_late_days[0];

                $late_interest = round($data['account']*0.008*$late_days,2);
                
		if ($late_days==0) $late_interest=0;
		return array("late_days"=>$late_days,"late_interest"=>$late_interest );
	}*/
	
	function LateRepay($data){
		return false;
		global $mysql,$_G;
		$repayment_id = $data['id'];
		$sql = "select p1.*,p2.name as borrow_name,p2.user_id,p2.p_contract_no,p2.is_vouch, p2.biao_type from `{borrow_repayment}` as p1 left join `{borrow}` as p2 on p1.borrow_id = p2.id where p1.id = {$repayment_id}";
		$result = $mysql->db_fetch_array($sql);
		$borrow_repayment_result = $result;
		//判断上一期是否已还
		$islast = 0;
		if ($borrow_repayment_result['order']!=0){
			$_order = $borrow_repayment_result['order']-1;
			$sql = "select status from `{borrow_repayment}` where `order`=$_order and borrow_id={$borrow_repayment_result['borrow_id']}";
			$a = $mysql->db_fetch_array($sql);
			if ($a!=false && $a['status']==0){
				return "你上期的借款还没还，请先还上期的";
			}else{
				$sql = "select status from `{borrow_repayment}` where `order`>{$borrow_repayment_result['order']} and borrow_id={$borrow_repayment_result['borrow_id']}";
				$a = $mysql->db_fetch_array($sql);
				if ($a==false){
					$islast = 1;
				}
			}
		}
		$borrow_result = self::GetOne(array("id"=>$result['borrow_id']));
		$borrow_id = $borrow_result["id"];
		$repayment_status = $borrow_repayment_result["status"];
		$order = $borrow_repayment_result["order"];
		$biao_type = $borrow_result['biao_type'];
		$classname = $biao_type."biaoClass";
		$dynaBiaoClass = new $classname();
		$advance = $dynaBiaoClass->get_advance();
		$advance_time = $advance['advance_time'];
		//判断是否到了逾期垫付时间
		$repayment_time = $borrow_repayment_result['repayment_time'];
		//liukun add for bug 52 begin
		//fb($advance, FirePHP::TRACE);
		//fb($repayment_time, FirePHP::TRACE);
		//fb($advance_time, FirePHP::TRACE);
		//liukun add for bug 52 end

		if (time() < ($repayment_time + 3600 * 24 * $advance_time)){
			return array("此标尚未到逾期垫付时间：{$advance_time}天");
		}
		$late_result = $dynaBiaoClass->getLateInterest($borrow_repayment_result);
		$isVouch=$result['is_vouch'];
		if ($repayment_status==1){
			return array("借款人已经还款");
		}elseif ($repayment_status==2){
			return array("网站已经垫付");
		}elseif ($repayment_status==0){
			//根据用户是否VIP来决定垫付
			$normal_user_type =0;
			$vip_user_type =1;
			$biaotype_info = $dynaBiaoClass->get_biaotype_info();//利息管理费;
			//$sql = "select p1.repay_account,p1.interest,p1.capital,p2.user_id from `{borrow_collection}` as p1 left join  `{borrow_tender}` as p2 on p1.tender_id = p2.id where p1.`order` = '{$order}' and p2.borrow_id='{$borrow_id}'";
			$sql = "select p1.* from `{borrow_collection}` as p1  where p1.`order` = '{$order}' and p1.borrow_id='{$borrow_id}' and p1.status=0";
			$result = $mysql->db_fetch_arrays($sql);
			mysql_query("start transaction");//开启事务
			//撤回该标的债权转让
			require_once ROOT_PATH."modules/creditor/creditor.class.php";
			$creditor = new creditorClass;
			$_a = $creditor->transfer_cf(array('status'=>1,'borrow_id'=>$borrow_id));
			if($_a==false){
				mysql_query("rollback");
				return false;
			}
			$sql = "select p1.card_id,p1.realname,p1.phone,p1.pIpsAcctNo,p1.virCardNo from {user} as p1 where p1.user_id=".$borrow_repayment_result['user_id'];
			$a = $mysql->db_fetch_array($sql);
			$tg_repay = array(
				'pBidNo'=>$borrow_repayment_result['borrow_id'],
				'pContractNo'=>$borrow_repayment_result['p_contract_no'],
				'pRepaymentDate'=>date("Ymd",time()),
				'pMerBillNo'=>$borrow_repayment_result['trade_no'],
				'pFTrdAmt'=>0,
				'pFIpsAcctNo'=>$a['pIpsAcctNo'],
				'pFIdentNo'=>$a['card_id'],
				'pFRealName'=>$a['realname'],
				'pFPhone'=>$a['phone'],
				'pFTrdFee'=>0.00,
				'pMerchantBillNo'=>'',
				'isInFull'=>$islast,
				'repaymentType'=>1,
				'repayment_id'=>$borrow_repayment_result['id']
				);
			unset($a);
			foreach ($result as $key => $value){
				if($value['is_sell']==1){//债权已被出售
					continue;
				}
				$sql = "select p1.card_id,p1.realname,p1.phone,p1.pIpsAcctNo,p1.virCardNo,p2.vip_status from `{user}` as p1 left join `{user_cache}` as p2 on p1.user_id=p2.user_id where p1.user_id={$value['user_id']} ";
				$vip_result = $mysql->db_fetch_array($sql);
				$vip_status = $vip_result['vip_status'];
				if($vip_result['vip_status']==$vip_user_type){
					$advance_scope = $advance['advance_vip_scope'];
					$advance_rate = $advance['advance_vip_rate'];
				}else{
					$advance_scope = $advance['advance_scope'];
					$advance_rate = $advance['advance_rate'];
				}
				//0,不垫付
				if ($advance_scope == 0){
					$advance_account = 0;
					$advance_capital = 0;
					$advance_interest = 0;
				}
				//1,垫付本金
				elseif ($advance_scope == 1){
					$advance_account=$borrow_repayment_result['capital'];
					$advance_account = round($value['capital'] * $advance_rate, 2);
					$advance_capital = round($value['capital'] * $advance_rate, 2);
					$advance_interest = 0;
				}
				//2，垫付本金和利息
				else {
					$advance_account=$value['repay_account'];
					$advance_account = round($value['repay_account'] * $advance_rate, 2);
					$advance_capital = round($value['capital'] * $advance_rate, 2);
					$advance_interest = $advance_account - $advance_capital;
				}
				if($vip_result['vip_status']==$vip_user_type){
					$interest_fee = round($advance_interest * $biaotype_info['interest_fee_rate_vip'], 2);
				}else{
					$interest_fee = round($advance_interest * $biaotype_info['interest_fee_rate'], 2);
				}
				//更新投资人的分期信息
				$sql = "update `{borrow_collection}` set repay_yestime='".time()."',repay_yesaccount = {$advance_account} ,status=2 , interest_fee = {$interest_fee}   where id = '{$value['id']}'";
				$re_1 = $mysql->db_query($sql);
				//更新投资的信息
				$sql = "update `{borrow_tender}` set repayment_yesaccount= repayment_yesaccount + {$advance_account},wait_account = wait_account  - {$advance_account} ,wait_interest = wait_interest - {$advance_interest},repayment_yesinterest  = repayment_yesinterest  + {$advance_interest}  where id = '{$value['tender_id']}'";
				if($value['tender_id']>0){
					$re_2 = $mysql->db_query($sql);
				}else{
					$re_2 = true;
				}
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$account_log['user_id'] =$value['user_id'];
				$account_log['type'] = "system_repayment";
				$account_log['money'] = $advance_account;
				$account_log['total'] = $account_result['total'];
				$account_log['use_money'] = $account_result['use_money']+$account_log['money'];
				$account_log['no_use_money'] = $account_result['no_use_money'];
				$account_log['collection'] =$account_result['collection'] -$account_log['money'];
				$account_log['to_user'] = "0";
				$account_log['borrow_id'] = $borrow_repayment_result['borrow_id'];
				if($value['is_buy']==1){
					$account_log['remark'] = "客户逾期，系统自动对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]借款的还款,债权转让获得";
				}else{
					$account_log['remark'] = "客户逾期，系统自动对[<a href=\'/invest/a{$borrow_repayment_result['borrow_id']}.html\' target=_blank>{$borrow_repayment_result['borrow_name']}</a>]借款的还款";
				}
				$re_3 = accountClass::AddLog($account_log);
				if($account_result==false || $re_1==false || $re_2==false || $re_3==false){
					mysql_query("rollback");
					return false;
				}
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$log['user_id'] = $value['user_id'];
				$log['type'] = "tender_mange";
				$log['money'] = $interest_fee;
				$log['total'] = $account_result['total']-$log['money'];
				$log['use_money'] = $account_result['use_money']-$log['money'];
				$log['no_use_money'] = $account_result['no_use_money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = 0;
				$log['borrow_id'] = $borrow_repayment_result['borrow_id'];
				$log['remark'] = "用户成功还款扣除利息的管理费,标ID:{$borrow_repayment_result['borrow_id']}";
				$re_1 = accountClass::AddLog($log);
				if($account_result==false || $re_1==false){
					mysql_query("rollback");
					return false;
				}
				/***设置托管信息***/
				$_t['pTAcctType'] = 1;
				$_t['pTIpsAcctNo'] = $vip_result['pIpsAcctNo'];
				$_t['pTPhone'] = $vip_result['phone'];
				$_t['pTTrdFee'] = $interest_fee;
				$_t['pTTrdAmt'] = $advance_account;
				$_t['virCardNo'] = $vip_result['virCardNo'];
				$_t['user_id'] = $value['user_id'];
				$tg_repay['pDetails']['pRow'][] = $_t;
				$tg_repay['pFTrdAmt'] += $_t['pTTrdAmt'];
				unset($_t);
				/******************/

			}
			$sql = "update `{borrow_repayment}` set status=2,webstatus=1, advance_time='".time()."' where id = {$repayment_id}";

			$result_1 = $mysql -> db_query($sql);

			$result = $dynaBiaoClass->late_repay($borrow_repayment_result);
			if($result==false || $result_1==false){
				$mysql->db_query("rollback");
			}else{
				//$a = tg_repayment($tg_repay);
				if($a['respCode']=='0000'){
					$mysql->db_query("commit");
					/*
					foreach($tg_repay['pDetails']['pRow'] as $k=>$v){
						$b['virCardNo'] = $v['virCardNo'];
						$b['merRemark1'] = "借款标还款的利息管理费扣除CID:".$borrow_repayment_result['id'];
						$b['merOrderNum'] = 'glf'.time().$k.rand(001,999);
						$b['tranAmt'] = $v['pTTrdFee'];
						$b['borrow_id'] = $borrow_repayment_result['borrow_id'];
						$b['collection_id'] = $borrow_repayment_result['id'];
						$b['user_id'] = $v['user_id'];
						$c = tg_deduct($b);
						if($c['pErrCode']=='0000'){
							$mysql->db_fetch_array('update {borrow_collection} set interest_fee_status=interest_fee_status+1 where id='.$borrow_repayment_result['id']);
						}
					}*/
					return true;
				}else{
					$mysql->db_query("rollback");
					return $a['msgExt'];
				}
			}
			return $result;
		}
	}
	/**
	 * 查看投标的信息
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetRepayment($data = array()){
		global $mysql;
		$id = $data['id'];
		$sql = "select * from {borrow}  where id=$id";
		$result = $mysql->db_fetch_array($sql);
		$data['account'] = $result['account'];
		$data['year_apr'] = $result['apr'];
		$data['month_times'] = $result['time_limit'];
		$data['borrow_time'] = $result['success_time'];
		$data['borrow_style'] = $result['style'];
		///add by weego for 天标
		$data['isday'] = $result['isday'];
		$data['time_limit_day'] = $result['time_limit_day'];
		return self::EqualInterest($data);
	}

	/**
	 * 流转标及时生息，这个方法中的事务在AddTender()方法中统一提交，避免事务嵌套。此方法不能单独调用，必须配合事务
	 * add by weego 20121208
	 * @param Array $data(user_id,id,status,remark)
	 * @return Array
	 */
	public static function AddRepaymentForLZ($data = array()){
		global $mysql,$_G,$rdGlobal;
		
		$tender_id = $data['tender_id'];
		$id = $data['id'];
		if ($id  =="") return self::ERROR;
		$status = $data['status'];
		
		$sql = "select * from {borrow}  where id=$id";
		$result = $mysql->db_fetch_array($sql);
	     
		$user_id = $result['user_id'];
		$borrow_name = $result['name'];
		
		$style = $result['style'];
		$award =$result['award'];
		$funds = $result['funds'];
		$is_vouch = $result['is_vouch'];//是否是担保标
		$vouch_award = $result['vouch_award'];//担保的奖励
		$part_account = $result['part_account'];
		$tender_times = $result['tender_times'];
		$month_times = $result['time_limit'];
		$borrow_account_b = $result['account'];
		//add by weego 20120525
		$isday = $result['isday'];
		$time_limit_day = $result['time_limit_day'];
		$repayment_account  = $result['repayment_account'];
		$borrow_url = "<a href=\'/invest/a{$id}.html\' target=_blank>{$borrow_name}</a>";
		$sendRemind = array();
		$sendSMS = array();
		if ($status == 3){
			//扣除投资者的金钱。
			$sql = "select * from `{borrow_tender}`  where id=$tender_id and status=5 order by id";
			$result = $mysql->db_fetch_arrays($sql);
			foreach ($result as $key => $value){
				$borrow_account += $value['account'];
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$log['user_id'] = $value['user_id'];
				$log['type'] = "invest";
				$log['money'] = $value['account'];
				$log['total'] = $account_result['total']-$log['money'];
				$log['use_money'] = $account_result['use_money'];
				$log['no_use_money'] = $account_result['no_use_money']-$log['money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = $user_id;
				$log['borrow_id'] = $id;
				$log['remark'] = "投标成功费用扣除，来自[{$borrow_url}]";
				$re = accountClass::AddLog($log);
				if($account_result==false || $re==false){
					return false;
				}
				//添加待收的金额
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$log['user_id'] = $value['user_id'];
				$log['type'] = "collection";
				$log['money'] = $value['repayment_account'];
				$log['total'] = $account_result['total']+$log['money'];
				$log['use_money'] = $account_result['use_money'];
				$log['no_use_money'] = $account_result['no_use_money'];
				$log['collection'] = $account_result['collection']+$log['money'];
				$log['to_user'] = $user_id;
				$log['borrow_id'] = $id;
				$log['remark'] = "待收金额,来自[{$borrow_url}]";
				$re = accountClass::AddLog($log);
				$sql = " update `{borrow_tender}` set status=1 where status= 5 and id='{$value['id']}'";
				$re_1 = $mysql ->db_query($sql);
				if($account_result==false || $re==false || $re_1==false){
					return false;
				}
				//提醒设置
				$remind['nid'] = "loan_yes_account";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $value['user_id'];
				$remind['title'] = "[借出成功，扣除冻结款]恭喜您，投资[{$borrow_name}]成功.";
				$remind['content'] = "恭喜您，您投资[<a href=\'/invest/a{$data['id']}.html\' target=_blank><font color=red>{$borrow_name}</font></a>]在".date("Y-m-d",time())."已经审核通过";
				$remind['type'] = "system";  
				$sendRemind[] = $remind;
				$sendSMS[] = array('user_id'=>$value['user_id'],'content'=>"您已于".date("Y-m-d",time())."成功投资[{$borrow_name}]".$value['account']."元。");

				$credit['nid'] = "invest_success";
				$result = creditClass::GetTypeOne(array("nid"=>$credit['nid']));
				$credit['user_id'] = $value['user_id'];
				$credit['value'] = round($value['account']/100);
				$credit['op_user'] = $_G['user_id'];
				$credit['op'] = 1;//增加
				$credit['type_id'] = $result['id'];
				$credit['remark'] = "投资成功加{$credit['value']}分";
    			$re = creditClass::UpdateCredit($credit);//更新积分
				if($result==false || $re==false){
					return false;
				}
				//更新投资人的投标标的还款日期 add by weego 20120614
				for ($i=0;$i<$month_times;$i++){
					//repair by weego 20120525 for 天标还款时间
					if($isday==1){
						$repay_time=strtotime("$time_limit_day days",time());	
					}else{
						$repay_time = get_times(array("time"=>time(),"num"=>$i+1));
					}
					// 2012-06-14 修改还款时间 LiuYY
					//$to_day = date("Y-m-d 23:59:59", $repay_time);
					//$repay_time = strtotime($to_day);
					$sql = " update `{borrow_collection}` set repay_time={$repay_time} where `order`= {$i} and tender_id='{$value['id']}'";
					$re = $mysql ->db_query($sql);
					if($re==false){
						return false;
					}
				}
			}
			//借款者总金额增加。
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$user_id));
			$borrow_log['user_id'] = $user_id;
			$borrow_log['type'] = "borrow_success";
			$borrow_log['money'] = $borrow_account;
			$borrow_log['total'] =$account_result['total']+$borrow_log['money'];
			$borrow_log['use_money'] = $account_result['use_money']+$borrow_log['money'];
			$borrow_log['no_use_money'] = $account_result['no_use_money'];
			$borrow_log['collection'] = $account_result['collection'];
			$borrow_log['to_user'] = "0";
			$borrow_log['borrow_id'] = $id;
			$borrow_log['remark'] = "通过[{$borrow_url}]借到的款";
			$re = accountClass::AddLog($borrow_log);
			if($account_result==false || $re==false){
				return false;
			}
			$sendSMS[] = array('user_id'=>$user_id,'content'=>"你的流转标[{$borrow_name}]已被购买，你的账户增加了{$borrow_account}元。");

			//扣除手续费
			$biao_type = new lzbiaoClass();
			$biao_result = $biao_type->get_biaotype_info();
			if($isday==1){
				$borrow_fee = $biao_result['borrow_day_fee_rate'];
			}else{
				$borrow_fee = $biao_result['borrow_fee_rate'];
			}
			$money = round($borrow_account*$borrow_fee*$month_times,2);
			//add by weego for 天标借款管理费 20120525
			if($isday==1){
				$money=$money/30*$time_limit_day;	
			}
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$user_id));
			$fee_log['user_id'] = $user_id;
			$fee_log['type'] = "borrow_fee";
			$fee_log['money'] = $money;
			$fee_log['total'] = $account_result['total']-$fee_log['money'];
			$fee_log['use_money'] = $account_result['use_money']-$fee_log['money'];
			$fee_log['no_use_money'] = $account_result['no_use_money'];
			$fee_log['collection'] = $account_result['collection'];
			$fee_log['to_user'] = "0";
			$fee_log['borrow_id'] = $id;
			$fee_log['remark'] = "借款[{$borrow_url}]的手续费";
            $re = accountClass::AddLog($fee_log);
            if($account_result==false || $re==false){
            	return false;
            }
		}
		//如果有设置奖励并且招标成功，或者失败也奖励
		if ($award==1 || $award==2){
			if ($status == 3 && $rdGlobal['lz_awardfirst']){
				$sql = "select * from {borrow_tender}  where id=$tender_id";
				$result = $mysql->db_fetch_arrays($sql);
				foreach ($result as $key => $value){
					//投标奖励扣除和增加。
					if ($award==1){
						$money = round(($value['account']/$borrow_account_b)*$part_account,2);
					}elseif ($award==2){
						$money = round((($funds/100)*$value['account']),2);
					}
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
					$log['user_id'] = $value['user_id'];
					$log['type'] = "award_add";
					$log['money'] = $money;
					$log['total'] = $account_result['total']+$money;
					$log['use_money'] = $account_result['use_money']+$money;
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = $user_id;
					$log['borrow_id'] = $id;
					$log['remark'] = "借款[{$borrow_url}]的奖励";
					$re = accountClass::AddLog($log);
					if($account_result==false || $re==false){
						return false;
					}
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$user_id));
					$log['user_id'] = $user_id;
					$log['type'] = "award_lower";
					$log['money'] = $money;
					$log['total'] = $account_result['total']-$money;
					$log['use_money'] = $account_result['use_money']-$money;
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = $value['user_id'];
					$log['borrow_id'] = $id;
					$log['remark'] = "扣除借款[{$borrow_url}]的奖励";
					$re = accountClass::AddLog($log);
					if($account_result==false || $re==false){
						return false;
					}
				}
			}
		}
		foreach($sendRemind as $remind){
			remindClass::sendRemind($remind);
		}
		foreach($sendSMS as $value){
			//sendSMS($value['user_id'],$value['content'],1);
		}
		return true;
	}
	
	/**
	 * 满标复审
	 *
	 * @param Array $data(user_id,id,status,remark)
	 * @return Array
	 */
	public static function AddRepayment($data = array()){
		global $mysql,$_G;
		$id = $data['id'];
		if ($id  =="") return self::ERROR;
		$status = $data['status'];
		$sql = "select * from {borrow}  where id=$id";
		$result = $mysql->db_fetch_array($sql);
		//保留borrow信息以备后用，减少不必要的查询
		$borrow_result = $result;
		if ($result['status'] != 1){
			return "此标暂时不能复审";
		}
		$user_id = $result['user_id'];
		$borrow_name = $result['name'];
		$borrow_account = $result['account'];
		$style = $result['style'];
		$award =$result['award'];
		$funds = $result['funds'];
		$is_vouch = $result['is_vouch'];//是否是担保标
		$vouch_award = $result['vouch_award'];//担保的奖励
		$part_account = $result['part_account'];
		$tender_times = $result['tender_times'];
		$month_times = $result['time_limit'];
		//add by weego 20120525
		$isday = $result['isday'];
		$time_limit_day = $result['time_limit_day'];

		$repayment_account  = $result['repayment_account'];
		$_data['account'] = $borrow_account;
		$_data['year_apr'] = $result['apr'];
		$_data['month_times'] = $month_times;
		$_data['borrow_time'] = $result['success_time'];
		$_data['borrow_style'] = $result['style'];

		$is_mb = $result['is_mb'];
		$is_fast = $result['is_fast'];
		$is_jin = $result['is_jin'];

		///add by weego for 天标
		$isday = $result['isday'];
		$time_limit_day = $result['time_limit_day'];
		$_data['isday'] = $result['isday'];
		$_data['time_limit_day'] = $result['time_limit_day'];

		// alpha add for bug 8   begin
		$is_zhouzhuan = $result['is_zhouzhuan'];
		// alpha add for bug 8   end

		// alpha add for bug 24  begin
		$is_restructuring = $result['is_restructuring'];
		// alpha add for bug 24  end

		$biao_type = $result['biao_type'];
		$classname = $borrow_result['biao_type']."biaoClass";
		$dynaBiaoClass = new $classname();

		$interest_result = self::EqualInterest($_data);
		$total_account = 0;
		$borrow_url = "<a href=\'/invest/a{$id}.html\' target=_blank>{$borrow_name}</a>";
		
		mysql_query("start transaction");//开启事务
		$sql = " update {borrow} set status='{$data['status']}' where id='{$id}'";
		$mysql->db_query($sql);
		$borrow_result['status'] = $status;
		if ($status == 3){
			$a = $mysql->db_fetch_arrays("select p1.* from {borrow_collection} as p1 left join {borrow_tender} as p2 on p1.tender_id=p2.id where p2.borrow_id=$id and p2.status=5");
			$_a = array();
			foreach($a as $k=>$v){
				$_a[$v['order']]['interest'] += $v['interest'];
				$_a[$v['order']]['capital'] += $v['capital'];
				$_a[$v['order']]['repay_account'] += $v['repay_account'];
				$_a['total']['interest'] += $v['interest'];
				$_a['total']['capital'] += $v['capital'];
				$_a['total']['repay_account'] += $v['repay_account'];
			}
			//如果成功，则将还款信息输进表里面去
			foreach ($interest_result as $key => $value){
				$value['interest'] = $_a[$key]['interest'];
				$value['capital'] = $_a[$key]['capital'];
				$value['repayment_account'] = $_a[$key]['repay_account'];
				// 2012-06-14 修改还款时间 LiuYY
				$to_day = date("Y-m-d 23:59:59", $value['repayment_time']);
				$value['repayment_time'] = strtotime($to_day);
				$total_account = $total_account+$value['repayment_account'];//总还金额
				$trade_no = 'hk'.time().$key.rand(1,100);
				$sql = "insert into {borrow_repayment} set `addtime` = '".time()."',`addip` = '".ip_address()."',`borrow_id`='{$id}',`order`='{$key}',`repayment_time`='{$value['repayment_time']}',`repayment_account`='{$value['repayment_account']}',`interest`='{$value['interest']}',`capital`='{$value['capital']}',trade_no='{$trade_no}'";
				$re = $mysql->db_query($sql);
				$repayment_account = $value['repayment_account'];
				if ($re==false){
					mysql_query("rollback");
					return false;
				}
			}
			//扣除所有投资者的金钱。
			$sql = "select * from `{borrow_tender}`  where borrow_id=$id and status=5 order by id";
			$result = $mysql->db_fetch_arrays($sql);
			$sendRemind = array();
			$sendSMS = array();
			$tender_result = $result;
			
			foreach ($result as $key => $value){
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$log['user_id'] = $value['user_id'];
				$log['type'] = "invest";
				$log['money'] = $value['account'];
				$log['total'] = $account_result['total']-$log['money'];
				$log['use_money'] = $account_result['use_money'];
				$log['no_use_money'] = $account_result['no_use_money']-$log['money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = $user_id;
				$log['borrow_id'] = $data['id'];
				$log['remark'] = "投标[<a href=\'/invest/a{$data['id']}.html\' target=_blank><font color=red>{$borrow_name}</font></a>]成功费用扣除";
				$re = accountClass::AddLog($log);
				if($account_result==false || $re==false){
					mysql_query("rollback");
					return false;
				}
				//添加待收的金额
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$log['user_id'] = $value['user_id'];
				$log['type'] = "collection";
				$log['money'] = $value['repayment_account'];
				$log['total'] = $account_result['total']+$log['money'];
				$log['use_money'] = $account_result['use_money'];
				$log['no_use_money'] = $account_result['no_use_money'];
				$log['collection'] = $account_result['collection']+$log['money'];
				$log['to_user'] = $user_id;
				$log['borrow_id'] = $data['id'];
				$log['remark'] = "待收金额[<a href=\'/invest/a{$data['id']}.html\' target=_blank><font color=red>{$borrow_name}</font></a>]";
				$re = accountClass::AddLog($log);
				$sql = " update `{borrow_tender}` set status=1 where status= 5 and id='{$value['id']}'";
				$re_1 = $mysql ->db_query($sql);
				if($account_result==false || $re==false || $re_1==false){
					mysql_query("rollback");
					return false;
				}
				//提醒设置
				$remind['nid'] = "loan_yes_account";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $value['user_id'];
				$remind['title'] = "[借出成功，扣除冻结款]恭喜您，你所投资的标[{$borrow_name}]满标审核成功.";
				$remind['content'] = "恭喜您，你所投资的标[<a href=\'/invest/a{$data['id']}.html\' target=_blank><font color=red>{$borrow_name}</font></a>]在".date("Y-m-d",time())."已经满标审核通过";
				$remind['type'] = "system";
				
				$sendRemind[] = $remind;
				$sendSMS[] = array('user_id'=>$value['user_id'],'content'=>"你的冻结金额于".date("Y-m-d",time())."成功扣除，[{$borrow_name}]标成功满额。");

				$credit['nid'] = "invest_success";
				$result = creditClass::GetTypeOne(array("nid"=>$credit['nid']));
				$credit['user_id'] = $value['user_id'];
				$credit['value'] = round($value['account']/100);
				$credit['op_user'] = $_G['user_id'];;
				$credit['op'] = 1;//增加
				$credit['type_id'] = $result['id'];
				$credit['remark'] = "投资成功加{$credit['value']}分";

				if($is_mb != 1){//秒标和天标不增加积分 jackfeng 2012 weego 20120525
					if($isday!=1){
						$re = creditClass::UpdateCredit($credit);//更新积分
						if($re==false){
							mysql_query("rollback");
						}
					}
				}
				//liukun add for bug 227 begin
				//更新投资人的投标标的还款日期 add by weego 20120614
				for ($i=0;$i<$month_times;$i++){
					//repair by weego 20120525 for 天标还款时间
					if($isday==1){
						$repay_time=strtotime("$time_limit_day days",time());
					}else{
						if($style == 2){
							$repay_time = get_times(array("time"=>time(),"num"=>$month_times));
						}else{
							$repay_time = get_times(array("time"=>time(),"num"=>$i+1));
						}
					}
					// 2012-06-14 修改还款时间 LiuYY
					$to_day = date("Y-m-d 23:59:59", $repay_time);
					$repay_time = strtotime($to_day);
					$sql = " update `{borrow_collection}` set tender_status=1,repay_time={$repay_time} where `order`= {$i} and tender_id='{$value['id']}'";
					$re = $mysql ->db_query($sql);
					if($re == false){
						mysql_query("rollback");
						return false;
					}
				}
				//liukun add for bug 227 end
			}
			//借款者总金额增加。
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$user_id));
			$borrow_log['user_id'] = $user_id;
			$borrow_log['type'] = "borrow_success";
			$borrow_log['money'] = $borrow_account;
			$borrow_log['total'] =$account_result['total']+$borrow_log['money'];
			$borrow_log['use_money'] = $account_result['use_money']+$borrow_log['money'];
			$borrow_log['no_use_money'] = $account_result['no_use_money'];
			$borrow_log['collection'] = $account_result['collection'];
			$borrow_log['to_user'] = "0";
			$borrow_log['borrow_id'] = $id;
			$borrow_log['remark'] = "通过[{$borrow_url}]借到的款";
			$re = accountClass::AddLog($borrow_log);
			if($re == false || $account_result==false){
				mysql_query("rollback");
				return false;
			}
			//$_username = $mysql->db_fetch_array("select username from {user} where user_id=".$user_id);
			//$sendSMS[] = array('user_id'=>$user_id,'content'=>"尊敬的".$_username['username']."会员恭喜您，您所发布的[{$borrow_name}]，已满标复审通过，感谢您对我们的支持与信赖。");
			
			//liukun add for bug 164 begin
			//冻结借款标的保证金10%。
			$frost_rate = $dynaBiaoClass->get_frost_rate();
			if ($frost_rate > 0){
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$user_id));
				$margin_log['user_id'] = $user_id;
				$margin_log['type'] = "margin";
				$margin_log['money'] =round($borrow_account*$frost_rate, 2);
				$margin_log['total'] = $account_result['total'];
				$margin_log['use_money'] = $account_result['use_money']-$margin_log['money'];
				$margin_log['no_use_money'] = $account_result['no_use_money']+$margin_log['money'];
				$margin_log['collection'] = $account_result['collection'];
				$margin_log['to_user'] = "0";
				$margin_log['borrow_id'] = $id;
				$margin_log['remark'] = "冻结借款标的[{$borrow_url}]的保证金";
				$re_1 = accountClass::AddLog($margin_log);
				//更新保证金
				$sql = "update `{borrow}` set forst_account='{$margin_log['money']}' where id='{$id}'";
				$re_2 = $mysql -> db_query($sql);
				if($re_1==false || $re_2==false || $account_result==false){
					mysql_query("rollback");
					return false;
				}
			}
			//liukun add for bug 164 end

			$money = $dynaBiaoClass->getBorrowFee($borrow_result);
			$borrow_fee = $money;
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$user_id));
			$fee_log['user_id'] = $user_id;
			$fee_log['type'] = "borrow_fee";
			$fee_log['money'] = $borrow_fee;
			$fee_log['total'] = $account_result['total']-$fee_log['money'];
			$fee_log['use_money'] = $account_result['use_money']-$fee_log['money'];
			$fee_log['no_use_money'] = $account_result['no_use_money'];
			$fee_log['collection'] = $account_result['collection'];
			$fee_log['to_user'] = "0";
			$fee_log['borrow_id'] = $id;
			$fee_log['remark'] = "借款[{$borrow_url}]的手续费";
			$re = accountClass::AddLog($fee_log);
			if($re==false || $account_result==false){
				$mysql->db_query("rollback");
				return false;
			}
			//更新满标时的操作人
			$nowtime = time();
			//repair by weego 20120525 for 天标还款时间
			if($isday==1){
				$endtime=strtotime("$time_limit_day days",time());
			}else{
				$repay_time = get_times(array("time"=>time(),"num"=>$month_times));
				$repay_time = date("Y-m-d",$repay_time);
				$endtime = strtotime($repay_time." 23:59:59");
			}
			if ($style==1){
				$_each_time = "每三个月后".date("d",$nowtime)."日";
			}else{
				$_each_time = "每月".date("d",$nowtime)."日";
			}
			//liukun add for bug 312 begin 保存借款管理费 begin
			$sql = " update {borrow} set success_time='{$nowtime}',end_time='{$endtime}',each_time='{$_each_time}',payment_account='{$repayment_account}', borrow_fee = {$borrow_fee} where id='{$id}'";
			$re = $mysql ->db_query($sql);
			if($re==false){
				mysql_query("rollback");
				return false;
			}
			//liukun add for bug 312 begin 保存借款管理费 end
			//提醒设置
			$remind['nid'] = "borrow_review_yes";
			$remind['sent_user'] = "0";
			$remind['receive_user'] = $user_id;
			$remind['title'] = "恭喜您，你的借款标[{$borrow_name}]满标审核成功";
			$remind['content'] = "恭喜您，你的借款标[{$borrow_url}]在".date("Y-m-d",time())."已经审核通过";
			$remind['type'] = "system";
			$sendRemind[] = $remind;
			
		}
		//满标审核失败
		elseif ($status == 4){
			//返回所有投资者的金钱。
			$ishappy = $borrow_result['ishappy'];
			$sql = "select p1.*,p2.pIpsAcctNo from {borrow_tender} as p1 left join {user} p2 on p1.user_id=p2.user_id where p1.borrow_id=$id and p1.status=5 order by p1.id";
			$result = $mysql->db_fetch_arrays($sql);
			$tender_result = $result;
			$total_happy_interest = 0;
			foreach ($result as $key => $value){
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
				$log['user_id'] = $value['user_id'];
				$log['type'] = "invest_false";
				$log['money'] = $value['account'];
				$log['total'] = $account_result['total'];
				$log['use_money'] = $account_result['use_money']+$log['money'];
				$log['no_use_money'] = $account_result['no_use_money']-$log['money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = $user_id;
				$log['borrow_id'] = $id;
				$log['remark'] = "招标[{$borrow_url}]失败返回的投标额";
				$re = accountClass::AddLog($log);
				$sendSMS[] = array('user_id'=>$value['user_id'],'content'=>"你的冻结金额已被解冻，[{$borrow_name}]标已被取消。");
				
				if($re==false || $account_result==false){
					mysql_query("rollback");
					return false;
				}

				//提醒设置
				$remind['nid'] = "loan_no_account";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $value['user_id'];
				$remind['title'] = "很遗憾，你所投资的标[{$borrow_name}]满标审核失败";
				$remind['content'] = "很遗憾，你所投资的标[<a href=\'/invest/a{$data['id']}.html\' target=_blank><font color=red>{$borrow_name}</font></a>]在".date("Y-m-d",time())."审核失败,失败原因：{$data['repayment_remark']}";
				$remind['type'] = "system";
				$sendRemind[] = $remind;
			}
			//提醒设置
			$remind['nid'] = "borrow_review_no";
			$remind['sent_user'] = "0";
			$remind['receive_user'] = $user_id;
			$remind['title'] = "很遗憾，您所申请的标[{$borrow_name}]满标审核失败";
			$remind['content'] = "很遗憾，您所申请的标[<a href=\'/invest/a{$data['id']}.html\' target=_blank><font color=red>{$borrow_name}</font></a>]在".date("Y-m-d",time())."审核失败,失败原因：{$data['repayment_remark']}";
			$remind['type'] = "system";
			$sendRemind[] = $remind;
		}
		//
		//如果有设置奖励并且招标成功，或者失败也奖励
		//liukun add for bug 165 begin
		//这里已经没有投标失败也奖励的逻辑了， 因为没有为$is_false设置值
		//liukun add for bug 165 begin
		if ($award==1 || $award==2){
			if ($status == 3 || $is_false==1){
				$sql = "select p1.id,p1.user_id,p1.account,p2.pIpsAcctNo from {borrow_tender} as p1 left join {user} as p2 on p1.user_id=p2.user_id where p1.borrow_id=$id";
				$result = $mysql->db_fetch_arrays($sql);
				$tg_award = array();
				foreach ($result as $key => $value){
					//投标奖励扣除和增加。
					if ($award==1){
						$money = round(($value['account']/$borrow_account)*$part_account,2);
					}elseif ($award==2){
						$money = round((($funds/100)*$value['account']),2);
					}
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
					$log['user_id'] = $value['user_id'];
					$log['type'] = "award_add";
					$log['money'] = $money;
					$log['total'] = $account_result['total']+$money;
					$log['use_money'] = $account_result['use_money']+$money;
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = $user_id;
					$log['borrow_id'] = $id;
					$log['remark'] = "借款[{$borrow_url}]的奖励({$money}元)";
					$re = accountClass::AddLog($log);
					if($re==false || $account_result==false){
						mysql_query("rollback");
						return false;
					}
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$user_id));
					$log['user_id'] = $user_id;
					$log['type'] = "award_lower";
					$log['money'] = $money;
					$log['total'] = $account_result['total']-$money;
					$log['use_money'] = $account_result['use_money']-$money;
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = $value['user_id'];
					$log['borrow_id'] = $id;
					$log['remark'] = "扣除借款[{$borrow_url}]的奖励:{$money}元";
					$re = accountClass::AddLog($log);
					if($re==false || $account_result==false){
						mysql_query("rollback");
						return false;
					}
					$tg_award[] = array('account'=>$money,'pIpsAcctNo'=>$value['pIpsAcctNo'],'id'=>$value['id']);
				}
			}
		}
		//更新满标时的操作人
		$sql = " update {borrow} set repayment_user='{$data['repayment_user']}',repayment_account='{$total_account}',repayment_remark='{$data['repayment_remark']}',repayment_time='".time()."',status='{$data['status']}' where id='{$id}'";
		$result = $mysql ->db_query($sql);
		if($result==false){
			mysql_query("rollback");
		}else{
			if ($status == 3){
				$dynaBiaoClass->full_verify($borrow_result);
				if(IS_TG){
					$juser_result = $mysql->db_fetch_array("select * from {user} where user_id=".$borrow_result['user_id']);
					$a = tg_review($borrow_result,$juser_result);
					$mysql->db_query("update {borrow} set `pErrCode`='{$a['pErrCode']}',`pErrMsg`='{$a['pErrMsg']}' where id={$borrow_result['id']} limit 1");
					if($a['pErrCode']=='0000'){
						mysql_query("commit");
					}else{
						mysql_query("rollback");
						return $a['pErrMsg'];
					}
					if(isset($tg_award) && !empty($tg_award)){
						foreach($tg_award as $k=>$v){
							$data['out_user'] = $juser_result['pIpsAcctNo'];
							$data['in_user'] = $v['pIpsAcctNo'];
							$data['amount'] = $v['account'];
							$data['trade_no'] = time().$data['in_user'].$k.rand(001,999);
							$a = tg_deduct_add($data);
							if($a===true){
								$mysql->db_query('update {borrow_tender} set award_status=1 where id='.$v['id']);
							}else{
								$mysql->db_query('update {borrow_tender} set award_status=2 where id='.$v['id']);
							}
						}
					}
				}else{
					mysql_query("commit");
				}
			}elseif($status == 4){
				if(IS_TG){
					$juser_result = $mysql->db_fetch_array("select * from {user} where user_id=".$borrow_result['user_id']);
					$a = tg_liubiao($borrow_result,$juser_result,$tender_result);
					$mysql->db_query("update {borrow} set `pErrCode`='{$a['pErrCode']}',`pErrMsg`='{$a['pErrMsg']}' where id={$borrow_result['id']} limit 1");
					if($a['pErrCode']=='0000'){
						mysql_query("commit");
					}else{
						mysql_query("rollback");
						return $a['pErrMsg'];
					}
				}else{
					mysql_query("commit");
				}
			}
			foreach($sendRemind as $key=>$value){
				remindClass::sendRemind($value);
			}
			/*
			foreach($sendSMS as $key=>$value){
				sendSMS($value['user_id'],$value['content'],1);
			}
			*/
			
		}
		return $result;
	}
	/*
	 * 获取各个还款方式的利息
	 */
	public static function EqualInterest ($data = array()){
		if (isset($data['borrow_style']) && $data['borrow_style']!=""){
			$borrow_style = $data['borrow_style'];
		}else{
			$borrow_style = 0;
		}
		
		if ($borrow_style==0){
			return self::EqualMonth($data);
		}elseif ($borrow_style==1){
			return self::EqualSeason($data);
		}elseif ($borrow_style==2){
			return self::EqualEnd($data);
		}elseif ($borrow_style==3){
			return self::EqualEndMonth($data);
		}
	
	}
	
	//等额本息法
	//贷款本金×月利率×（1+月利率）还款月数/[（1+月利率）还款月数-1] 
	//a*[i*(1+i)^n]/[(1+I)^n-1] 
	//（a×i－b）×（1＋i）
	public static function EqualMonth ($data = array()){
	 	if (1==2){
		if (isset($data['account']) && $data['account']>0){
			$account = $data['account'];
		}else{
			return "";
		}
		
		if (isset($data['year_apr']) && $data['year_apr']>0){
			$year_apr = $data['year_apr'];
		}else{
			return "";
		}
		
		if (isset($data['month_times']) && $data['month_times']>0){
			$month_times = $data['month_times'];
		}
		if (isset($data['borrow_time']) && $data['borrow_time']>0){
			$borrow_time = $data['borrow_time'];
		}else{
			$borrow_time = time();
		}
		$month_apr = $year_apr/(12*100);
		//如果是天标 weego
		if($data['isday']==1){
			$month_apr=$month_apr*$data['time_limit_day']/30;
		}
		
		$_li = pow((1+$month_apr),$month_times);

		$repayment = @round($account * ($month_apr * $_li)/($_li-1),2);
 
		$_result = array();
		$totalRepaymentMoney = round($repayment*$month_times,2);
		if (isset($data['type']) && $data['type']=="all"){
			$_result['repayment_account'] = round($repayment*$month_times,2);
			$_result['monthly_repayment'] = round($repayment,2);
			$_result['month_apr'] = round($month_apr*100,2);
		 
		}else{
			//$re_month = date("n",$borrow_time);
			for($i=0;$i<$month_times;$i++){
				if ($i==0){
					$interest = round($account*$month_apr,3);
				}else{
					$_lu = pow((1+$month_apr),$i);
					$interest = round(($account*$month_apr - $repayment)*$_lu + $repayment,3);
				}
				$_result[$i]['repayment_account'] =  round($repayment,2); //月还款本息
				
							//repair by weego 20120525 for 天标还款时间
				if($data['isday']==1){
					$_result[$i]['repayment_time'] = strtotime("$data[time_limit_day] days",time());	
				}else{
					$_result[$i]['repayment_time'] = get_times(array("time"=>$borrow_time,"num"=>$i+1));
				}
				
				$_result[$i]['interest'] = round($interest,2); //利息
				$_result[$i]['capital'] = round($repayment-$interest,2); //月还款本金
				if($i==($month_times-1)){
						//0.01问题处理 weego 20120519
				$_result[$i]['repayment_account'] = $_result[$i]['capital']+$_result[$i]['interest'];
					}
				$totalRepaymentMoney=round(($totalRepaymentMoney-$_result[$i]['repayment_account']),2);
			 	if($totalRepaymentMoney<0.1) {$totalRepaymentMoney=0;}
				$_result[$i]['totalRepaymentMoney'] = $totalRepaymentMoney; //余额
				 
			}
		}
 
		return $_result;
	 	}
	 	
	 	if (isset($data['account']) && $data['account']>0){
	 		$account = $data['account'];
	 	}else{
	 		return "";
	 	}
	 	
	 	if (isset($data['year_apr']) && $data['year_apr']>0){
	 		$year_apr = $data['year_apr'];
	 	}else{
	 		return "";
	 	}
	 	
	 	if (isset($data['month_times']) && $data['month_times']>0){
	 		$month_times = $data['month_times'];
	 	}
	 	if (isset($data['borrow_time']) && $data['borrow_time']>0){
	 		$borrow_time = $data['borrow_time'];
	 	}else{
	 		$borrow_time = time();
	 	}
	 	$month_apr = $year_apr/(12*100);
	 	//如果是天标 weego
	 	if($data['isday']==1){
	 		$month_apr=$month_apr*$data['time_limit_day']/30;
	 	}
	 	
	 	$_li = pow((1+$month_apr),$month_times);
	 	
	 	$repayment = @round($account * ($month_apr * $_li)/($_li-1),2);
	 	
	 	$_result = array();
	 	$totalRepaymentMoney = round($repayment*$month_times,2);
	 	if (isset($data['type']) && $data['type']=="all"){
	 		$_result['repayment_account'] = round($repayment*$month_times,2);
	 		$_result['monthly_repayment'] = round($repayment,2);
	 		$_result['month_apr'] = round($month_apr*100,2);
	 	
	 	}else{
	 		//$re_month = date("n",$borrow_time);
	 			
	 		for($i=0;$i<$month_times;$i++){
	 			if ($i==0){
	 				//liukun add for 0.01
	 				//$interest = round($account*$month_apr,3);
	 				$interest = round($account*$month_apr,2);
	 			}else{
	 				$_lu = pow((1+$month_apr),$i);
	 				//liukun add for 0.01
	 				//$interest = round(($account*$month_apr - $repayment)*$_lu + $repayment,3);
	 				$interest = round(($account*$month_apr - $repayment)*$_lu + $repayment,2);
	 			}
	 			$_result[$i]['repayment_account'] =  round($repayment,2); //月还款本息
	 	
	 			//repair by weego 20120525 for 天标还款时间
	 			if($data['isday']==1){
	 				$_result[$i]['repayment_time'] = strtotime("$data[time_limit_day] days",time());
	 			}else{
	 				$_result[$i]['repayment_time'] = get_times(array("time"=>$borrow_time,"num"=>$i+1));
	 			}
	 	
	 			$_result[$i]['interest'] = round($interest,2); //利息
	 			$_result[$i]['capital'] = round($repayment-$interest,2); //月还款本金
	 			//liukun add for bug 232 begin
	 			if (1==2){
	 				if($i==($month_times-1)){
	 					//0.01问题处理 weego 20120519
	 					$_result[$i]['repayment_account'] = $_result[$i]['capital']+$_result[$i]['interest'];
	 				}
	 			}
	 			//liukun add for bug 232 end
	 			$totalRepaymentMoney=round(($totalRepaymentMoney-$_result[$i]['repayment_account']),2);
	 			if($totalRepaymentMoney<0.1) {
	 				$totalRepaymentMoney=0;
	 			}
	 			$_result[$i]['totalRepaymentMoney'] = $totalRepaymentMoney; //余额
	 		}
	 	}
	 	//liukun add for bug 232 begin
	 	$_total_capital = 0;
	 	$_total_interest = 0;

	 	if ($month_times > 1){
	 		for($i=0;$i<$month_times-1;$i++){
	 			$_total_capital += isset($_result[$i]['capital'])?$_result[$i]['capital']:0;
	 			$_total_interest += isset($_result[$i]['interest'])?$_result[$i]['interest']:0;
	 		}
	 		//liukun add for bug 52 begin
	 		//fb(round($repayment*$month_times,2), FirePHP::TRACE);
	 		//fb($_total_capital, FirePHP::TRACE);
	 		//fb($_total_interest, FirePHP::TRACE);
	 		//fb($account, FirePHP::TRACE);
	 		//liukun add for bug 52 end
	 		$_result[$month_times-1]['capital'] = $account - $_total_capital;
	 		$_result[$month_times-1]['repayment_account'] = round($repayment*$month_times, 2) - $_total_capital - $_total_interest;
	 		$_result[$month_times-1]['interest'] = round($_result[$month_times-1]['repayment_account'] - $_result[$month_times-1]['capital'],2);
	 			
	 	}
	 	//liukun add for bug 232 end
	 	return $_result;
	}
	
	//按季等额本息法
	public static function EqualSeason ($data = array()){
	 	
		//借款的月数
		if (isset($data['month_times']) && $data['month_times']>0){
			$month_times = $data['month_times'];
		}
		
		//按季还款必须是季的倍数
		if ($month_times%3!=0){
			return false;
		}
	 
	 	//借款的总金额
		if (isset($data['account']) && $data['account']>0){
			$account = $data['account'];
		}else{
			return "";
		}
		
		//借款的年利率
		if (isset($data['year_apr']) && $data['year_apr']>0){
			$year_apr = $data['year_apr'];
		}else{
			return "";
		}
		
		
		//借款的时间
		if (isset($data['borrow_time']) && $data['borrow_time']>0){
			$borrow_time = $data['borrow_time'];
		}else{
			$borrow_time = time();
		}
		
		//月利率
		$month_apr = $year_apr/(12*100);
		
		//得到总季数
		$_season = $month_times/3;
		
		//每季应还的本金
		$_season_money = round($account/$_season,2);
		
		//$re_month = date("n",$borrow_time);
		$_yes_account = 0 ;
		$repayment_account = 0;//总还款额
		for($i=0;$i<$month_times;$i++){
			$repay = $account - $_yes_account;//应还的金额
			
			$interest = round($repay*$month_apr,2);//利息等于应还金额乘月利率
			$repayment_account = $repayment_account+$interest;//总还款额+利息
			$capital = 0;
			if ($i%3==2){
				$capital = $_season_money;//本金只在第三个月还，本金等于借款金额除季度
				$_yes_account = $_yes_account+$capital;
				$repay = $account - $_yes_account;
				$repayment_account = $repayment_account+$capital;//总还款额+本金
			}
			
			$_result[$i]['repayment_account'] = $interest+$capital;
			$_result[$i]['repayment_time'] = get_times(array("time"=>$borrow_time,"num"=>$i+1));
			$_result[$i]['interest'] = $interest;
			$_result[$i]['capital'] = $capital;
		}
		if (isset($data['type']) && $data['type']=="all"){
			$_resul['repayment_account'] = $repayment_account;
			$_resul['monthly_repayment'] = round($repayment_account/$_season,2);
			$_resul['month_apr'] = round($month_apr*100,2);
			return $_resul;
		}else{
			return $_result;
		}
	}
	
	
	//到期付款
	public static function EqualEnd ($data = array()){
	 	
		//借款的月数
		if (isset($data['month_times']) && $data['month_times']>0){
			$month_times = $data['month_times'];
		}
		
	 
	 	//借款的总金额
		if (isset($data['account']) && $data['account']>0){
			$account = $data['account'];
		}else{
			return "";
		}
		
		//借款的年利率
		if (isset($data['year_apr']) && $data['year_apr']>0){
			$year_apr = $data['year_apr'];
		}else{
			return "";
		}
		
		
		//借款的时间
		if (isset($data['borrow_time']) && $data['borrow_time']>0){
			$borrow_time = $data['borrow_time'];
		}else{
			$borrow_time = time();
		}
		
		//月利率
		$month_apr = $year_apr/(12*100);
		
		$interest = $month_apr*$month_times*$account;
		if (isset($data['type']) && $data['type']=="all"){
			$_resul['repayment_account'] = $account+$interest;
			$_resul['monthly_repayment'] = $account+$interest;
			$_resul['month_apr'] = $month_apr;
			return $_resul;
		}else{
			$_result[0]['repayment_account'] = $account+$interest;
			$_result[0]['repayment_time'] = get_times(array("time"=>$borrow_time,"num"=>$month_times));
			$_result[0]['interest'] = $interest;
			$_result[0]['capital'] = $account;
			return $_result;
		}
	}
	
	
	//到期还本，按月付息
	public static function EqualEndMonth ($data = array()){
	 	
		//借款的月数
		if (isset($data['month_times']) && $data['month_times']>0){
			$month_times = $data['month_times'];
		}
	 
	 	//借款的总金额
		if (isset($data['account']) && $data['account']>0){
			$account = $data['account'];
		}else{
			return "";
		}
		
		//借款的年利率
		if (isset($data['year_apr']) && $data['year_apr']>0){
			$year_apr = $data['year_apr'];
		}else{
			return "";
		}
		
		//借款的时间
		if (isset($data['borrow_time']) && $data['borrow_time']>0){
			$borrow_time = $data['borrow_time'];
		}else{
			$borrow_time = time();
		}
		
		//月利率
		$month_apr = $year_apr/(12*100);

		//$re_month = date("n",$borrow_time);
		$_yes_account = 0 ;
		$repayment_account = 0;//总还款额
		
		$interest = round($account*$month_apr,2);//利息等于应还金额乘月利率
		for($i=0;$i<$month_times;$i++){
			$capital = 0;
			if ($i+1 == $month_times){
				$capital = $account;//本金只在第三个月还，本金等于借款金额除季度
			}
			
			$_result[$i]['repayment_account'] = $interest+$capital;
			$_result[$i]['repayment_time'] = get_times(array("time"=>$borrow_time,"num"=>$i+1));
			$_result[$i]['interest'] = $interest;
			$_result[$i]['capital'] = $capital;
		}
		if (isset($data['type']) && $data['type']=="all"){
			$_resul['repayment_account'] = $account + $interest*$month_times;
			$_resul['monthly_repayment'] = $interest;
			$_resul['month_apr'] = round($month_apr*100,2);
			return $_resul;
		}else{
			return $_result;
		}
	}

	//获取待还总额
	//用户id
	public static function GetWaitPayment($data){
		global $mysql;
		//待还总额
		$user_id= $data['user_id'];
		if($user_id<1) return false;
		$sql = "select t1.status,count(1) as repay_num,sum(t1.repayment_account) as borrow_num ,sum(t1.capital) as capital_num ,sum(t1.repayment_yesaccount) as borrow_yesnum from `{borrow_repayment}` t1,(select id from `{borrow}` where user_id = {$user_id} and status=3) t2 where t1.borrow_id =t2.id group by t1.status ";
		$result = $mysql -> db_fetch_arrays($sql);
		$_result['wait_payment'] = $_result['borrow_yesnum'] = 0;
		$_result['borrow_num0'] = 0;
		$_result['borrow_capital_num0'] = 0;
		$_result['borrow_repay0'] = 0;
		$_result['borrow_yesnum'] = 0;
		$_result['borrow_num2'] = 0;
		$_result['borrow_num1'] = 0;
		$_result['borrow_capital_num'] = 0;
		$_result['amount'] = 0;
		foreach ($result as $key => $value){
			if ($value['status']==0 ){
				$_result['borrow_num0'] = $value['borrow_num'];
				$_result['borrow_capital_num0'] +=$value['capital_num'];//借款的金额
				$_result['borrow_repay0'] = $value['repay_num'];
			}elseif ($value['status']==2){//网站代还
				$_result['borrow_yesnum'] = $value['borrow_yesnum'];
				$_result['borrow_num2'] = $value['borrow_num'];
			}elseif ($value['status']==1){
				$_result['borrow_yesnum'] = $value['borrow_yesnum'];
				$_result['borrow_num1'] = round($value['borrow_num'],2);
			}
			$_result['borrow_capital_num'] +=$value['capital_num'];//借款的金额
		}
		$_result['wait_payment'] = $_result['borrow_num0']+$_result['borrow_num2'];//待还金额
		$_result['borrow_num'] =$_result['borrow_num0']+$_result['borrow_num1']+$_result['borrow_num2'];//借款总额
		$_result['use_amount'] = $_result['amount']-$_result['wait_payment'];//可用额度
		return $_result;
	}

       //取最佳提现金额值
	public static function GetCashGoodAmount($data){
           global $mysql,$_G;
           $user_id = $data['user_id'];
           $sql = "select * from `{account}` where  user_id = '{$user_id}'";
           $result = $mysql->db_fetch_array($sql);
           $use_money = $result['use_money'];
           $collection = $result['collection'];
           $no_use_money = $result['no_use_money'];
           if($no_use_money<0){
           		$use_money-=abs($no_use_money);
           }
           //$_result_wait = self::GetWaitPayment(array("user_id"=>$user_id));
           //$wait_payment = $_result_wait["wait_payment"];//待还金额
           //$jinAmount = $total - $wait_payment;
           /*
           $biaotype=new biaotypeClass();
           //未还信用标
           $sql = "select sum(repayment_account) as repayment_account,sum(repayment_yesaccount) as repayment_yesaccount from `{borrow}` where user_id='{$user_id}' and status=3 and is_xin=1";
           $result = $mysql->db_fetch_array($sql);
           $repayment_xin = $result['repayment_account'] - $result['repayment_yesaccount'];
           $re = $biaotype->get_cash_cost(array('biao_type'=>'xin'));
           $repayment_xin = $repayment_xin<0?0:$repayment_xin*(1-$re['extract_rate']);
           //未还净值标
           $sql = "select sum(repayment_account) as repayment_account,sum(repayment_yesaccount) as repayment_yesaccount from `{borrow}` where user_id='{$user_id}' and status=3 and is_jin=1";
           $result = $mysql->db_fetch_array($sql);
           $repayment_jin = $result['repayment_account'] - $result['repayment_yesaccount'];
           $re = $biaotype->get_cash_cost(array('biao_type'=>'jin'));
           $repayment_jin = $repayment_jin<0?0:$repayment_jin*(1-$re['extract_rate']);
           //未还抵押标
           $sql = "select sum(repayment_account) as repayment_account,sum(repayment_yesaccount) as repayment_yesaccount from `{borrow}` where user_id='{$user_id}' and status=3 and is_fast=1";
           $result = $mysql->db_fetch_array($sql);
           $repayment_fast = $result['repayment_account'] - $result['repayment_yesaccount'];
           $re = $biaotype->get_cash_cost(array('biao_type'=>'fast'));
           $repayment_fast = $repayment_fast<0?0:$repayment_fast*(1-$re['extract_rate']);
           //未还流转标
           $sql = "select sum(repayment_account) as repayment_account,sum(repayment_yesaccount) as repayment_yesaccount from `{borrow}` where user_id='{$user_id}' and status=1 and is_lz=1";
           $result = $mysql->db_fetch_array($sql);
           $repayment_lz = $result['repayment_account'] - $result['repayment_yesaccount'];
           $re = $biaotype->get_cash_cost(array('biao_type'=>'lz'));
           $repayment_lz = $repayment_lz<0?0:$repayment_lz*(1-$re['extract_rate']);
           */
           //$repayment = $repayment_xin+$repayment_jin+$repayment_fast+$repayment_lz;
          // $repayment = $repayment_jin;
		  $repayment = 0;

           $nid = 'common';
           if($_G['user_result']['vip_status']==1){
           		$nid = 'vip_member';
           }
           if(isset($_G['cash_rule'][$nid])){
           	$cash_scheme = $_G['cash_rule'][$nid];
           }else{
           	$sql = "select * from `{cash_rule}` where nid='$nid'";
           	$cash_scheme = $mysql->db_fetch_array($sql);
           }
           $cash_scheme['cash_scale']=$cash_scheme['cash_scale'];//正常提现的费用
           $cash_scheme['fast_cash_scale']=$cash_scheme['fast_cash_scale'];//快速提现的费用

           //正在申请的提现
           $sql = "select sum(total) as num from `{account_cash}` where status=0 and user_id = '{$user_id}'";
           $result = $mysql->db_fetch_array($sql);
           $cashAmountV=$result["num"]==null?0:$result["num"];
           
           //今日已提现金额
           $today_start = strtotime(date("Y-m-d",time()).' 0:0:0');
           $sql = "select sum(total) as num from `{account_cash}` where status=1 and user_id = '{$user_id}' and verify_remark>='$today_start'";
           $result = $mysql->db_fetch_array($sql);
           $today_cash = $result["num"];

           $result=array();
           //$yValueTmp = $use_money-$cashAmountV-$repayment;
           $yValueTmp = $use_money-$repayment;
           if($yValueTmp<0) $yValueTmp=0;
           $result['cash_scheme'] = $cash_scheme;
           //$result['today_cash'] = $cash_scheme['max_day_money']-$today_cash;//今日还可以提现金额
           $result['today_cash'] = $yValueTmp;//今日还可以提现金额
           $result["yValue"]=$yValueTmp;
           $result["txValue"]=$cashAmountV;//正在申请的提现
           return $result;
       }
       /*
        * 提现利息
        */
		public static function GetCashFeeAmount($data){
			global $mysql,$_G;
			$user_id = $data['user_id'];
			$cashAmount = $data['cashAmount'];//提现总额
			$cash_type = $data['cash_type']==1?1:2;//提现模式
           //****************************************
           //计算净资产=use_money + collection - wait_payment=可用余额+待收-待还
           $sql = "select total,use_money,no_use_money,collection from `{account}` where  user_id = '{$user_id}'";
           $result = $mysql->db_fetch_array($sql);
           $use_money = $result['use_money'];//可用余额
           $no_use_money = $result['no_use_money'];
           if($no_use_money<0){
           	$use_money-=abs($no_use_money);
           }
           
           $result = self::GetCashGoodAmount(array('user_id'=>$user_id));
           $yValue=$result['yValue'];
           $max_money=$result['cash_scheme']['max_money'];
           $min_money=$result['cash_scheme']['min_money'];
           $cash_scale=$result['cash_scheme']['cash_scale'];
           $fast_min_money=$result['cash_scheme']['fast_min_money'];
           $fast_cash_scale=$result['cash_scheme']['fast_cash_scale'];
           $today_cash=$result['today_cash'];
           $max_day_money=$result['cash_scheme']['max_day_money'];
		   $min_scale = $result['cash_scheme']['min_scale'];
           $fee=0;
           if($yValue<=0){
           		return array("你的提现金额不足！");
           }
           if($today_cash<$cashAmount){
           		return array("您今日提现已达上限{$max_day_money}元！");
           }
           if($cash_type==2 && $cashAmount<$fast_min_money){
           		return array("快速提现金额不能低于{$fast_min_money}元！");
           }
           if($cashAmount>$max_money || $cashAmount<$min_money){
           		return array("提现金额不能低于{$min_money}元，高于{$max_money}元！");
           }
           if($cashAmount>$yValue){
           		return array("本次提现金额不能高于{$yValue}元！");
           }
           $fee=$cashAmount*$cash_scale;
            if($fee<$min_scale){
            	$fee = $min_scale;
            }
           if($cash_type==2){
           		$fee = $cashAmount*$fast_cash_scale;
           }
		   $fee=round($fee,2);
           return $fee;
       }
	
	//已成功的借款
	public static function GetBorrowSucces($data){
		global $mysql,$_G;
           
		$user_id =$data['user_id'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "";
		$unset = false;
		$_sql .= ' where (bo.status=3 or (bt.status=1 and bo.is_lz=1)) and bt.status=1';
		if (isset($data['type']) && $data['type']!=""){
			if ($data['type']=="wait"){
				$_sql .= " and bt.repayment_yesaccount!=bt.repayment_account";
				$unset = true;
			}elseif ($data['type']=="yes"){
				$_sql .= " and bt.repayment_yesaccount=bt.repayment_account ";
			}
		}else{
			$_sql = ' where bt.status in(1,5)';
		}
        if(isset($data['borrow_status']) && $data['borrow_status']!=""){
			$_sql .= " and bo.status in({$data['borrow_status']})";
		}
		if (isset($data['dotime1']) && $data['dotime1']!=""){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if( !isTimePatternT($dotime1))$dotime1 = "";
			if ($dotime1!=""){
				$_sql .= " and bt.addtime >= ".get_mktime($dotime1);
                        }
                }     
                
		if (isset($data['dotime2']) && $data['dotime2']!=""){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if( !isTimePatternT($dotime2))$dotime2 = "";
			if ($dotime2!=""){
				$_sql .= " and bt.addtime <= ".get_mktime($dotime2);
                        }
                }
                
		if (isset($data['keywords']) && $data['keywords']!=""){
			$keywords = ($data['keywords']=="request")?$_REQUEST['keywords']:$data['keywords'];
			if ($keywords!=""){
				$_sql .= " and bo.name like'%".safegl($keywords)."%'";
                        }
                }
        if(isset($data['user_id'])){
        	$_sql .= " and bt.user_id =".(int)$data['user_id'];
        }
        
		$_select  = "bt.status as tender_status,bt.repayment_yesaccount,bt.repayment_account,bt.addtime as tender_time,bt.account as anum,bt.repayment_account  - bt.account as inter,bo.name as borrow_name,bo.account,bo.time_limit,bo.isday,bo.time_limit_day,bo.apr,u.username,cr.value as credit,bo.id ";
		//$sql = "select SELECT from `{borrow_tender}` as bt,`{borrow}` as bo,`{user}` as u,`{credit}` as cr where bt.user_id={$user_id} and bo.user_id=u.user_id  and cr.user_id=bo.user_id and bt.borrow_id=bo.id and (bo.status=3 or (bt.status=1 and bo.is_lz=1)) {$_sql} order by bo.id desc";
		$sql = "select SELECT from `{borrow_tender}` as bt left join `{borrow}` as bo on bt.borrow_id=bo.id left join `{user}` as u on bt.user_id=u.user_id left join `{credit}` as cr on cr.user_id=bt.user_id {$_sql} order by bo.id desc LIMIT";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.id desc', $_limit), $sql));
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array("count(*) as  num","",""),$sql));

		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));
		$list = $list?$list:array();
		if($unset){
		foreach($list as $k=>$v){
			$a = $mysql->db_fetch_array('select count(1) as c from {borrow_collection} where status=0 and user_id='.$user_id.' and borrow_id='.$v['id']);
			if($a['c']==0){
				unset($list[$k]);
			}
		}
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	
	//收款明细
	public static function GetCollectionList($data){
		global $mysql,$_G;
		$_sql = " ";
		$__sql = " ";
		if (isset($data['user_id']) && $data['user_id']!="" ){
			$__sql .= " where p1.user_id={$data['user_id']}";
		}
		if (isset($data['status']) && $data['status']!="" ){
			$_sql .= " and p1.status=".(int)$data['status'];
		}
		if (isset($data['borrow_status']) && $data['borrow_status']!="" ){
			//$_sql .= " and (p3.status={$data['borrow_status']} or (p2.status=1 and p3.is_lz=1))";
			$_sql .= " and (p3.status=".(int)$data['borrow_status']." or (p3.status=1 and p3.is_lz=1))";
		}
		if (isset($data['username']) && $data['username']!="" ){
			$_sql .= " and p4.username like '%{$data['username']}%' ";
		}
                
                
		if (isset($data['dotime1']) && $data['dotime1']!=""){
			$dotime1 = ($data['dotime1']=="request")?$_REQUEST['dotime1']:$data['dotime1'];
			if( !isTimePatternT($dotime1))$dotime1 = "";
			if ($dotime1!=""){
				$_sql .= " and p1.repay_time >= ".get_mktime($dotime1);
                        }
                }     
                
		if (isset($data['dotime2']) && $data['dotime2']!=""){
			$dotime2 = ($data['dotime2']=="request")?$_REQUEST['dotime2']:$data['dotime2'];
			if( !isTimePatternT($dotime2))$dotime2 = "";
			if ($dotime2!=""){
				$_sql .= " and p1.repay_time <= ".get_mktime($dotime2);
                        }
                }
                
		if (isset($data['keywords']) && $data['keywords']!=""){
			$keywords = ($data['keywords']=="request")?$_REQUEST['keywords']:$data['keywords'];
			if ($keywords!=""){
				$_sql .= " and p3.name like'%".safegl($keywords)."%'";
                        }
                }
                
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_select = 'p1.*,p3.name as borrow_name,p3.id as borrow_id,p3.time_limit,p4.username, p3.biao_type ';
		$_order = " order by p1.id ";

		if (isset($data['order']) && $data['order']!="" ){
			if ($data['order'] == "repay_time"){
				$_order = " order by p1.repay_time asc ";
			}elseif ($data['order'] == "order"){
				$_order = " order by p1.`order` desc,p1.id desc ";
			}
		}
		/*
		$sql = "select SELECT from `{borrow_collection}` as p1 
				left join `{borrow_tender}` as p2 on  p1.tender_id = p2.id
				left join `{borrow}` as p3 on  p3.id = p2.borrow_id
				left join `{user}` as p4 on  p4.user_id = p3.user_id
				where p1.tender_id in (select id from `{borrow_tender}`{$__sql})
			   {$_sql}  ORDER LIMIT";
				*/
		$sql = "select p1.*,p3.name as borrow_name,p3.biao_type,p3.id as borrow_id,p3.time_limit,p3.apr,p3.isday,p3.time_limit as time_limit_month,p3.time_limit_day,p4.username from `{borrow_collection}` as p1 left join `{borrow}` as p3 on p1.borrow_id=p3.id left join {user} as p4 on p3.user_id=p4.user_id 
{$__sql}{$_sql}  ORDER LIMIT";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list  = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  $_order, $_limit), $sql));
			foreach ($list as $key => $value){
				if($value['is_buy']==1){
					$list[$key]['borrow_name'] = $value['borrow_name'].'[债权购买所得]';
				}
				$repay_data['repayment_time']=$value['repay_time'];
				$repay_data['repayment_account']=$value['repay_account'];
				$repay_data['capital']=$value['capital'];
				$repay_data['status']=$value['status'];
				$repay_data['biao_type']=$value['biao_type'];
				$late = self::LateCollectionInterest($repay_data);
				$list[$key]['mytime']=$value['repay_time']-time();
			}
			return $list;
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(" count(*) as num ","",""),$sql));
		
		$total = isset($row['num'])?$row['num']:0;
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order , $limit), $sql));		

		$list = $list?$list:array();
		foreach ($list as $key => $value){
			if($value['is_buy']==1){
				$list[$key]['borrow_name'] = '[债权购买]'.$value['borrow_name'];
			}
			if($value['is_sell']==1){
				//$list[$key]['borrow_name'] = '[债权已转让]'.$value['borrow_name'];
			}
			$repay_data['repayment_time']=$value['repay_time'];
			$repay_data['repayment_account']=$value['repay_account'];
			$repay_data['capital']=$value['capital'];
			$repay_data['status']=$value['status'];
			$repay_data['biao_type']=$value['biao_type'];
			$late = self::LateCollectionInterest($repay_data);
			$list[$key]['mytime']=$value['repay_time']-time();
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	
	
	function GetBorrowAll($data=array()){
		global $mysql;
		$user_id = $data['user_id'];
		$sql = "select * from `{borrow}` where user_id = {$user_id}";
		$result = $mysql->db_fetch_arrays($sql);
		$_result['success'] = $_result['false'] =  $_result['wait'] = $_result['pay_success'] = $_result['pay_advance'] = $_result['pay_expired'] = 0; 
		foreach ($result as $key => $value){
			if ($value['status']==3){
				$_result['success'] ++;
			}
			if ($value['status']==3 && $value['repayment_account']!=$value['repayment_yesaccount']){
				$_result['wait'] ++;
			}
			if ($value['status']==0 || $value['status']==4){
				$_result['false'] ++;
			}
		}
		$sql = "select * from `{borrow_repayment}` t1,(select id from `{borrow}` where user_id = {$user_id} and status=3) t2 where t1.borrow_id =t2.id";
		$result = $mysql->db_fetch_arrays($sql);
		foreach ($result as $key => $value){
			//已还款未过期
			//if ($value['status']==1 && $value['repayment_time']<$value['repayment_yestime']){
			if ($value['status']==1 && $value['repayment_yestime'] >0 ){
				$_result['pay_success'] ++;
			}
			//已还款过期
			if ($value['status']==1 && $value['repayment_time']>$value['repayment_yestime']){
				$_result['pay_expired'] ++;
			}
			//逾期未还
			if (($value['status']==0 || $value['status']==2 ) &&  date("Ymd",$value['repayment_time'])<date("Ymd",time())){
				$_result['pay_expiredno'] ++;
			}
			//逾期已还
			if ($value['status']==1 && date("Ymd",$value['repayment_time'])<date("Ymd",$value['repayment_yestime'])){
				$_result['pay_expiredyes'] ++;
			}
			//提前还款(提前3天还款算提前还款)
			if ($value['status']==1 && ($value['repayment_time']-$value['repayment_yestime'])>60*60*24*2){
				$_result['pay_advance'] ++;
			}
			//30天之外的逾期还款
			if ($value['status']==1 && $value['repayment_yestime']-$value['repayment_time']>60*60*24*30){
				$_result['pay_expired30'] ++;
			}
			//30天之内的逾期还款 
			if ($value['status']==1 && $value['repayment_yestime']-$value['repayment_time']>60*60*24 && $value['repayment_yestime']-$value['repayment_time']<60*60*24*30){
				$_result['pay_expired30in'] ++;
			}
		}
		$ti1 = $mysql->db_fetch_array('select count(1) as ti from {borrow_repayment} as p1 left join {borrow} as p2 on p1.borrow_id=p2.id  where p1.status>0 and p1.repayment_yestime<p1.repayment_time and p2.user_id='.$user_id);
		$ti2 = $mysql->db_fetch_array('select count(1) as ti from {borrow_repayment} as p1 left join {borrow} as p2 on p1.borrow_id=p2.id where p1.status>0 and p2.user_id='.$user_id);
		if($ti1['ti']!=0 && $ti2['ti']!=0){
			$_result['pay_advance'] = round($ti1['ti']/$ti2['ti']);
		}else{
			$_result['pay_advance'] = 0;
		}
		return $_result;	
	}
	
	function GetAll($data=array()){
		global $mysql;
		$sql = "select sum(account) as sum from `{borrow}`";
		$result = $mysql->db_fetch_array($sql);
		$_result['borrow_all'] = $result['sum'];
		
		$sql = "select sum(account) as sum from `{borrow}` where status=3";
		$result = $mysql->db_fetch_array($sql);
		$_result['borrow_yesall'] = $result['sum'];
		
		
		$sql = "select count(account) as num from `{borrow}`";
		$result = $mysql->db_fetch_array($sql);
		$_result['borrow_times'] = $result['num'];
		
		$sql = "select count(account) as num from `{borrow}` where status=3";
		$result = $mysql->db_fetch_array($sql);
		$_result['borrow_yestimes'] = $result['num'];
		
		return $_result;
	}
	
	function ActionLiubiao($data){
		global $mysql;
		$status= $data['status'];
		if ($status==1){
			$result = self::Cancel($data);
		}elseif($status==2){
			$valid_time = $data['days'];
			$sql = "update `{borrow}` set valid_time=valid_time +{$valid_time} where id={$data['id']}";
			$result = $mysql->db_query($sql);
		}
		return $result;
	}
	

	//逾期还款列表
	function GetLateList($data = array()){
		global $mysql,$_G;
		
		$page = (!isset($data['page']) || $data['page']=="")?1:$data['page'];
		$epage = (!isset($data['epage']) || $data['epage']=="")?10:$data['epage'];
		
		$_select = 'p1.*,p3.*, p2.biao_type';
		$_order = " order by p1.id ";
		if (isset($data['late_day']) && $data['late_day']!=""){
			$_repayment_time = time()-60*60*24*$data['late_day'];
		}else{
			$_repayment_time = time();
		}
		
		$_sql = " where p1.repayment_time < '{$_repayment_time}' and p1.status!=1 and p1.borrow_id>0";
		$sql = "select SELECT from `{borrow_repayment}` as p1 
				left join `{borrow}` as p2 on p1.borrow_id=p2.id
				left join `{user}` as p3 on p2.user_id=p3.user_id
			   {$_sql} ORDER LIMIT";

		$_list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order , ""), $sql));	
			
		foreach ($_list as $key => $value){
			$repay_data['repayment_time']=$value['repayment_time'];
			$repay_data['repayment_account']=$value['repayment_account'];
			$repay_data['capital']=$value['capital'];
			$repay_data['status']=$value['status'];
			$repay_data['biao_type']=$value['biao_type'];
			$late = self::LateRepaymentInterest($repay_data);
			
			$list[$value['user_id']]['realname'] = $value['realname'];
			$list[$value['user_id']]['phone'] = $value['phone'];
			$list[$value['user_id']]['user_id'] = $value['user_id'];
			$list[$value['user_id']]['email'] = $value['email'];
			$list[$value['user_id']]['qq'] = $value['qq'];
			$list[$value['user_id']]['sex'] = $value['sex'];
			$list[$value['user_id']]['card_id'] = $value['card_id'];
			$list[$value['user_id']]['area'] = $value['area'];
			$list[$value['user_id']]['late_days'] += $late['late_days'];//总逾期天数
			if ($list[$value['user_id']]['late_numdays']<$late['late_days']){
				$list[$value['user_id']]['late_numdays'] =  $late['late_days'];
			}
			$list[$value['user_id']]['late_interest'] += $late['late_interest'];
			$list[$value['user_id']]['late_account'] +=  $value['repayment_account'];//逾期总金额
			$list[$value['user_id']]['late_num'] ++;//逾期笔数
			if ($value['webstatus']==1){
				$list[$value['user_id']]['late_webnum'] +=1;//逾期笔数
			}
		}
		//是否显示全部的信息
		if (isset($data['limit']) ){
			if (count($list)>0){
			return array_slice ($list,0,$data['limit']);
			}else{
			return array();
			}
		}
		$total = count($list);
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		if (is_array($list)){
		$list = array_slice ($list,$index,$epage);
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	

	//我的客户列表
	function GetMyuserList($data = array()){
		global $mysql,$_G;
		
		$page = (!isset($data['page']) || $data['page']=="")?1:$data['page'];
		$epage = (!isset($data['epage']) || $data['epage']=="")?10:$data['epage'];
		
		$_select = 'p1.*,p2.realname,p2.username';
		$_order = " order by p1.id ";
		$_sql = "";
		if (isset($data['suser_id']) && $data['suser_id']!=""){
			$_sql .= " and p1.user_id='{$data['suser_id']}'";
		}
		$sql = "select SELECT from `{borrow}` as p1 left join `{user}` as p2 on p1.user_id=p2.user_id where p1.user_id in (select user_id from `{user_cache}` where kefu_userid = '{$data['user_id']}') {$_sql} ORDER LIMIT";

		//是否显示全部的信息
		if (isset($data['limit']) && $data['limit']!="" ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.`order` desc,p1.id desc', $_limit), $sql));
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array("count(*) as  num","",""),$sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	
	}
	
	//统计
	function GetMyuserAcount($data = array()){
		global $mysql,$_G;
		$user_id = $data['user_id'];
		
		//第一步，先读取出客服下面的用户
		$sql = "select user_id from `{user_cache}` where kefu_userid = {$user_id}";
		$result = $mysql->db_fetch_arrays($sql);
		if ($result!=""){
			foreach ($result as $key => $value){
				$_result[] = $value["user_id"];
			}
			$_fuserid = join(",",$_result);
		}
		$_first_month = strtotime("2010-08-01");
		$_now_year = date("Y",time());
		$_now_month = date("n",time());
		$month = ($_now_year-2011)*12 + 5+$_now_month;//现在的月数
		
		//成功借款
		for ($i=1;$i<=$month;$i++){
			$up_month = strtotime("$i month",$_first_month);
			$now_month = strtotime("-1 month",$up_month);
			$nowlast_day = strtotime("-1 day",$up_month);
			
			$sql = "select sum(money) as num_money from `{account_log}` where user_id in ($_fuserid) and type='borrow_success' and addtime >= {$now_month} and addtime < {$nowlast_day}";
			$result = $mysql->db_fetch_array($sql);
			if ($result["num_money"]!=""){
				$_resul[date("Y-n",$now_month)]["borrow"] = $result["num_money"];
			}
			
			$sql = "select sum(money) as num_money from `{account_log}` where user_id in ($_fuserid) and type='invest' and addtime >= {$now_month} and addtime < {$nowlast_day}";
			$result = $mysql->db_fetch_array($sql);
			if ($result["num_money"]!=""){
				$_resul[date("Y-n",$now_month)]["tender"] = $result["num_money"];
			}
			$sql = "select count(1) as num_vip from `{account_log}` where user_id in ($_fuserid) and type='vip' and addtime >= {$now_month} and addtime < {$nowlast_day}";
			$result = $mysql->db_fetch_array($sql);
			if ($result["num_vip"]>0){
				$_resul[date("Y-n",$now_month)]["vip"] = $result["num_vip"];
			}
		}
		arsort($_resul);
		return $_resul;
	}
	
	//统计
	function Tongji($data = array()){
		global $mysql;
		//成功借款
		$sql = " select sum(account) as num from `{borrow}` where status=3 ";
		$result = $mysql->db_fetch_array($sql);
		$_result['success_num'] = $result['num'];
		//逾期未还款
		$_repayment_time = time();;
		$sql = " select p1.capital,p1.repayment_yestime,p1.repayment_time,p1.status  from  `{borrow_repayment}` as p1 left join `{borrow}` as p2 on p1.borrow_id=p2.id where p2.status=3 ";
		$result = $mysql->db_fetch_arrays($sql);
		foreach ($result as $key => $value){
			$_result['success_sum'] += $value['capital'];//借款总额
			if ($value['status']==1){
				$_result['success_num1'] += $value['capital'];//成功还款总额
				if (date("Ymd",$value['repayment_time']) < date("Ymd",$value['repayment_yestime'])){	
					$_result['success_laterepay'] += $value['capital'];
				}
			}
			if ($value['status']==0){
				$_result['success_num0'] += $value['capital'];//未还款总额
				if (date("Ymd",$value['repayment_time']) < date("Ymd",time())){	
					$_result['false_laterepay'] += $value['capital'];
				}
			}
		}
		$_result['laterepay'] = $_result['success_laterepay'] + $_result['false_laterepay'];
		return $_result;
	}
	/**
	 * 添加自动投标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function add_auto($data = array()){
		global $mysql;global $_G;
		$csql="select count(*) as t from`{borrow_auto}` where user_id={$data['user_id']} ";
		$cn = $mysql->db_fetch_array($csql);
        if(isset($data['auto_id'])&&is_numeric($data['auto_id'])){
            //
        }else{
        	if($cn['t']>=1){//只能添加一条规则
        		return false;
        	}
        }
		if($data['tender_scale']>20) $data['tender_scale'] = 20;
		$_sql=array();
		$_table_field =  $mysql->db_show_fields("borrow_auto");//获取当前用户的余额
		foreach($_table_field as $field_v){
			if(isset($data[$field_v])) $_sql[]="`$field_v` = '".$data[$field_v]."'";
			elseif($field_v == 'id') "";
			else  $_sql[]="`$field_v` = '0'";
		}
		if(isset($data['auto_id'])&&is_numeric($data['auto_id'])){
			$sql = "update `{borrow_auto}` set ";
		}else{
			$sql = "insert into `{borrow_auto}` set ";
		}
		$sql.=join(",",$_sql);
		if(isset($data['auto_id'])&&is_numeric($data['auto_id'])){
			$sql .= " where  id = {$data['auto_id']} ";
		}
		return $mysql->db_query($sql);
	}
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	/*
	function add_auto_back($data = array()){
		global $mysql;global $_G;
			$csql="select id from`{auto_back}` where user_id={$data['user_id']} ";
			$cn = $mysql->db_fetch_array($csql);
			$_sql=array();
			$_table_field =  $mysql->db_show_fields("auto_back");//获取当前用户的余额
			foreach($_table_field as $field_v){
				if(isset($data[$field_v])) $_sql[]="`$field_v` = '".$data[$field_v]."'";
				elseif($field_v == 'id') "";
				else  $_sql[]="`$field_v` = '0'";
			}
			if(isset($data['auto_back_id'])&&is_numeric($data['auto_back_id'])){
				$sql = "update `{auto_back}` set ";
			}else{
				$sql = "insert into `{auto_back}` set ";
			}
			$sql.=join(",",$_sql);
			if(isset($data['auto_back_id'])&&is_numeric($data['auto_back_id'])){
				$sql .= " where  id = {$data['auto_back_id']} ";
			}
			return $mysql->db_query($sql);
	}*/
	/*
	 * 获取自动投标信息
	*/
	function GetAutoList($data = array()){
		global $mysql;global $_G;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$_select = " p1.* ";
		$_sql = "where 1=1 ";	
		if (isset($data['user_id'])  && $data['user_id']!=""){
			$_sql .= " and p1.user_id = {$data['user_id']}";
		}
		$_order = " order by p1.`id` desc ";
		$_limit = "  limit 0,3";
		$sql = "select SELECT from `{borrow_auto}` as p1 $_sql ORDER LIMIT";
		$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		return $list;

	}
	/*
	 * 获取自动投标信息
	 */
	function GetAutoId($id){
		global $mysql;global $_G;
		$user_id = $_G['user_id'];
		$_select = " p1.* ";
		$_where = "where 1=1 "; 
		if (isset($user_id)  && $user_id!=""){
			$_where .= " and p1.user_id = {$user_id}";
		}
		if (isset($id)  && $id!=""){
			$_where .= " and p1.id = {$id}";
		}
		$_order = " order by p1.`id` desc ";
		$_limit = "  limit 0,1";
		$sql = "select SELECT from `{borrow_auto}` as p1 $_where ORDER LIMIT";
		$row =  $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		return $row;
	}

	/*
	function GetAutoBackId(){
		global $mysql;global $_G;
		$user_id = $_G['user_id'];
		$_select = " p1.* ";
		$_where = "where 1=1 ";
		if (isset($user_id)  && $user_id!=""){
			$_where .= " and p1.user_id = {$user_id}";
		}
		$_order = " order by p1.`id` desc ";
		$_limit = "  limit 0,1";
		$sql = "select SELECT from `{auto_back}` as p1 $_where ORDER LIMIT";
		$row =  $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		return $row;
	}*/
	/**
	 * 获取待还列表
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	/*
	function get_back_list($data = array()){
		global $mysql;global $_G;
			$ausql="select * from `{borrow_repayment}` where borrow_id = ".$data['id']."";
			$au_row = $mysql->db_fetch_arrays($ausql);//自动投标的用户
			return $au_row;
	}*/
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	/*
	function add_fast_biao($data = array()){
		global $mysql;global $_G;
		
			$_sql=array();
			$_table_field =  $mysql->db_show_fields("daizi");//获取当前用户的余额
			foreach($_table_field as $field_v){
				if(is_array($data[$field_v])){
					$data[$field_v] = implode(",",$data[$field_v]);
				}
				if(isset($data[$field_v])) $_sql[]="`$field_v` = '".$data[$field_v]."'";
				elseif($field_v == 'id') "";
				else  $_sql[]="`$field_v` = '0'";
			}
			
				$sql = "insert into `{daizi}` set ";
			
			$sql.=join(",",$_sql);
			$mysql->db_query($sql);
			$newid = $mysql->db_insert_id();
			return $newid;
	}
	*/
	/**
	 * 删除自动投标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function del_auto($id){
		global $mysql;
		$where =" id ='{$id}' ";
		return $mysql->db_delete("borrow_auto",$where);
	}
	/*
	 * 自动投标
	 */
	function auto_borrow($data_s=array()){
		return true;
		global $mysql;global $_G;
		include_once(ROOT_PATH."modules/account/account.class.php");
		$borrow_id=$data_s['id'];
		$borrow_result = self::GetOne(array("id"=>$borrow_id));//获取借款标的单独信息
		/* 2012-06-14 自动投标新算法 By:Weego */
		$usql = "select user_id from view_auto_invest where user_id<>'".$data_s['user_id']."'";  
        $result = $mysql->db_fetch_arrays($usql);
		if($result==false){
			return;
		}else{
			$have_auto_do=array();
			foreach($result as $key => $value){
                $ausql="select * from `{borrow_auto}` where user_id = ".$value['user_id'];
				$v = $mysql->db_fetch_array($ausql);//自动投标信息
                $borrow_result = self::GetOne(array("id"=>$borrow_id));//获取借款标的单独信息
            	if($borrow_result['is_mb'] == 1 || $borrow_result['is_vouch'] == 1 || $borrow_result['pwd']<>''){
                	return;//不允许自动投标
                }
				if(in_array($v['user_id'],$have_auto_do)){
					continue;
				}else{
					$uss = "select * from `{user}` where user_id = '".$v['user_id']."'";
					$u_row_detail = $mysql->db_fetch_arrays($uss);//当前自动投标的用户信息
				}
				if($v['tender_type']==1){
					$account_money=$v['tender_account'];
					$account_money_s=$v['tender_account'];
					
					//bug a1 begin
					if ($v['tender_total_account']==0){
					}else{
						$user_auto_tender_total_re = self::getAutoTenderAccount(array("user_id"=>$v['user_id']));
						$user_auto_tender_total = $user_auto_tender_total_re['auto_tener_total'];
						//如果已经没有自动投标额度，停止自动投标
						if ($v['tender_total_account'] <= $user_auto_tender_total)
							continue;
						
						//如果当前自动投标余额小于用户设置的投标金额，以投标余额为准
						$can_auto_account = $v['tender_total_account'] - $user_auto_tender_total;
						if ($can_auto_account < $v['tender_account']){
							$account_money=$can_auto_account;
							$account_money_s=$can_auto_account;
						}
					}
					//bug a1 end
					
				}elseif($v['tender_type']==2){
					$account_money=($v['tender_scale']*$data_s['total_jie']/100);
					$account_money_s=($v['tender_scale']*$data_s['total_jie']/100);
				}
				if($account_money < $data_s['zuishao_jie']){
					continue;//不符合最低投资金额
				}
				//借款人信息
				$jksql="select * from `{user}` where user_id='".$borrow_result['user_id']."'";
				$jkr_row = $mysql->db_fetch_array($jksql);
				$jksql2="select * from `{user_cache}` where user_id='".$borrow_result['user_id']."'";
				$jkr_rowCache = $mysql->db_fetch_array($jksql2);
				
				if($v['video_status']==1 && $jkr_row['video_status']==1){
				
				}elseif(empty($v['video_status'])){
				
				}else{
					continue;
				}
				
				if($v['scene_status'] == 1&&$jkr_row['scene_status']==1){
				
				}elseif(empty($v['scene_status'])){
				
				}else{
					continue;
				}
				
				//借款信息
                                
               if($v['borrow_credit_status'] == 1){
					if($v['borrow_credit_first']<=$jkr_rowCache['credit']&&$v['borrow_credit_last']>=$jkr_rowCache['credit']){
					
					}else{
						continue;
					}
				}        
				if($v['award_status'] == 1){
                                    if($v['award_first'] > 0){ 
					if($v['award_first']<=$borrow_result['funds']){
					
					}else{
						continue;
					}
                                    }
				}
				if($v['apr_status'] == 1){
					if($v['apr_first']<=$borrow_result['apr']&&$v['apr_last']>=$borrow_result['apr']){
					
					}else{
						continue;
					}
				
				}
				//bug a1 begin
				/*
				//抵押标
				if($borrow_result['is_fast'] == 1){
					if($v['fast_status'] != 1){
						continue;//不符合条件
					}
                }
                if($borrow_result['is_jin'] == 1){
					if($v['jin_status'] != 1){
						continue;//不符合条件
					}
                }
                if($borrow_result['is_fast'] !=1 && $borrow_result['is_jin'] !=1 && $borrow_result['is_mb'] !=1 && $borrow_result['is_vouch'] !=1){
					if($v['xin_status'] != 1){
						continue;//不符合条件
					}
                }
                */
				//bug a1 end
                //设定自动投标的百分比参数，剩下的给手动 add by weego 20120525 begin
				$auto_borrow_per='0.7';
				if($v['timelimit_status'] == 1){
					if($borrow_result['isday']==1){
						//如果是天标
						if($v['timelimit_day_first']<=$v['timelimit_day_last'] 
								&& $v['timelimit_day_last']>=$borrow_result['time_limit_day'] 
								&& $v['timelimit_day_first']<=$borrow_result['time_limit_day'] 
								&& $v['timelimit_day_first']>0){
						}else{
							continue;
						}

					}else{
						//如果是月标					
						if($v['timelimit_month_first']<=$v['timelimit_month_last'] 
								&& $v['timelimit_month_last']>=$borrow_result['time_limit'] 
								&& $v['timelimit_month_first']<=$borrow_result['time_limit'] 
								&&$v['timelimit_month_first']>0){
						}else{
							continue;
						}
					}
				}
				 //设定自动投标的百分比参数，剩下的给手动 add by weego 20120525 end
				if ($u_row_detail['islock']==1){
					continue;//$msg = array("您账号已经被锁定，不能进行投标，请跟管理员联系");
				}elseif (!is_array($borrow_result)){
					continue;//$msg = array($borrow_result);
				}elseif ($borrow_result['account_yes']>=($borrow_result['account']*$auto_borrow_per)){
					//continue;//$msg = array("此标已满，请勿再投");
                    return;
				}elseif ($borrow_result['verify_time'] == "" || $borrow_result['status'] != 1){
					return;//$msg = array("此标尚未通过审核");
				}elseif(!is_numeric($account_money)){
					continue;//$msg = array("请输入正确的金额");
				}elseif ($borrow_result['most_account']>0 && ($borrow_result['tender_yes'] > $borrow_result['most_account'] || $borrow_result['tender_yes']+$account_money>$borrow_result['most_account'])){
                    continue;//$msg = array("你的总投标金额".($borrow_result['tender_yes']+$account_money)."已经超过最高金额{$borrow_result['most_account']}");
				}else{
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$v['user_id']));//获取当前用户的余额
					if (($borrow_result['account']*$auto_borrow_per-$borrow_result['account_yes'])<$account_money){
						$account_money = $borrow_result['account']*$auto_borrow_per-$borrow_result['account_yes'];
					}
					if($account_result['use_money']<=0){
						continue;
					}
					if ($account_result['use_money']<$account_money){
						$account_money = $account_result['use_money'];
					}
					if($borrow_result['biao_type']=='lz'){
						$account_money = intval($account_money/100)*100;
						if($account_money==0) continue;
					}
					$data['borrow_id'] = $borrow_id;
					$data['money'] = $account_money_s;
					$data['account'] = $account_money;
					$data['user_id'] = $v['user_id'];
					$data['status'] = 5;
					//bug a1 begin 
					$data['is_auto'] = 1;
					//bug a1 end 
					$result = self::AddTender($data);//添加借款标
					if($result == true){
						$have_auto_do[]=$v['user_id'];//不再判断此用户
						continue;
					}
				}
			}//foreach
		}//if false
	}//function 
	/*
	 * 获取标的信息
	 */
	function get_biao_type_info($data = array()){
		global $mysql,$_G;
		$biao_type = $data['biao_type'];
		if(isset($_G['biao_type'][$biao_type])){
			return $_G['biao_type'][$biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
			if($result){
				return $result;
			}else{
				return false;
			}
		}
	}
	/*
	 *用户统计
	*/
	public function borrowStatistics($data=array()){
		global $mysql;
		$where = ' where p2.status=3';
		$salesman_user = intval($data['salesman_user']);
		if($salesman_user>0){//业务员
			$where .= ' and p1.salesman_user='.$salesman_user;
		}elseif($salesman_user==0){
			$where .= ' and p1.salesman_user=0';
		}
		if($data['dotime1']!=''){
			$dotime1 = intval(strtotime($data['dotime1'].' 0:0:0'));
			$where .= ' and p1.addtime>'.$dotime1;
		}
		if($data['dotime2']!=''){
			$dotime1 = intval(strtotime($data['dotime2'].' 23:59:59'));
			$where .= ' and p1.addtime>'.$dotime2;
		}
		if($data['belong_organ']!=''){//所属机构
			$where .= " and p1.belong_organ='".htmlspecialchars($data['belong_organ'])."'";
		}
		if($data['recommend_organ']!=''){//推荐机构
			$where .= " and p1.recommend_organ='".htmlspecialchars($data['recommend_organ'])."'";
		}
		if(($user_type = intval($data['user_type']))>0){
			$where .= ' and p1.user_type='.$user_type;
		}
		if(($province = intval($data['province']))>0){
			$where .= ' and p1.province='.$province;
		}
		if(($city = intval($data['city']))>0){
			$where .= ' and p1.city='.$city;
		}
		$sql = 'select count(p2.id) count,sum(p2.account_yes) account_yes from {borrow} p2 left join {user} p1 on p2.user_id=p1.user_id '.$where;
		return $mysql->db_fetch_array($sql);
	}
	/*
	*首页成功案例
	*/
	function IndexSuccessAl($data=array()){
		global $mysql;
		$limit = isset($data['limit'])?intval($data['limit']):1;
		$sql = 'select id,name from {borrow} where status=3 order by repayment_time desc limit '.$limit;
		return $mysql->db_fetch_arrays($sql);
	}
	/*
	*首页最新投标信息
	*/
	function GetNewTender($data=array()){
		global $mysql;
		$limit = isset($data['limit'])?$data['limit']:10;
		if($data['limit']>0){
			$limit = ' limit '.intval($limit);
		}
		$where = '';
		$sql = 'select p1.borrow_id, p1.account,p1.addtime,p2.username, p2.user_id,p3.name from {borrow_tender} p1 left join {user} p2 on p1.user_id=p2.user_id left join {borrow} as p3 on p1.borrow_id=p3.id '.$where.' order by p1.id desc '.$limit;
		return $mysql->db_fetch_arrays($sql);
	}
	/*
	*首页最新回款信息
	*/
	function GetNewRepay($data=array()){
		global $mysql;
		$limit = isset($data['limit'])?$data['limit']:10;
		if($data['limit']>0){
			$limit = ' limit '.intval($limit);
		}
		$where = ' where p1.repayment_yestime>0';
		$order = ' order by repayment_yestime desc';
		$sql = 'select p1.borrow_id, p1.repayment_yesaccount,p1.repayment_yestime,p3.name,p2.username from {borrow_repayment} p1 left join {borrow} as p3 on p1.borrow_id=p3.id left join {user} p2 on p3.user_id=p2.user_id'.$where.$order.$limit;
		return $mysql->db_fetch_arrays($sql);
	}
	/*
	 * 获取用户资料图片
	 */
	function GetBorrowAttestationList($data){
		global $mysql;
		if(isset($data['attestationid']) && $data['attestationid']!=""){
			$where = " where p1.id in({$data['attestationid']})";
			$sql = "select p1.*,p2.name type_name from {attestation} p1 left join {attestation_type} p2 on p1.type_id=p2.type_id $where";
			$re = $mysql->db_fetch_arrays($sql);
			return $re;
		}
		return false;
	}
	/*
	 * 获取用户上传对应标的协议书
	 */
	function GetBorrowProtocol($data=array()){
		global $mysql;
		if(isset($data['borrow_id']) && $data['borrow_id']!=""){
			$sql = "select * from {attestation} where borrow_id={$data['borrow_id']} and upload_type=3";
			$re = $mysql->db_fetch_arrays($sql);
			$type_name = array(1=>"借款协议",2=>"借款承诺书",3=>"逾期处罚确认书");
			foreach ($re as $k=>$v){
				$key = array_search($v['name'], $type_name);
				if($key>0){
					unset($type_name[$key]);
				}
			}
			foreach ($type_name as $k=>$v){
				$re[] = array('name'=>$v);
			}
			return $re;
		}
		return false;
	}
	
	/*
	 * 获取自动投标总金额（待收本金）
	 */
	public static function getAutoTenderAccount($data=array()){
		global $mysql,$TG_OB;
		$user_id = $data['user_id'];
		$sql = "select ifnull(sum(capital),0) as auto_tener_total from `{borrow_collection}` bc

				left join `{borrow}` bw on  bc.borrow_id = bw.id
				
				left join `{borrow_tender}` bt on bc.tender_id = bt.id 
				
				where bc.user_id = {$user_id} and bc.status = 0 and bt.is_auto = 1
				
				and  (bw.status = 3  or bw.status = 1)";
		$re = $mysql->db_fetch_array($sql);
		$result['auto_tener_total'] = $re['auto_tener_total'];
		return $result;
		
	}
	/*
	* 投资排行榜
	*/
	public static function GetTenderRanking($data=array()){
		global $mysql;
		$limit = isset($data['limit'])?$data['limit']:5;
		$sql = 'select sum(p1.account) as total,p2.username from {borrow_tender} as p1 left join {user} as p2 on p1.user_id=p2.user_id where p1.status=1 order by total desc limit '.$limit;
		$a = $mysql->db_fetch_arrays($sql);
		foreach($a as $k=>$v){
			$a[$k]['number'] = $k+1;
			$a[$k]['username_show'] = $v['username'];
			$a[$k]['total_account_format'] = number_format($v['total'],2);
		}
		return $a;
	}
	//首页实时数据统计
	public function GetDataStatistics(){
		global $mysql;
		//总成交额
		$sql = 'select sum(account_yes) as account_yes from {borrow} where status=3 and biao_type!="lz"';
		$a = $mysql->db_fetch_array($sql);
		
		$_sql = 'select sum(p1.account) as account from {borrow_tender} as p1 left join {borrow} as p2 on p1.borrow_id=p2.id where p2.biao_type="lz" and (p1.status=1 or p1.status=3)';
		$b = $mysql->db_fetch_array($_sql);
		
		$re = array();
		$re['total_account'] = number_format($a['account_yes']+$b['account'],2);

		//会员人数
		$b = $mysql->db_fetch_array('select count(1) n from {user}');
		$re['total_user'] = $b['n'];

		//成功的标数量
		$b = $mysql->db_fetch_array('select count(1) as total_borrow from {borrow} where (status=3 and biao_type!="lz") or (status=1 and biao_type="lz")');
		$re['total_borrow'] = $b['total_borrow'];

		return $re;
	}
/*
*	获取年化和月化
*/
	function InterestYearAndMonth($data){
		global $_G;
		$data['fee_rate'] = $_G['biao_type'][$data['biao_type']]['interest_fee_rate'];
		$data['rebid_rate'] = 0;
		$data['tender_rate'] = $data['award']==2?$data['funds']:0;
		$isday			= $data['isday']==1?true:false;
		$borrow_style	= $data['style'];
		$time_limit		= $isday==true?$data['time_limit_day']:$data['time_limit'];
		$year_interest	= $data['apr'] * (1 - $data['fee_rate']/100);
		$condition = array();
		if(!$isday){
			if($borrow_style == 0){		//month
				$year_interest = 24.00 * ($data['tender_rate'] + $data['rebid_rate'])/($time_limit + 1) + $year_interest;
				$condition['flyear_interest'] = number_format((pow((1 + $year_interest/1200), 12) - 1) * 100, 2, '.', '');
			}else if($borrow_style == 1){	//season
				$year_interest = ($year_interest * 3 + 24.00*($data['tender_rate'] + $data['rebid_rate'])/($time_limit/3+1))/3;
				$condition['flyear_interest'] = number_format((pow((1 + $year_interest/400), 4)-1) * 100, 2, '.', '');
			}else if($borrow_style == 2){		//all  == 2
				$year_interest = $year_interest + ($data['tender_rate'] + $data['rebid_rate'])*12/$time_limit;
				$condition['flyear_interest'] = number_format((pow((1+$year_interest/1200*$time_limit), 12/$time_limit)-1)*100, 2, '.', '');
			}else{	//end  == 3
				$year_interest = ($year_interest*$time_limit + 12 * ($data['tender_rate'] + $data['rebid_rate']))/$time_limit;
				$condition['flyear_interest'] = number_format((pow((1 + $year_interest/1200*$time_limit), 12/$time_limit)-1)*100, 2, '.', '');
			}
		}else{	//期限按天算
			$year_interest = $year_interest + ($data['tender_rate'] + $data['rebid_rate'])/$time_limit * 365;
			$condition['flyear_interest'] = number_format((pow((1+$year_interest/36500*$time_limit), 365/$time_limit)-1)*100, 2, '.', '');
		}

		$condition['flmonth_interest']	= number_format($condition['flyear_interest']/12, 2, '.', '');
		$condition['year_interest']		= number_format($year_interest, 2, '.', '');
		$condition['month_interest']	= number_format($year_interest/12, 2, '.', '');

		return $condition;
	}
	public static function tg_Repay($data = array()){
		global $mysql,$_G,$HX_config;
		$id = $data['id'];
		if ($id == "request"){
			$id = $_REQUEST['id'];
		}
		if($id<1) return self::ERROR;
		$_sql = "";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p2.user_id = '{$data['user_id']}'";
		}else{
			return self::ERROR;
		}
		/*
		$_t['tran_code'] = $HX_config['repaymentTranCode'];
		$_t['repayment_id'] = $id;
		$_t['err_code'] = '0000';
		$re = accountClass::getOrderResult($_t);
		if(!empty($re['list'])){
			return '你有未处理的订单，请联系网站管理员处理';
			exit();
		}*/
		$user_id = $data['user_id'];
		$current_time = time();
		$sql = "select p1.*,p2.name,p2.monthly_repayment as monthly_repayment,p2.is_mb as is_mb,p2.is_jin as is_jin,p2.is_fast as is_fast,p2.name as borrow_name,p2.repayment_account as all_repayment_account,p2.repayment_yesaccount as all_repayment_yesaccount,p2.user_id as borrow_userid,p2.repayment_yesinterest ,p2.time_limit,p2.isday,p2.time_limit_day,p2.forst_account,p2.account as borrow_account,p2.is_vouch,p2.success_time, p2.biao_type,p2.p_contract_no from {borrow_repayment} as p1,{borrow} as p2   where p1.status!=1 and p1.id=$id and p1.borrow_id=p2.id $_sql";
		$borrow_repayment_result = $mysql->db_fetch_array($sql);
		$borrow_id = $borrow_repayment_result["borrow_id"];
		$success_time = $borrow_repayment_result["success_time"];
		$borrow_userid = $borrow_repayment_result["borrow_userid"];
		if ($borrow_repayment_result==false){
			return self::ERROR;
		}
		if ($borrow_repayment_result['status']==1){
			return "此期已经还款，请不要乱操作";
		}

		//判断上一期是否已还
		if ($borrow_repayment_result['order']!=0){
			$_order = $borrow_repayment_result['order']-1;
			$sql = "select status from `{borrow_repayment}` where `order`=$_order and borrow_id={$borrow_repayment_result['borrow_id']}";
			$result = $mysql->db_fetch_array($sql);
			if ($result!=false && $result['status']!=1){
				return "你上期的借款还没还，请先还上期的";
			}
		}
		$biao_type = $borrow_repayment_result['biao_type'];
		$classname = $biao_type."biaoClass";
		$dynaBiaoClass = new $classname();
		$late_result = $dynaBiaoClass->getLateInterest($borrow_repayment_result);
		//判断可用余额是否够还款
		$sql = "select * from {account} where user_id = '{$borrow_userid}'";
		$account_result = $mysql->db_fetch_array($sql);
		if ($account_result['use_money']<$borrow_repayment_result['repayment_account']+$late_result['late_interest']){
			return self::BORROW_REPAYMENT_NOT_ENOUGH;
		}

		//撤回该标的债权转让
		require_once ROOT_PATH."modules/creditor/creditor.class.php";
		$creditor = new creditorClass;
		$_a = $creditor->transfer_cf(array('status'=>1,'borrow_id'=>$borrow_id));


		if (round(($borrow_repayment_result['all_repayment_yesaccount']+$borrow_repayment_result['repayment_account']),2) == round($borrow_repayment_result['all_repayment_account'],2)){
			$islast = 1;
		}else{
			$islast = 0;
		}

		/***设置托管信息***/
		$a = $mysql->db_fetch_array('select card_id,realname,phone,pIpsAcctNo,virCardNo from {user} where user_id='.$data['user_id'].' limit 1');
		$tg_repay = array(
		'pBidNo'=>$borrow_repayment_result['borrow_id'],
		'pBidname'=>$borrow_repayment_result['name'],
		'pContractNo'=>$borrow_repayment_result['p_contract_no'],
		'pRepaymentDate'=>date("Ymd",time()),
		'pMerBillNo'=>$borrow_repayment_result['trade_no'],
		'pFTrdAmt'=>$borrow_repayment_result['repayment_account'],
		'pFIpsAcctNo'=>$a['pIpsAcctNo'],
		'pFIdentNo'=>$a['card_id'],
		'pFRealName'=>$a['realname'],
		'pFPhone'=>$a['phone'],
		'pFTrdFee'=>0.00,
		'pMerchantBillNo'=>'',
		'isInFull'=>$islast,
		'repayment_id'=>$borrow_repayment_result['id'],
		'virCardNo'=>$a['virCardNo']
		);
		unset($a);
		/******************/
		

		//liukun add for bug 133 begin
		$_repay_time = $borrow_repayment_result['repayment_time'];

		$_order = $borrow_repayment_result['order'];
		//如果网站没有代还，则需要自己还款
		//如果网站没有代还，增加投资人收款记录
		$sendRemind = array();
		if ($borrow_repayment_result['status']!=2){
			$biaotype_info = $dynaBiaoClass->get_biaotype_info();
			$sql = "select p1.*,p3.vip_status from `{borrow_collection}` as p1 left join `{user_cache}` as p3 on p1.user_id=p3.user_id where p1.`order` = '{$_order}' and p1.borrow_id='{$borrow_repayment_result['borrow_id']}' and p1.status=0";
			$result = $mysql->db_fetch_arrays($sql);
			foreach ($result as $key => $value){
				if($value['is_sell']==1){//债权已被出售
					continue;
				}
				//根据user_id找到是否是会员，决定利息管理费
				if ($value['vip_status']==1){
					$interest_fee = round($value['interest'] * $biaotype_info['interest_fee_rate_vip'], 2);
				}else{
					$interest_fee = round($value['interest'] * $biaotype_info['interest_fee_rate'], 2);
				}
				$pTTrdAmt = $value['repay_account'];
				//TODO
				$late_customer_result = $dynaBiaoClass->getLateCustomerInterest($value);
				$late_customer_interest = $late_customer_result['late_customer_interest'];
				$late_days = $late_customer_result['late_days'];
				$_t['late_fee'] = 0;
				if($late_customer_interest > 0){

					//$pTTrdAmt += $late_customer_interest;//逾期利息
					$_t['late_fee'] = $late_customer_interest;
					$late_result['late_interest'] -= $late_customer_interest;
				}
				
				/***设置托管信息***/
				$a = $mysql->db_fetch_array('select card_id,realname,phone,pIpsAcctNo,virCardNo from {user} where user_id='.$value['user_id'].' limit 1');
				$_t['pTAcctType'] = 1;
				$_t['pTIpsAcctNo'] = $a['pIpsAcctNo'];
				$_t['pTPhone'] = $a['phone'];
				$_t['pTTrdFee'] = $interest_fee;
				$_t['pTTrdAmt'] = $pTTrdAmt;
				$_t['virCardNo'] = $a['virCardNo'];

				
				$tg_repay['pDetails']['pRow'][] = $_t;
				unset($_t);
				/******************/
			}
		}
		$tg_repay['late_interest']=0;
		if ($late_result['late_days']>0){

			
		/***设置托管信息***/
		//$tg_repay['pFTrdFee'] += $late_result['late_interest'];
		$tg_repay['late_interest'] = $late_result['late_interest'];
		/******************/
		
		}
		if(isset($data['is_return']) && $data['is_return']==true){
			return $tg_repay;
		}
		return tg_repayment($tg_repay);
		exit();
	}
	//获取没有扣除利息管理费的
	public static function tg_getnolx($data = array()){
		global $mysql;
		if(!isset($data['id'])){
			return false;
		}
		$borrow_repayment_result = $mysql->db_fetch_array("select p1.`order`,p1.`borrow_id`,p2.`biao_type` from {borrow_repayment} as p1 left join {borrow} as p2 on p1.borrow_id=p2.id where p1.id=".$data['id']);
		$biao_type = $borrow_repayment_result['biao_type'];
		$classname = $biao_type."biaoClass";
		$dynaBiaoClass = new $classname();

		$_sql = '';
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql = " and p2.user_id = '{$data['user_id']}'";
		}
		$sql = "select p1.*,p2.monthly_repayment as monthly_repayment,p2.is_mb as is_mb,p2.is_jin as is_jin,p2.is_fast as is_fast,p2.name as borrow_name,p2.repayment_account as all_repayment_account,p2.repayment_yesaccount as all_repayment_yesaccount,p2.user_id as borrow_userid,p2.repayment_yesinterest ,p2.time_limit,p2.isday,p2.time_limit_day,p2.forst_account,p2.account as borrow_account,p2.is_vouch,p2.success_time, p2.biao_type,p2.p_contract_no from {borrow_repayment} as p1,{borrow} as p2   where (p1.status=0 or p1.status=2) and p1.id={$data['id']} and p1.borrow_id=p2.id $_sql";
		$xx = $mysql->db_fetch_array($sql);
		$late_result = $dynaBiaoClass->getLateInterest($xx);

		$biaotype_info = $dynaBiaoClass->get_biaotype_info();
		$_order = $borrow_repayment_result['order'];
		$sql = "select p1.*,p3.vip_status from `{borrow_collection}` as p1 left join `{user_cache}` as p3 on p1.user_id=p3.user_id where p1.`order` = '{$_order}' and p1.borrow_id='{$borrow_repayment_result['borrow_id']}' and p1.interest_fee_status=0";
		$result = $mysql->db_fetch_arrays($sql);
		$re = array();
		foreach ($result as $key => $value){
			if($value['is_sell']==1){//债权已被出售
				continue;
			}
			//根据user_id找到是否是会员，决定利息管理费
			if ($value['vip_status']==1){
				$interest_fee = round($value['interest'] * $biaotype_info['interest_fee_rate_vip'], 2);
			}else{
				$interest_fee = round($value['interest'] * $biaotype_info['interest_fee_rate'], 2);
			}
			//TODO
			$late_customer_result = $dynaBiaoClass->getLateCustomerInterest($value);
			$late_customer_interest = $late_customer_result['late_customer_interest'];
			$late_days = $late_customer_result['late_days'];

			if($late_customer_interest > 0){

				//$pTTrdAmt += $late_customer_interest;//逾期利息
				$_t['late_fee'] = $late_customer_interest;

			}
			/***设置托管信息***/
			$a = $mysql->db_fetch_array('select card_id,realname,phone,pIpsAcctNo,virCardNo from {user} where user_id='.$value['user_id'].' limit 1');

			$_t['pTIpsAcctNo'] = $a['pIpsAcctNo'];
			$_t['pTPhone'] = $a['phone'];
			$_t['pTTrdFee'] = $interest_fee;
			$_t['virCardNo'] = $a['virCardNo'];
			$_t['collection_id'] = $value['id'];
			$_t['merOrderNum'] = $value['id'].'_'.$value['user_id'].'_'.$key.rand(001,999);
			$_t['borrow_id'] = $value['borrow_id'];
			$_t['user_id'] = $value['user_id'];
			$re[] = $_t;
			unset($_t);
		}
		$re['late_result'] = $late_result;
		return $re;
	}
	//获取托管还款信息
	public static function tgReturnView($data=array()){
		global $mysql;
		$repayment_id = (int)$data['repayment_id'];
		if($repayment_id<1) return false;
		$a = $mysql->db_fetch_array('select `order`,`tg_return`,`borrow_id` from {borrow_repayment} where id='.$repayment_id);
		if(empty($a)){
			return false;
		}
		$a['tg_return'] = unserialize($a['tg_return']);
		$b = $mysql->db_fetch_arrays('select p2.user_id,p2.username,p1.* from {borrow_collection} as p1 left join {user} as p2 on p1.user_id=p2.user_id where p1.borrow_id='.$a['borrow_id'].' and p1.`order`='.$a['order']);
		$a['interest_fee'] = $b;
		return $a;
	}
}
?>