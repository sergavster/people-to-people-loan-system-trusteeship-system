<?php
require_once ROOT_PATH . '/core/config_ucenter.php';
require_once ROOT_PATH . '/uc_client/client.php';
# ÅÐ¶ÏÊÇ·ñUcenter
		$ctime = (int)time() - (72 * 60);
		 

		 
		if ($rdGlobal['uc_on'])
		{
			if ($user->is_uc) {
				UcenterClient::LogOut();
			}
		}
		if ($_G['is_cookie'] ==1){
				setcookie('rdun', '', -31536000);
				setcookie('login_uid', '', -31536000);
				setcookie('login_endtime', '', -31536000);
		}else{
			$_SESSION["rdun"] = "";
			$_SESSION['login_uid'] = "";
			$_SESSION['login_endtime'] = "";
	
		}
	
		$_SESSION["QC_userData"]['state'] = "";
		$_SESSION["QC_userData"] = array();
		setcookie('PHPSESSID', '', -31536000);

			if (isset($_SESSION["rdun"])) unset($_SESSION["rdun"]);
		if (isset($_SESSION["login_uid"])) unset($_SESSION["login_uid"]);
		if (isset($_SESSION['username'])) unset($_SESSION['username']);
		if (isset($_SESSION['login_endtime'])) unset($_SESSION['login_endtime']);
		if (isset($_SESSION['user_realname'])) unset($_SESSION['user_realname']);
		if (isset($_SESSION['user_typename'])) unset($_SESSION['user_typename']);
		if (isset($_SESSION['user_id'])) unset($_SESSION['user_id']);
		if (isset($_SESSION['userid'])) unset($_SESSION['userid']);
		if (isset($_SESSION['usertype'])) unset($_SESSION['usertype']);
		if (isset($_SESSION['usertime'])) unset($_SESSION['usertime']);
		if (isset($_SESSION['reg_step'])) unset($_SESSION['reg_step']);
		if ($rdGlobal['uc_on'])
		{
			$ucsynlogout = uc_user_synlogout();
			echo $ucsynlogout;
		}
		echo '<script language="javascript">window.location.href="index.action?user&q=going/login";</script>';
		exit();
?>