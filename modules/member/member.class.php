<?php


/**
 * 会员类
 *
 * @author TissotCai
 */

class Member {

    const LOGIN_ERROR_USER_PWD = '用户名或密码错误';
    const LOGIN_ERROR_UCENTER = 'ucenter同步登录失败';
	const LOGIN_STEP_EMAIL = '邮箱激活步骤';
	const LOGIN_STEP_INFO  = '信息完善步骤';
	const LOGIN_STEP_AVATAR = '头像上传步骤';
    
    const ADDMEMBER_ERROR_REAL_NAME = '姓名无效';
	const ADDMEMBER_ERROR_USER_EXISTS = '会员号重复';
	const ADDMEMBER_ERROR_MAIL_EXISTS = '邮箱重复';

    const PERFECTUSER_ERROR_UPDATE_USER = '用户信息更新失败';
    const PERFECTUSER_ERROR_UPDATE_RESUME = '简历信息更新失败';

	const UPDATEMEMBER_MAIL_EXISTS = '邮箱重复';
	const UPDATEMEMBER_ERROR_USER = '会员信息更新失败';
	const UPDATEMEMBER_ERROR_REAL_NAME = '姓名无效';
	
	const ADDFAVORITES_MODULE_NULL = '所属模块不能为空';
	const ADDFAVORITES_NAME_NULL = '标题不能为空';
	const ADDFAVORITES_URL_NULL = '链接地址不能为空';
	const ADDFAVORITES_NOT_EXISTS_USER = '用户不存在';
	const ADDFAVORITES_NOT_EXISTS_MODULE = '模块不存在';
	
    const ACTIVE_KEY = 1281226088;
    
    /**
     * 用户注册
	 * @param $param array
	 *		array(
	 *			'username'=>'会员号',
	 *			'password'=>'密码',
	 *			'email'=>'邮箱',
	 *			'gender'=>'性别'
	 *		)
	 * @return String
	 *		Member::ADDMEMBER_ERROR_USER_EXISTS 会员号重复<br>
	 *		Member::ADDMEMBER_ERROR_MAIL_EXISTS 邮箱重复<br>
	 *		Member::ADDMEMBER_ERROR_REAL_NAME 姓名无效<br>
	 *		true 成功
     */
    public static function AddMember ($param) {
        global $_G, $mysql;
        
        $data['username'] = trim($param['username']);
        $data['password'] = trim($param['password']);
        $data['email']    = trim($param['email']);
		$data['sex']      = trim($param['gender']);
		$data['realname']      = trim($param['realname']);
		# 检查用户名和邮箱
		if ($mysql->db_fetch_array("select 1 from {user} where username='{$data['username']}'")) {
			return self::ADDMEMBER_ERROR_USER_EXISTS;
		}
		if ($mysql->db_fetch_array("select 1 from {user} where email='{$data['email']}'")) {
			return self::ADDMEMBER_ERROR_MAIL_EXISTS;
		}
		
        $data['type_id'] = 2;
        $data['status'] = 0;
        if (isset($data['realname'])) {
            if (strlen($data['realname']) > 8 and strlen($data['realname']) <2) {
                return self::ADDMEMBER_ERROR_REAL_NAME;
            }
        }

        if (1 != $_G['system']['con_member_reg_mail']) {
            $data['status'] = 1;
        }

        $user_id = 0;
        $user = new User();
        $result = true === $user->add($data, $user_id)?true:false;
		
        return $user_id;
    }

	 /**
     * 用户登录
	 * @param $param array
	 *		array(
	 *			'key_value'=>'值',
	 *			'password'=>'密码',
	 *			'login_type'=>'验证类型(username/email)'
	 *		)
	 * @return
	 *		Member::LOGIN_ERROR_USER_PWD 用户名或密码错误<br>
	 *		Member::LOGIN_ERROR_UCENTER ucenter同步登录失败<br>
	 *		Member::LOGIN_STEP_EMAIL 邮箱激活步骤<br>
	 *		Member::LOGIN_STEP_INFO 信息完善步骤<br>
	 *		Member::LOGIN_STEP_AVATAR 头像上传步骤<br>
	 *		成功返回array(会员信息)
     */
     public static function Login ($param) {
        global $mysql, $user;

		$user_name = $param['key_value'];
		if ('username' != $param['login_type']) {
			$row = $mysql->db_fetch_array("select username from {user} where {$param['login_type']}='{$param['key_value']}'");
			if (!$row) {
				return self::LOGIN_ERROR_USER_PWD;
			}
			$user_name = $row['username'];
		}
		
        $user_info = $user->login($user_name, $param['password']);
        if (!$user_info) {
            return self::LOGIN_ERROR_USER_PWD;
        }else{
			$_SESSION['username'] = $user_info['username'];
			$_SESSION['user_id'] = $user_info['user_id'];
			$_SESSION['usertype'] = 0;
			$_SESSION['usertime'] = time();
		}
        if (1 != $user_info['status']) {
			return self::LOGIN_STEP_EMAIL;
        }
        if (!$user_info['litpic']) {
			return self::LOGIN_STEP_AVATAR;
        }
        if ($user->is_uc) {
            $u_id = (int)UcenterClient::login($user_name, $param['password']);
            if ($u_id <= 0) {
                return self::LOGIN_ERROR_UCENTER;
            }
        }
		# 获取VIP信息
		$vip = VipUser::GetVIPUserInfo($user_info['user_id']);
		$user_info['vip_info'] = $vip;
		
        return $user_info;
    }

	/**
	 * 用户退出
	 * @return bool true
	 */
	public static function LogOut () {
		global $user;
		
		if ($user->is_uc) {
			UcenterClient::LogOut();
		}
		if (isset($_SESSION['username'])) unset($_SESSION['username']);
		if (isset($_SESSION['user_id'])) unset($_SESSION['user_id']);
		if (isset($_SESSION['userid'])) unset($_SESSION['userid']);
		if (isset($_SESSION['usertype'])) unset($_SESSION['usertype']);
		if (isset($_SESSION['usertime'])) unset($_SESSION['usertime']);

		return true;
	}

    /**
     * 发激活邮件
	 * @param $param
	 *		array(
	 *			'user_id'=>'会员ID',
	 *			'email'=>'邮箱',
	 *			'msg'=>'信息内容'
	 *		)
	 * @return bool true/false
     */
    public static function SendActiveMail ($param) {
        require_once ROOT_PATH . 'plugins/mail/mail.php';
        global $mysql;

		$user_id = $param['user_id'];
		$email = $param['email'];
		$msg   = isset($param['msg'])?$param['msg']:'';
        $user_info = $mysql->db_fetch_array("select * from {user} where user_id={$user_id}");
        if (!$user_info) {
            return false;
        }
        if (!$email) {
            $email = $user_info['email'];
        }
        $result = Mail::Send("账号注册确认",$msg, array(array($email,'')));
		
        if ($result && $email != $user_info['email']) {
            $mysql->db_query("update {user} set email='{$email}' where user_id={$user_id}");
        }

        return true;
    }

   

    /**
     * 完善会员资料
     * @param $param 更新数据
	 *		array(
	 *			'user_id'=>'会员ID'
	 *			'birthday'=>'生日',
	 *			'province'=>'省会',
	 *			'city'=>'城市',
	 *			'area'=>'区',
	 *			'school'=>'学校',
	 *			'professional'=>'专业',
	 *			'school_area'=>'校区'
	 *		)
	 * @return bool
     */
    public static function PerfectUserInfo ($param) {
        global $module;

		$data['birthday'] = $param['birthday'];
		$data['province'] = $param['province'];
		$data['city']     = $param['city'];
		$data['area']     = $param['area'];
		$data['school']   = $param['school'];
		$data['professional'] = $param['professional'];
		$data['school_area']  = $param['school_area'];
		
		$user_id = $param['user_id'];
		
        # 用户信息
        $user_key = array(
          'birthday',
          'province',
          'city',
          'area',
        );
        # 简历信息
        $resume_key = array(
          'school',
          'professional',
          'school_area'
        );
        $user_info = array();
        $resume_info = array();
        foreach ($data as $key => $value) {
            if (in_array($key, $user_key)) {
                $user_info[$key] = $value;
            }
            elseif(in_array($key, $resume_key)) {
                $resume_info[$key] = $value;
            }
        }

        if ($user_info) {
            $user = new User();
			
            if (-1 === $user->update($user_info, $user_id)) {
               return self::PERFECTUSER_ERROR_UPDATE_USER;
            }
        }

        # 更新简历信息
        if ($resume_info) {
            if ($module->get_module('schoolresume')) {
                require_once ROOT_PATH . 'modules/schoolresume/schoolresume.class.php';
                if(!SchoolResume::Update($user_id, $resume_info)){
                    return self::PERFECTUSER_ERROR_UPDATE_RESUME;
                }
            }
        }

        return true;
    }

	/**
	 * 上传头像
	 * @param $user_id 会员ID
	 * @param $avatar 头像参数名
	 * @param $width 头像宽度
	 * @param $height 头像高度
	 */
	/**
	 * @param $param
	 *		array(
	 *			'user_id'=>'会员号',
	 *			'avatar'=>'头像参数名',
	 *			'width'=>'头像宽度[option]',
	 *			'height'=>'头像高度[option]'
	 *		)
	 * @return bool true/false
	 */
	public static function UploadAvatar ($param) {
		global $mysql, $upload, $user;

		$user_id = $param['user_id'];
		$avatar = $param['avatar'];
		$width  = isset($param['width'])?$param['width']:100;
		$height = isset($param['height'])?$param['height']:100;
		
		$upload->setUploadPic($width, $height);
        $pic = $upload->uploadImg($avatar);
		$data = array();

		if ($pic) {
			$data['litpic'] = $pic;
		}
        
        if ($data['litpic']) {
            $mysql->db_update('user', $data, "user_id={$user_id}");
        }

		if ($user->is_uc && $user_id > 0) {
			$u = $mysql->db_fetch_array("select username, password from {user} where user_id={$user_id}");
			if ($u) {
				UcenterClient::login($u['username'], $u['password']);
			}
		}
		
		return true;
	}

	/**
	 * 获取用户信息
	 * @param $user_id 会员ID
	 * @return array 会员信息
	 */
	public static function ViewMember ($user_id) {
		global $mysql;
		
		$row = $mysql->db_fetch_array("select * from {user} where user_id={$user_id}");
		
		return $row;
	}

	/**
	 * 获取会员简历
	 * @param $user_id 会员ID
	 * @return array 简历信息
	 */
	public static function ViewMemberResume ($user_id) {
		global $mysql, $module;

		$row = array();
		if ($module->get_module('schoolresume')) {
			$row = $mysql->db_fetch_array("select * from {school_resume} where user_id = {$user_id}");
		}

		return $row;
	}

	/**
	 * 更新会员信息
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID',
	 *			...会员信息
	 *		)
	 * @return
	 *		Member::UPDATEMEMBER_MAIL_EXISTS 邮箱重复<br>
	 *		Member::UPDATEMEMBER_ERROR_REAL_NAME 姓名无效<br>
	 *		Member::UPDATEMEMBER_ERROR_USER 会员信息更新失败<br>
	 *		true 成功
	 */
	public static function UpdateMemberInfo ($param) {
		global $mysql;

		$user_id = $param['user_id'];
		$data = $param;
		$can_not_update = array(
			'user_id',
			'username',
			'addtime',
			'addip'
		);
		$temp = $data;
		foreach ($temp as $key => $value) {
			if (in_array($key, $can_not_update)) {
				unset($data[$key]);
			}
		}

		if (isset($data['email'])) {
			$row = $mysql->db_fetch_array("select user_id from {user}
											where email='{$data['email']}'");
			if ($row) {
				if ($row['user_id'] == $user_id) {
					unset($data['email']);
				}
				else {
					return self::UPDATEMEMBER_MAIL_EXISTS;
				}
			}
		}
		if (isset($data['realname'])) {
			if (strlen($data['realname']) > 8) {
				return self::UPDATEMEMBER_ERROR_REAL_NAME;
			}
		}

		if (!empty($data)) {
			$user = new User();
			if (-1 === $user->update($data, $user_id)) {
				return self::UPDATEMEMBER_ERROR_USER;
			}
		}

		return true;
	}

	/**
	 * 添加会员简历
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID',
	 *			... 简历信息
	 *		)
	 * @return bool true/false
	 */
	public static function AddMemberResume ($param) {
		require_once ROOT_PATH . 'modules/schoolresume/schoolresume.class.php';

		$user_id = $param['user_id'];
		$data = $param;
		if(!SchoolResume::Update($user_id, $data)){
			return false;
		}

		return true;
	}

	/**
	 * 更新会员简历
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID',
	 *			... 简历信息
	 *		)
	 * @return bool true/false
	 */
	public static function UpdateMemberResume ($param) {
		require_once ROOT_PATH . 'modules/schoolresume/schoolresume.class.php';

		$user_id = $param['user_id'];
		$data = $param;
		unset($data['user_id']);
		if(!SchoolResume::Update($user_id, $data)){
			return false;
		}

		return true;
	}

	/**
	 * 兼职报名
	 * @param $param
	 *		array(
	 *			'job_id' => '岗位ID',
	 *			'user_id' => '会员ID'
	 *		)
	 * @return
	 *		PartTime::APPLY_REAPPLY 重复报名<br>
	 *		PartTime::APPLY_HAVE_APPLY 已报名其他岗位<br>
	 *		PartTime::APPLY_ON_WORK 在职会员<br>
	 *		PartTime::APPLY_NO_VIP 非VIP会员<br>
	 *		PartTime::APPLY_VIP_NOACTIVE VIP非激活状态<br>
	 *		PartTime::APPLY_NO_JOB 岗位不存在<br>
	 *		PartTime::APPLY_NO_START_APPLY 岗位还没开始报名<br>
	 *		PartTime::APPLY_END_APPLY 岗位已结束预订<br>
	 *		PartTime::APPLY_FULL 报名人数已满<br>
	 *		PartTime::APPLY_SEX 性别不符合<br>
	 *		PartTime::APPLY_HEIGHT 身高不符合<br>
	 *		PartTime::APPLY_WORK_TIME 工作时间不符合<br>
	 *		PartTime::APPLY_HEALTH 要求健康证<br>
	 *		PartTime::APPLY_BIKE 要求自行车<br>
	 *		true 成功
	 */
	public static function ApplyPartTime ($param) {

		$job_id  = $param['job_id'];
		$user_id = $param['user_id'];
		return PartTime::Apply($job_id, $user_id);
	}

	/**
	 * 兼职取消报名
	 * @param $param
	 *		array(
	 *			'job_id' => '岗位ID',
	 *			'user_id' => '会员ID'
	 *		)
	 * @return
	 *		PartTime::CANCELAPPLY_ERROR_USER_ID 会员ID错误<br>
	 *		PartTime::CANCELAPPLY_END_TIME 报名即将结束，不能取消<br>
	 *		true 成功
	 */
	public static function CancelApplyPartTime ($param) {
		
		$job_id  = $param['job_id'];
		$user_id = $param['user_id'];
		return PartTime::CancelApply($job_id, $user_id);
	}

	/**
	 * 活动报名
	 * @param $param
	 *		array(
	 *			'id' => '活动ID',
	 *			'user_id' => '会员ID'
	 *		)
	 * @return
	 *		HuoDong::APPLY_NO_START_APPLY 活动还没开始报名<br>
	 *		HuoDong::APPLY_FULL 报名人数已满<br>
	 *		HuoDong::APPLY_END_APPLY 活动结束报名<br>
	 *		HuoDong::APPLY_REAPPLY 重复报名<br>
	 *		HuoDong::APPLY_NOT_EXISTS 活动不存在<br>
	 *		true 成功
	 */
	public static function ApplyHuoDong ($param) {

		$id = $param['id'];
		$user_id = $param['user_id'];
		return HuoDong::Apply($id, $user_id);
	}

	/**
	 * 活动取消报名
	 * @param $param
	 *		array(
	 *			'id' => '活动ID',
	 *			'user_id' => '会员ID'
	 *		)
	 * @return
	 *		HuoDong::CANCELAPPLY_END_TIME 报名即将结束，不能取消<br>
	 *		true 成功
	 */
	public static function CancelApplyHuoDong ($param) {

		$id  = $param['id'];
		$user_id = $param['user_id'];
		return HuoDong::CancelApply($id, $user_id);
	}
	
	/**
	 * 获取报名的岗位
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID',
	 *			'page' => '页码',
	 *			'where' => 'array(number=>123,...)',
	 *			'epage' => '每页记录数'
	 *		)
	 * @return
	 *	array(
	 *		'list' => array(岗位信息),
	 *		'record_num' => 记录数,
	 *		'page' => 页码,
	 *		'page_size' => 每页记录数
	 *		'total_page' => 总页码
	 * )
	 */
	public static function GetApplyPartTime ($param) {
		
		$user_id   = $param['user_id'];
		$page      = $param['page'];
		$where     = $param['where'];
		$page_size = $param['epage'];

		return PartTime::GetMemberApplyList($user_id, $page, $where, $page_size);
	}

	/**
	 * 获取会员录取的兼职列表
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID',
	 *			'page' => '页码',
	 *			'where' => 'array(number=>123,...)',
	 *			'epage' => '每页记录数'
	 *		)
	 * @return
	 *	array(
	 *		'list' => array(岗位信息),
	 *		'record_num' => 记录数,
	 *		'page' => 页码,
	 *		'page_size' => 每页记录数
	 *		'total_page' => 总页码
	 * )
	 */
	public static function GetMemberWork ($param) {

		$user_id   = $param['user_id'];
		$page      = $param['page'];
		$where     = $param['where'];
		$page_size = $param['epage'];

		return PartTime::GetMemberWorkList($user_id, $page, $where, $page_size);
	}

	/**
	 * 获取兼职岗位列表
	 * @param $param
	 *		array(
	 *			'page' => '页码',
	 *			'where' => 'array(name like 'abc',...)',
	 *			'epage' => '每页记录数'
	 *		)
	 * @return
	 *	array(
	 *		'list' => array(岗位信息),
	 *		'record_num' => 记录数,
	 *		'page' => 页码,
	 *		'total_page' => 总页码
	 * )
	 */
	public static function GetJob ($param) {
		
		$page      = $param['page'];
		$where     = $param['where'];
		$page_size = $param['epage'];

		$where['status'] = 1;
		
		return PartTime::GetJobList($page, $where, $page_size);
	}

	/**
	 * 获取报名的活动
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID',
	 *			'page' => '页码',
	 *			'where' => 'array(id=>12,...)',
	 *			'epage' => '每页记录数'
	 *		)
	 * @return
	 *	array(
	 *		'list' => array(活动信息),
	 *		'record_num' => 记录数,
	 *		'page' => 页码,
	 *		'page_size' => 每页记录数
	 *		'total_page' => 总页码
	 * )
	 */
	public static function GetApplyHuoDong ($param) {

		$user_id = $param['user_id'];
		$page    = isset($param['page'])?(int)$param['page']:1;
		$where   = isset($param['where'])?$param['where']:array();
		$page_size = isset($param['epage'])?(int)$param['epage']:20;
		
		return HuoDong::GetApply($user_id, $page, $where, $page_size);
	}

	/**
	 * 获取活动列表
	 * @param $param
	 *		array(
	 *			'page' => '页码',
	 *			'where' => 'array(id=>12,...)',
	 *			'epage' => '每页记录数'
	 *		)
	 * @return
	 *	array(
	 *		'list' => array(活动信息),
	 *		'record_num' => 记录数,
	 *		'page' => 页码,
	 *		'page_size' => 每页记录数
	 *		'total_page' => 总页码
	 * )
	 */
	public static function GetHuoDong ($param) {

		$page = isset($param['page'])?(int)$param['page']:1;
		$where = isset($param['where'])?$param['where']:array();
		$page_size = isset($param['epage'])?(int)$param['epage']:20;
		
		return HuoDong::Get($page, $where, $page_size);
	}

	/**
	 * 培训报名
	 * @param $param
	 *		array(
	 *			'id' => '培训ID',
	 *			'user_id' => '会员ID'
	 *		)
	 * @return
	 *		PeiXun::APPLY_NO_START_APPLY 培训还没开始报名<br>
	 *		PeiXun::APPLY_FULL 报名人数已满<br>
	 *		PeiXun::APPLY_END_APPLY 培训结束报名<br>
	 *		PeiXun::APPLY_REAPPLY 重复报名<br>
	 *		PeiXun::APPLY_NOT_EXISTS 培训不存在<br>
	 *		true 成功
	 */
	public static function ApplyPeiXun ($param) {

		$id = $param['id'];
		$user_id = $param['user_id'];
		return PeiXun::Apply($id, $user_id);
	}

	/**
	 * 培训取消报名
	 * @param $param
	 *		array(
	 *			'id' => '培训ID',
	 *			'user_id' => '会员ID'
	 *		)
	 * @return
	 *		HuoDong::CANCELAPPLY_END_TIME 报名即将结束，不能取消<br>
	 *		true 成功
	 */
	public static function CancelApplyPeiXun ($param) {

		$id  = $param['id'];
		$user_id = $param['user_id'];
		return PeiXun::CancelApply($id, $user_id);
	}
	
	/**
	 * 获取报名的培训
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID',
	 *			'page' => '页码',
	 *			'where' => 'array(id=>12,...)',
	 *			'epage' => '每页记录数'
	 *		)
	 * @return
	 *	array(
	 *		'list' => array(培训信息),
	 *		'record_num' => 记录数,
	 *		'page' => 页码,
	 *		'page_size' => 每页记录数
	 *		'total_page' => 总页码
	 * )
	 */
	public static function GetApplyPeiXun ($param) {

		$user_id = $param['user_id'];
		$page    = isset($param['page'])?(int)$param['page']:1;
		$where   = isset($param['where'])?$param['where']:array();
		$page_size = isset($param['epage'])?(int)$param['epage']:20;

		return PeiXun::GetApply($user_id, $page, $where, $page_size);
	}

	/**
	 * 获取培训列表
	 * @param $param
	 *		array(
	 *			'page' => '页码',
	 *			'where' => 'array(id=>12,...)',
	 *			'epage' => '每页记录数'
	 *		)
	 * @return
	 *	array(
	 *		'list' => array(培训信息),
	 *		'record_num' => 记录数,
	 *		'page' => 页码,
	 *		'page_size' => 每页记录数
	 *		'total_page' => 总页码
	 * )
	 */
	public static function GetPeiXun ($param) {

		$page = isset($param['page'])?(int)$param['page']:1;
		$where = isset($param['where'])?$param['where']:array();
		$page_size = isset($param['epage'])?(int)$param['epage']:20;

		return PeiXun::Get($page, $where, $page_size);
	}
	
	/**
	 * 获取VIP卡类型
	 * @return array VIP卡类型
	 */
	public static function GetVIPType () {

		return VipUser::CardType();
	}


	/**
	 * 激活VIP
	 * @param $param
	 *		array(
	 *			'user_id' => '会员VIP',
	 *			'card_type' => 'VIP类型ID',
	 *			'card_number' => '卡号',
	 *			'password' => 'VIP卡密码',
	 *		)
	 * @return
	 *		VipUser::USER_NOT_EXISTS 会员不存在<br>
	 *		VipUser::USER_NOT_RESUME 会员没有简历<br>
	 *		VipUser::USER_IS_VIP 已经是VIP会员<br>
	 *		VipUser::CARD_NOT_EXISTS VIP卡不存在<br>
	 *		VipUser::CARD_IS_USED VIP 卡已被使用<br>
	 *		VipUser::CARD_ERROR_PASSWORD VIP卡密码错误<br>
	 *		VipUser::INVALID_CARD_TYPE VIP类型无效<br>
	 *		true 成功
	 */
	public static function ActiveVIP ($param) {
		global $mysql;

		$user_name = '';
		$user_id   = $param['user_id'];
		$vct_id    = $param['card_type'];
		$card_number = $param['card_number'];
		$password    = $param['password'];
		
		$user = $mysql->db_fetch_array("select username from {user} where user_id={$user_id}");
		if ($user) {
			$user_name = $user['username'];
		}

		$result = VipUser::AddVipUser($user_name, $vct_id, $card_number, $password);
		if (true !== $result) {
			return $result;
		}

		return VipUser::GetVIPUserInfo($user_id);
	}

	/**
	 * 冻结VIP卡
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID'
	 *			'month' => '冻结月数'
	 *		)
	 * @return
	 *		VipUser::FREEZE_NO_ACTIVE_STATUS 非激活状态无法冻结<br>
	 *		VipUser::FREEZE_MORE_TIMES 冻结超过2次<br>
	 *		VipUser::FREEZE_INVALID_TIME 冻结时间无效<br>
	 *		true 成功
	 */
	public static function FreezeVIP ($param) {
		global $mysql;

		$user_id = $param['user_id'];
		$month   = $param['month'];

		$vu = $mysql->db_fetch_array("select card_number from {vip_user} where user_id={$user_id}");
		if (!$vu) {
			return false;
		}
		$vc = $mysql->db_fetch_array("select id from {vip_card} where serial_number='{$vu['card_number']}'");
		if (!$vc) {
			return false;
		}
		
		return VipUser::FreezeCard($vc['id'], $month);
	}

	/**
	 * 解除VIP冻结
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID'
	 *		)
	 * @return bool true/false
	 */
	public static function ReleaseFreezeVIP ($param) {
		global $mysql;

		$user_id = $param['user_id'];

		$vu = $mysql->db_fetch_array("select card_number from {vip_user} where user_id={$user_id}");
		if (!$vu) {
			return false;
		}
		
		$vc = $mysql->db_fetch_array("select id,status from {vip_card} where serial_number='{$vu['card_number']}'");
		if (!$vc) {
			return false;
		}
		if (1 == $vc['status']) {
			return true;
		}
		else {
			if (2 != $vc['status']) {
				return false;
			}
		}

		return VipUser::ActiveCard($vc['id']);
	}

	/**
	 * 添加收藏
	 * @param $param
	 *		array(
	 *			'user_id' => '会员号',
	 *			'code' => '所属模块',
	 *			'aid' => '文章ID',
	 *			'name' => '标题',
	 *			'url' => '链接地址',
	 *			'remark' => '备注'
	 *		)
	 * @return
	 *		Member::ADDFAVORITES_MODULE_NULL 所属模块不能为空<br>
	 *		Member::ADDFAVORITES_NAME_NULL 标题不能为空<br>
	 *		Member::ADDFAVORITES_URL_NULL 链接地址不能为空<br>
	 *		Member::ADDFAVORITES_NOT_EXISTS_USER 用户不存在<br>
	 *		Member::ADDFAVORITES_NOT_EXISTS_MODULE 模块不存在<br>
	 *		true 成功
	 */
	public static function AddFavorites ($pram) {

		$user_id  = isset($pram['user_id'])?(int)($pram['user_id']):0;
		$module = isset($pram['code'])?$pram['code']:'';
		$aid  = isset($pram['aid'])?(int)($pram['aid']):'';
		$name = isset($pram['name'])?trim($pram['name']):'';
		$url  = isset($pram['url'])?trim($pram['url']):'';
		$remark = isset($pram['remark'])?trim($pram['remark']):'';
		
		if (!$module) {
			return self::ADDFAVORITES_MODULE_NULL;
		}
		if (!$name) {
			return self::ADDFAVORITES_NAME_NULL;
		}
		if (!$url) {
			return self::ADDFAVORITES_URL_NULL;
		}
		
		$result = Favorites::AddFavorites ($user_id, $module, $name, $url, $aid, $remark);
		if (Favorites::NOT_EXISTS_USER === $result) {
			return self::ADDFAVORITES_NOT_EXISTS_USER;
		}
		elseif (Favorites::NOT_EXISTS_MODULE === $result) {
			return self::ADDFAVORITES_NOT_EXISTS_MODULE;
		}
		
		return true;
	}

	/**
	 * 获取收藏列表
	 * @param $param
	 *		array(
	 *			'user_id' => '会员ID',
	 *			'code' => '所属模块',
	 *			'page' => '页码',
	 *			'epage' => '每页记录数'
	 *		)
	 * @return
	 *	array(
	 *		'list' => array(收藏信息),
	 *		'record_num' => 记录数,
	 *		'page' => 页码,
	 *		'page_size' => 每页记录数
	 *		'total_page' => 总页码
	 * )
	 */
	public static function GetFavorites ($param) {

		$user_id   = $param['user_id'];
		$module    =  isset($param['code'])?$param['code']:'';
		$page      = isset($param['page'])?$param['page']:1;
		$page_size = isset($param['epage'])?$param['epage']:20;

		return Favorites::ListFavorites($user_id, $module, '', $page, $page_size);
	}

	/**
     * 加密激活链接
	 * @param $id
	 * @return String
     */
    public static function EnActionCode ($id) {
        
        return base64_encode((string)($id * 3 + self::ACTIVE_KEY));
    }

    /**
     * 解密激活链接
	 * @param $id
	 * @return String
     */
    public static function DeActionCode ($id) {
        
        return (base64_decode($id) - self::ACTIVE_KEY) / 3;
    }

}
?>
