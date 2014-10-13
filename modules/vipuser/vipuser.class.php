<?php
/**
 * Description of vipuser
 *
 */
class VipUser {

    public static $msg = '';

    const USER_NOT_EXISTS = '会员不存在';
    const USER_NOT_RESUME = '会员没有简历';
    const USER_IS_VIP     = '已经是VIP会员';
    const CARD_NOT_EXISTS = 'VIP卡不存在';
    const CARD_IS_USED = 'VIP卡已被使用';
    const CARD_ERROR_PASSWORD = 'VIP卡密码错误';
	const INVALID_CARD_TYPE = 'VIP类型无效';

    # 未激活
    const CARD_STATUS_NO_ACTIVE = 'CARD_STATUS_NO_ACTIVE';
    # 正常
    const CARD_STATUS_ACTIVE  = 'CARD_STATUS_ACTIVE';
    # 冻结
    const CARD_STATUS_FREEZE  = 'CARD_STATUS_FREEZE';
    # 停卡
    const CARD_STATUS_STOP    = 'CARD_STATUS_STOP';
    # 封号
    const CARD_STATUS_INVALID = 'CARD_STATUS_INVALID';
    #　过期
    const CARD_STATUS_EXPIRED = 'CARD_STATUS_EXPIRED';

	const FREEZE_NO_ACTIVE_STATUS = '非激活状态无法冻结';
	const FREEZE_MORE_TIMES = '冻结超过2次';
	const FREEZE_INVALID_TIME = '冻结时间无效';
	
	/**
	 * 获取符合VIP会员的岗位
	 * @param $user_id
	 * @param $page 页码
	 * @param $page_size 每页记录数
	 */
	public static function GetJobList ($user_id, $page = 1, $page_size = 20) {
		global $mysql;

		$list = array();
		$total_record = 0;
		$total_page = 0;
		
		$resume = $mysql->db_fetch_array("select work_time from {school_resume} where user_id={$user_id}");
		$work_time = explode(',', $resume['work_time']);
		$where_work_time = 'j.work_time';
		foreach ($work_time as $t) {
			$where_work_time = "REPLACE(" . $where_work_time .",'{$t}','')";
		}
		if (empty ($work_time)) {
			$where_work_time = '';
		}
		else {
			$where_work_time = "REPLACE(" . $where_work_time .", ',', '')";
		}
		if ($where_work_time) {
			$where_work_time = " and {$where_work_time}=''";
		}
		
		$sql = "select SELECT
					from {jianzhi} j
					left join {vip_user} vu on 1=1
					left join {vip_card} vc on vu.card_number=vc.serial_number
					left join {user} u on vu.user_id=u.user_id
					left join {school_resume} rm on vu.user_id=rm.user_id
				where j.status=1 and vu.user_id={$user_id} and
					vc.status=1 and vc.end_date>UNIX_TIMESTAMP(now())-24*3600 and
							(j.apply_man > 0 or (u.sex=1 and j.apply_male>0) or (u.sex=2 and j.apply_female>0)) and
							(j.apply_male_num+j.apply_female_num<=j.apply_man+j.apply_male+j.apply_female) and
							((u.sex=1 and rm.height>=ifnull(male_height,0)) or (u.sex=2 and rm.height>=ifnull(female_height,0))) and
							rm.health_certificate>=j.is_health_certificate and rm.bike>=j.is_bike {$where_work_time} ORDER LIMIT";
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as cnt', '', ''), $sql));

		$total_record = $row['cnt'];
		$total_page = ceil($total_record / $page_size);
		$page = max(1, min($page, $total_page));
		$index = ($page - 1) * $page_size;

		if ($total_record > 0) {
			$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('j.*', ' order by j.id desc', " limit {$index},{$page_size}"), $sql));
		}

		return array(
            'list' => $list,
            'record_num' => $total_record,
            'page' => $page,
            'page_size' => $page_size,
            'total_page' => $total_page
        );
	}


	/**
     * vip卡类型列表
     */
    public static function ListCardType () {
        global $mysql;
        
        $rows = $mysql->db_fetch_arrays('select * from {vip_card_type}');
        return $rows;
    }

    /**
     * 新增vip卡类型
     */
    public static function AddCardType ($name, $month_num) {
        global $mysql;

        self::$msg = '';
        if (strlen($name) <= 0) {
            self::$msg .= "请填写类型\\n";
        }
        else {
            if($mysql->db_count('vip_card_type', " name='{$name}'") > 0) {
                self::$msg .= "类型不能重复\\n";
            }
        }
        if ($month_num <= 0 || $month_num > 60) {
            self::$msg .= "月数无效\\n";
        }
        if (self::$msg) {
            return false;
        }
        else{
            
            $data['name'] = $name;
            $data['month_num'] = $month_num;
            $mysql->db_add('vip_card_type', $data);
        }

        return true;
    }
    
	/**
     * 新增vip卡类型
     */
    public static function UpdateCardType ($name, $month_num,$id) {
        global $mysql;

        self::$msg = '';
        if (strlen($name) <= 0) {
            self::$msg .= "请填写类型\\n";
        }
        else {
            if($mysql->db_count('vip_card_type', " name='{$name}' and id !='{$id}'") > 0) {
                self::$msg .= "类型不能重复\\n";
            }
        }
        if ($month_num <= 0 || $month_num > 60) {
            self::$msg .= "月数无效\\n";
        }
        if (self::$msg) {
            return false;
        }
        else{
            
            $data['name'] = $name;
            $data['month_num'] = $month_num;
            $mysql->db_update('vip_card_type', $data," id=$id");
        }

        return true;
    }
	
    /**
     * 生成VIP卡
     * @param $number 生成数量
     * @param $remark 备注
     */
    public static function AddCard ($index) {
        global $mysql;

        $data['number']    = $index['number'];
        $year = $index['year'];
		$semester = $index['semester'];
		$city = $index['city'];
		$pwd_word_num = $index['pwd_word_num'];
		$sql = "select name from {area} where id=".$city;
		$result = $mysql->db_fetch_array($sql);
		$city_name = $result['name'];
        if(!self::CheckParam('AddCard', $data)) {
            return false;
        }
        
        $now = $batch = time();
        $ip = ip_address();
        
        $s = self::GetCardWord($city_name) . substr($year, strlen($year) - 2, 2) . $semester;
	
		$rows = $mysql->db_fetch_array("select serial_number from {vip_card} where serial_number like '{$s}%' order by serial_number desc limit 1");
		$s_index = (int)substr($rows['serial_number'], strlen($rows['serial_number']) - 5, 5);
        $sql = array();
        for ($index = 1; $index <= $data['number']; $index++) {

            $pwd = self::CreatePassword($pwd_word_num);
            $serial_number = $s . str_pad(strval($s_index + $index), 5, '0', STR_PAD_LEFT) ;
            array_push($sql, "('{$serial_number}', '{$batch}', '{$pwd}', '{$city_name}', '{$_SESSION['username']}','{$now}', 1, '{$now}', '{$ip}')");
        }
        $mysql->db_query('insert into {vip_card}' .
                '(serial_number, batch, password, city, create_user, create_time, flag, addtime, addip)' .
                ' values ' . implode(',', $sql) . ';');

        return true;
    }

    /**
     * VIP卡列表
     */
    public static function ListCard ($status = '', $serial_number = 0, $page = 1, $page_size = 10) {
        global $mysql;
        
        $whr = array();
        if ($serial_number) {
            array_push($whr, "vc.serial_number like '%{$serial_number}%'");
        }
        if ('' !== $status) {
			if (1 == $status) {
				array_push($whr, "vc.status={$status} and vc.is_up_end_date=0");
			}
			elseif (5 == $status) {
				array_push($whr, "vc.status=1 and vc.is_up_end_date>0");
			}
			elseif (6 == $status) {
				array_push($whr, "vc.end_date<now()");
			}
			else {
				array_push($whr, "vc.status={$status}");
			}
        }
		$whr = empty ($whr)?'':' where ' . implode(' and ', $whr);
		$row = $mysql->db_fetch_array("select count(1) cnt from {vip_card} vc $whr");
        $count = $row['cnt'];
        $total_page = ceil($count / $page_size);

        $page = max(1, min($page, $total_page));

        $sql = "select vc.id,vc.status,vc.serial_number,vc.password,vc.city,
					u.realname,vc.open_time,vc.end_date,vc.vct_name,vc.is_up_end_date,
					vc.create_user,rm.id as re_id
					from {vip_card} vc
					left join {vip_user} vu on vc.serial_number = vu.card_number
					left join {user} u on vu.user_id = u.user_id
					left join {school_resume} rm on u.user_id=rm.user_id
		{$whr} order by id desc";

        $rows = $mysql->db_list_res($sql, $page, $page_size);
        $rows = $rows?$rows:array();

        foreach($rows as $key => $row) {
			 
            $rows[$key] = self::GetCardStatus($row);
        }

        return array(
            'list' => $rows,
            'record_num' => $count,
            'page' => $page,
            'page_size' => $page_size,
            'total_page' => $total_page
        );
    }

    /**
     * 更新VIP卡
     */
    public static function UpdateCard ($id, $password = '', $remark = '') {
        global $mysql;

        $row = self::GetCardInfoById($id);
        if (!$row) {
            self::$msg = '记录不存在';
            return false;
        }
        $up_filed = array();
        if ($password && $row['password'] != $password) {
            array_push($up_filed, "password='{$password}'");
        }
        if ($row['remark'] != $remark) {
            array_push($up_filed, "remark='{$remark}'");
        }
        if ($up_filed) {
            $mysql->db_query('update {vip_card} set ' . implode(',', $up_filed).
                    " where id={$id}");
        }
        
        return true;
    }

    /**
     * 获取VIP卡内容
     * @param $id VIP卡ID
     */
    public static function GetCardInfoById ($id) {
        global $mysql;
        
        $row = $mysql->db_fetch_array('select * from {vip_card} where id = ' . $id);
        $row = self::GetCardStatus($row);
        $row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
        $row['start_date']  = $row['start_date']?date('Y-m-d', $row['start_date']):'';
        $row['end_date']    = $row['end_date']?date('Y-m-d', $row['end_date']):'';
        $row['open_time']   = $row['open_time']?date('Y-m-d H:i:s',  $row['open_time']):'';
        $row['freeze_time'] = $row['freeze_time']?date('Y-m-d H:i:s', $row['freeze_time']):'';
        $row['stop_time']   = $row['stop_time']?date('Y-m-d H:i:s', $row['stop_time']):'';

        return $row;
    }

    /**
     * 获取VIP卡内容
     * @param $id VIP卡ID
     */
    public static function GetUserCardInfoById ($id) {
        global $mysql;

        $row = $mysql->db_fetch_array(
           'select vc.*,u.username from {vip_card} vc
               left join {vip_user} vu on vc.serial_number = vu.card_number
               left join {user} u on vu.user_id=u.user_id
               where vc.id = ' . $id
        );

        $row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
        $row['start_date']  = $row['start_date']?date('Y-m-d', $row['start_date']):'';
        $row['end_date']    = $row['end_date']?date('Y-m-d', $row['end_date']):'';
        $row['open_time']   = $row['open_time']?date('Y-m-d H:i:s',  $row['open_time']):'';
        $row['freeze_time'] = $row['freeze_time']?date('Y-m-d H:i:s', $row['freeze_time']):'';
        $row['stop_time']   = $row['stop_time']?date('Y-m-d H:i:s', $row['stop_time']):'';

		$row = self::GetCardStatus($row);

        return $row;
    }

    /**
     * 获取VIP用户信息
     * @param $user_id 用户ID
     */
    public static function GetVIPUserInfo ($user_id) {
        global $mysql;

        if (!$user_id) {
            return array();
        }
        $now = strtotime(date('Y-m-d'));
        $row = $mysql->db_fetch_array(
           "select vc.status as vip_status, vc.start_date vip_start_date, vc.end_date as vip_end_date from {vip_user} vu
               left join {vip_card} vc on vu.card_number=vc.serial_number
               where vu.user_id={$user_id}"
        );
        if ($row) {
            if (0 == $row['vip_status']) {
                $row['status'] = self::CARD_STATUS_NO_ACTIVE;
            }
            elseif (1 == $row['vip_status']) {
                $row['status'] = self::CARD_STATUS_ACTIVE;
            }
            elseif (2 == $row['vip_status']) {
                $row['status'] = self::CARD_STATUS_FREEZE;
            }
            elseif (3 == $row['vip_status']) {
                $row['status'] = self::CARD_STATUS_STOP;
            }
            elseif (4 == $row['vip_status']) {
                $row['status'] = self::CARD_STATUS_INVALID;
            }
            
            if ($row['vip_end_date'] < $now) {
                $row['vip_status'] = self::CARD_STATUS_EXPIRED;
            }
            //$_SESSION['vip_status'] = $row['vip_status'];
        }
        
        return $row?$row:array();
    }

    /**
     * 删除VIP卡
     */
    public static function DeleteCard ($ids) {
       global $mysql;

	   if (is_array($ids) && !empty ($ids)) {
		   $ids = implode("','", $ids);
		   $mysql->db_query("delete from {vip_user} where card_number in ('{$ids}')");
		   $mysql->db_query("delete from {vip_card} where serial_number in ('{$ids}')");
	   }
	   
       return true;
    }

    
    /**
     * 冻结VIP卡
     * @param $id VIP卡ID
     * @param $month 冻结月数
     * @param $remark 冻结原因
     */
    public static function FreezeCard ($id, $month, $remark = '') {
        global $mysql;

        $row = $mysql->db_fetch_array("select * from {vip_card} where id={$id}");
        # 非正常状态无法冻结
        if (1 != $row['status']) {
            return self::FREEZE_NO_ACTIVE_STATUS;
        }
        if (2 <= $row['freeze_times'] && 1 != $_SESSION['usertype']) {
            return self::FREEZE_MORE_TIMES;
        }
        if ($month <= 0) {
            return self::FREEZE_INVALID_TIME;
        }
        $now = time();
        $now_format = date('Y-m-d H:i:s', $now);
        $ip = ip_address();
        $remark = "{$row['remark']}<br>{$now_format}->{$ip}->{$_SESSION['username']}->冻结->{$month}->{$remark}";
        # 冻结
		$day = (strtotime("+{$month} month", $now) - $now) / 3600 / 24;
        $freeze_second = 24 * 3600 * $day;

        $data = array(
            'status' => 2,
            'freeze_day' => $day,
            'freeze_time' => $now,
            'freeze_times' => (int)$row['freeze_times'] + 1,
            'end_date' => $row['end_date'] + $freeze_second,
            'remark' => $remark,
            'updatetime' => $now,
            'updateip' => $ip
        );
        $mysql->db_update('vip_card', $data, "id={$id}");

        return true;
    }

    /**
     * 停用VIP卡
     * @param $id VIP卡ID
     * @param $month 停用月数
     * @param $remark 停用原因
     */
    public static function StopCard ($id, $month, $remark = '') {
        global $mysql;

        $row = $mysql->db_fetch_array("select * from {vip_card} where id={$id}");
        # 非正常状态无法停卡
        if (1 != $row['status']) {
            self::$msg = '非激活状态VIP无法停卡';
            return false;
        }
        
        if ($month <= 0) {
            self::$msg = '停卡天数无效';
            return false;
        }
        
        $now = time();
        $now_format = date('Y-m-d H:i:s', $now);
        $ip = ip_address();

		$day = (strtotime("+{$month} month", $now) - $now) / 3600 / 24;
        $remark = "{$row['remark']}<br>{$now_format}->{$ip}->{$_SESSION['username']}->停卡->{$month}->{$remark}";

        $data = array(
            'status' => 3,
            'stop_time' => $now,
            'stop_day' => $day,
            'remark' => $remark,
            'updatetime' => $now,
            'updateip' => $ip
        );
        $mysql->db_update('vip_card', $data, "id={$id}");

        return true;
    }

    /**
     * 封号
     * @param $id VIP卡ID
     * @param $remark 封号原因
     */
    public static function InvalidCard ($id, $remark) {
        global $mysql;

        $row = $mysql->db_fetch_array("select * from {vip_card} where id={$id}");

        $now = time();
        $now_format = date('Y-m-d H:i:s', $now);
        $ip = ip_address();
        $remark = "{$row['remark']}<br>{$now_format}->{$ip}->{$_SESSION['username']}->禁用->{$month}->{$remark}";
        
        $data = array(
            'status' => 4,
            'remark' => $remark,
            'updatetime' => $now,
            'updateip' => $ip
        );

        $mysql->db_update('vip_card', $data, "id={$id}");
        
        return true;
    }

    /**
     * 激活VIP卡(重新激活)
     * @param $id VIP卡ID
     * @param $remark 激活原因
     */
    public static function ActiveCard ($id, $remark = '') {
        global $mysql;

        $row = $mysql->db_fetch_array("select * from {vip_card} where id={$id}");
        $now = time();
        $now_day = strtotime(date('Y-m-d', $now));
        $now_format = date('Y-m-d H:i:s', $now);
        $ip = ip_address();

        $remark = "{$row['remark']}<br>{$now_format}->{$ip}->{$_SESSION['username']}->激活->->{$remark}";
        $data = array(
            'updatetime' => $now,
            'updateip' => ip_address()
        );
        if (1 == $row['status']) {
            return true;
        }
        elseif (2 == $row['status']) {
            $release_freeze_date = strtotime(date('Y-m-d', strtotime("+{$row['freeze_day']} days", $row['freeze_time'])));
            $early_day = max(0, $release_freeze_date - $now_day);
            $data['freeze_day']  = 0;
            $data['freeze_time'] = 0;
            $data['end_date']    = $row['end_date'] - $early_day;
        }
        elseif (3 == $row['status']) {
            $data['stop_time'] = 0;
            $data['stop_day']  = 0;
        }

        $data['status'] = 1;
        $data['remark'] = $remark;
        
        $mysql->db_update('vip_card', $data, "id={$id}");

        return true;
    }

    /**
     * 调整过期日期
     * @param $id VIP卡ID
     * @param $month 过期日期
     * @param $remark 备注
     */
    public static function UpdateExpireDate ($id, $month, $remark = '') {
        global $mysql;

        $row = $mysql->db_fetch_array("select * from {vip_card} where id={$id}");
        if (!$row) {
            self::$msg = '记录不存在';
            return false;
        }
        if (0 == $row) {
            self::$msg = '未激活VIP卡不能调整有效期';
            return false;
        }
        
        $now = time();
        $now_format = date('Y-m-d H:i:s', $now);
        $ip = ip_address();

        $remark = "{$row['remark']}<br>{$now_format}->{$ip}->{$_SESSION['username']}->延期->{$month}->{$remark}";
        $data = array(
            'end_date' => strtotime("+{$month} month", $row['end_date']),
			'is_up_end_date' => $row['is_up_end_date'] + 1,
            'updatetime' => $now,
            'updateip' => ip_address(),
            'remark' => $remark
        );

        $mysql->db_update('vip_card', $data, "id={$id}");

        return true;
    }

    /**
     * 获取VIP用户列表
     * @param $user_name 用户名
     * @param $card_number VIP卡号
     */
    public static function GetVipuserList ($data = array()) {
        global $mysql;
		$realname = isset($data['realname'])?$data['realname']:"";
		$serial_number = isset($data['serial_number'])?$data['serial_number']:"";
		
		$page = isset($data['page'])?$data['page']:1;
		$epage = isset($data['epage'])?$data['epage']:10;
		
		$_sql = " where 1=1 ";
        $whr = array();
        if (!empty($serial_number)) {
            $_sql .= " and vc.serial_number like '%{$serial_number}%'";
        }
		
         if (!empty($realname)) {
            $_sql .= " and u.realname like '%{$realname}%'";
        }

        $sql = "select SELECT from {vip_user} vu
                    left join {vip_card} vc on vu.card_number=vc.serial_number
                    left join {user} u on vu.user_id = u.user_id
                    left join {school_resume} re on u.user_id=re.user_id
					left join {jianzhi_luqu} lq on vu.user_id=lq.user_id and lq.status=1
                    {$_sql} order by vu.id desc";
        $row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('vc.*, u.realname,re.id as re_id, re.phone_number,u.sex,u.user_id,
					re.height,re.school,re.school_area,re.professional,re.grade,
					re.health_certificate,re.bike,lq.status as lq_status ', 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();

        foreach($list as $key => $row) {
            $list[$key] = self::GetCardStatus($row);
			
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
     * 添加VIP会员
     * @param $user_name 会员号
	 * @param $vct_id VIP卡类型
     * @param $card_number 卡号
     * @param $password VIP卡密码
     */
    public static function AddVipUser ($user_name, $vct_id, $card_number, $password = NULL) {
        global $mysql;

        # 获取会员信息
        $user = $mysql->db_fetch_array("select user_id from {user} where username='{$user_name}'");
        if (!$user) {
            return self::USER_NOT_EXISTS;
        }
        # 判断会员简历
        if (!$mysql->db_fetch_array("select 1 from {school_resume} where user_id={$user['user_id']}")) {
            return self::USER_NOT_RESUME;
        }
        # 判断会员是否是VIP
        if($mysql->db_fetch_array("select 1 from {vip_user} where user_id={$user['user_id']}")) {
            return self::USER_IS_VIP;
        }
        # 判断VIP卡号是否存在
        $card = $mysql->db_fetch_array("select password,status from {vip_card} where serial_number='{$card_number}'");
        if(!$card) {
            return self::CARD_NOT_EXISTS;
        }
        if ($password != $card['password'] && NULL != $password) {
            return self::CARD_ERROR_PASSWORD;
        }
        if (0 != $card['status']) {
            return false;
        }
        # 判断VIP卡号是否已被使用
        if($mysql->db_fetch_array("select 1 from {vip_user} where card_number='{$card_number}'")) {
            return self::CARD_IS_USED;
        }
		# 获取VIP类型
		$vct = $mysql->db_fetch_array("select * from {vip_card_type} where id={$vct_id}");
		if (!$vct) {
			return self::INVALID_CARD_TYPE;
		}
        
        $data['user_id']     = $user['user_id'];
        $data['card_number'] = $card_number;
        if (!$mysql->db_add('vip_user', $data)) {
            return false;
        }
		
        $sql = "update {user} set vip_status=1 where user_id={$user['user_id']}";
		$mysql -> db_query($sql);
        # 激活VIP卡
        return self::ActiveVIPCard($vct_id, $card_number);

    }

	/**
	 * 获取操作记录
	 */
	public static function GetHistory ($id, $page, $page_size = 20) {
		global $mysql;

		$rows = array();
		$count = 0;
		$total_page = 0;
		$vc = $mysql->db_fetch_array("select serial_number, remark from {vip_card} where id={$id}");
		if ($vc) {
			
			$remarks = explode('<br>', $vc['remark']);
			unset($remarks[0]);
			$count = count($remarks);
			$total_page = ceil($count / $page_size);
			$page = max(1, min($page, $total_page));
			
			foreach ($remarks as $remark) {
				if (!$remark) {
					continue;
				}
				$arr_remark = explode('->', $remark);
				$row = array(
					'serial_number' => $vc['serial_number'],
					'op' => $arr_remark[3],
					'expire' => $arr_remark[4],
					'remark' => $arr_remark[5],
					'username' => $arr_remark[2],
					'optime' => $arr_remark[0],
					'optip' => $arr_remark[1],
				);
				array_push($rows, $row);
			}

			$rows = array_slice($rows, $page_size * ($page - 1), $page_size);
		}

		return array(
            'list' => $rows,
            'record_num' => $count,
            'page' => $page,
            'page_size' => $page_size,
            'total_page' => $total_page
        );
	}

	/**
	 * 获取VIP卡类型
	 */
	public static function CardType () {
		global $mysql;

		$rows = $mysql->db_fetch_arrays('select id,name,month_num from {vip_card_type}');
		return $rows;
	}
	
	/**
	 * 获取VIP卡类型
	 */
	public static function ViewCardType ($id) {
		global $mysql;
		if (empty($id)) return ;
		$rows = $mysql->db_fetch_array('select id,name,month_num from {vip_card_type} where id ='.$id);
		return $rows;
	}
	
	/**
     * 解除VIP卡冻结
     */
    private static function ReleaseFreezeCard ($id) {
        global $mysql;

        $row = $mysql->db_fetch_array("select * from {vip_card} where id={$id}");
        if (2 != $row['status']) {
            return true;
        }
        $mysql->db_query('update {vip_card} set status=1,stop_day=0,stop_times=null where id = ' . $id);
        return true;
    }

    /**
     * 解除VIP卡停卡
     */
    private static function ReleaseStopCard ($id) {
        global $mysql;

        $status = 1;
        $row = $mysql->db_fetch_array("select * from {vip_card} where id={$id}");
        # 冻结
        $freeze_set = '';
        if ($row['freeze_day'] > 0) {
            $end_freeze_date = strtotime(date('Y-m-d', $row['freeze_time'])) + $row['freeze_day']*24*3600;
            if ($end_freeze_date > strtotime(date('Y-m-d'))) {
                $status = 2;
            }
            else{
                $freeze_set .= ',freeze_day=0, freeze_times=null';
            }
        }
        $mysql->db_query("update {vip_card} set status={$status},stop_day=0,
                            stop_times=null {$freeze_set} where id = {$id}");
        
        return true;
    }

    /**
     * 生成密码
	 * @param $pwd_word_num 密码长度
     */
    private static function CreatePassword ($pwd_word_num) {

        $pwd = '';

        $str = '12345abcdefgABCDEFGhijklmnHIJKLMNopqrstuvwsyzOPQRSTUVWSYZ67890';
        for ($i = 0; $i < $pwd_word_num; $i++) {
            $pwd .= substr($str, rand(0, strlen($str) - 1), 1);
        }

        return $pwd;
    }

    private static function CheckParam ($op, $param) {

        self::$msg = '';
        if ('AddCard' == $op) {
            if (0 >= $param['number']) {
                self::$msg .= "请填写生成数量\\n";
            }
            if ($param['number'] > 100) {
                self::$msg .= "生成数量太多\\n";
            }
        }
        if (self::$msg) {
            return false;
        }
        
        return true;
    }

    /**
     * 激活VIP卡(第一次激活)
	 * @param $vct_id VIP类型
     * @param $card_number
     * @return bool
     */
    private static function ActiveVIPCard ($vct_id, $card_number) {
        global $mysql;

        $now = time();
        $now_date = strtotime(date('Y-m-d', $now));
        $now_format = date('Y-m-d H:i:s', $now);
        $ip = ip_address();
        
        $card = $mysql->db_fetch_array("select * from {vip_card} where serial_number='{$card_number}'");
        if (!$card) {
            return false;
        }
        if (0 != $card['status']) {
            return false;
        }
		$vct = $mysql->db_fetch_array("select * from {vip_card_type} where id={$vct_id}");
		if (!$vct) {
			return false;
		}
		$end_date = strtotime("+{$vct['month_num']} month", $now_date);
        $remark = "{$card['remark']}<br>{$now_format}->{$ip}->{$_SESSION['username']}->激活->{$vct['month_num']}->";
        $data = array(
			'vct_name' => $vct['name'],
			'month_num' => $vct['month_num'],
            'status' => 1,
            'start_date' => $now_date,
            'end_date' => $end_date,
            'open_time' => $now,
            'freeze_time' => 0,
            'freeze_day' => 0,
            'freeze_times' => 0,
            'stop_time' => 0,
            'stop_day' => 0,
            'stop_times' => 0,
            'remark' => $remark
        );
        if (!$mysql->db_update('vip_card', $data, "serial_number='{$card_number}'")) {
            return false;
        }
        return true;
    }

	/**
	 * 充值卡装备
	 * @param $data 充值卡信息
	 */
	public static function GetCardStatus ($data) {

		$data['status'] = (int)$data['status'];
		$all_status = array(
			'0' => '未激活',
			'1' => '激活',
			'2' => '冻结',
			'3' => '停卡',
			'4' => '禁用',
			'5' => '延期',
			'6' => '过期'
		);
		if (1 == $data['status'] && $data['is_up_end_date'] > 0) {
			$data['status'] = 5;
		}
		if ($data['end_date'] < strtotime(date('Y-m-d'))) {
			$data['status'] = 6;
		}
		$data['status_name'] = $all_status[$data['status']];

		return $data;
	}

	/**
	 * 获取VIP卡号开头字母
	 * @param $city 城市
	 */
	public static function GetCardWord (&$city = '') {
		global $mysql,$user;

		if (!$city) {
			$city = $user->getUserCity($city);
		}
		return getCnFirstChar($city);
	}
}
?>
