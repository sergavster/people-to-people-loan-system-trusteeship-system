<?php
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

include_once("userinfo.class.php");

$_U['userinfo_result'] = userinfoClass::GetOne(array("user_id"=>$_G['user_id']));

if (isset($_POST['type']) && $_POST['type'] ==1){
	
	/*
	//��������
	if ($_U['query_type'] == "list"){
		$var = array("marry","child","education","income","shebao","shebaoid","housing","car","late");
                $var2 = array("realname","phone","province","city","area","sex");
		$_msg = "���������޸ĳɹ�";
		$_url = "building";
	}
	//��������
	elseif ($_U['query_type'] == "building"){
		$var = array("house_address","house_area","house_year","house_status","house_holder1","house_holder2","house_right1","house_right2","house_loanyear","house_loanprice","house_balance","house_bank");
		$_msg = "���������޸ĳɹ�";
		$_url = "company";
	}
	//��λ����
	elseif ($_U['query_type'] == "company"){
		$var = array("company_name","company_type","company_industry","company_office","company_jibie","company_worktime1","company_worktime2","company_workyear","company_tel","company_address","company_weburl","company_reamrk");
		$_msg = "��λ�����޸ĳɹ�";	
		$_url = "firm";
	}
	
	//˽Ӫҵ��
	elseif ($_U['query_type'] == "firm"){
		$var = array("private_type","private_date","private_place","private_rent","private_term","private_taxid","private_commerceid","private_income","private_employee");
		$_msg = "��λ�����޸ĳɹ�";
		$_url = "finance";
	}

	//����״��
	elseif ($_U['query_type'] == "finance"){
		$var = array("finance_repayment","finance_property","finance_amount","finance_car","finance_caramount","finance_creditcard");
		$_msg = "���������޸ĳɹ�";
		$_url = "contact";
	}

	//��ϵ��ʽ
	elseif ($_U['query_type'] == "contact"){
		$var = array("tel","phone","post","address","province","city","area","linkman1","relation1","tel1","phone1","linkman2","relation2","tel2","phone2","linkman3","relation3","tel3","phone3","msn","qq","wangwang");
		$_msg = "��ϵ��ʽ�޸ĳɹ�";
		$_url = "mate";
	}

	//��ż����
	elseif ($_U['query_type'] == "mate"){
		$var = array("mate_name","mate_salary","mate_phone","mate_tel","mate_type","mate_office","mate_address","mate_income");
		$_msg = "��ż�����޸ĳɹ�";
		$_url = "edu";
	}

	//��������
	elseif ($_U['query_type'] == "edu"){
		$var = array("education_record","education_school","education_study","education_time1","education_time2");
		$_msg = "���������޸ĳɹ�";
		$_url = "job";
	}

	elseif ($_U['query_type'] == "job"){
		$var = array("ability","interest","others","experience");	
		$_msg = "���������޸ĳɹ�";
		$_url = "list";
	}
	*/
	
	//����Ƿ����б��� ����д��
	$user_type = $_G['user_result']['user_type'];
	if(isset($_POST['user_type'])){
		$user_type = $_POST['user_type'];
	}
	
	//��������
	if ($_U['query_type'] == "list"){
		$var = array("used_name", "marry", "child", "shebao", "shebaoid", "housing", "car", "late");
                $var2 = array("realname","phone","province","city","area","sex");
		$_msg = "���������޸ĳɹ�";
		if($_POST['housing']!=34 && $_POST['car']==37){
			if($user_type == 2){
				$_url = "company";
			}else{
				$_url = "firm";
			}
		}else{
			$_url = "building";
		}
	}
	//��������
	elseif ($_U['query_type'] == "building"){
		$var = array("house_address", "house_area", "house_year", "house_status", "house_loanyear", "house_loanprice", "house_balance", "house_bank", "car_brand", "car_value", "car_buy_time", "car_loan", "car_month_loan", "car_loan_year");
		$_msg = "�Ʋ�״���޸ĳɹ�";
		
		if($user_type == 2){
			$_url = "company";
		}else{
			$_url = "firm";
		}
	}
	//��λ����
	elseif ($_U['query_type'] == "company"){
		$var = array("company_name", "company_type", "company_industry", "work_department", "company_jibie", "company_office", "company_worktime1", "company_worktime2", "company_workyear", "company_tel", "company_address", "company_weburl", "income", "other_income", "company_reamrk");
		$_msg = "��λ�����޸ĳɹ�";	
		$_url = "finance";
	}
	
	//˽Ӫҵ��
	elseif ($_U['query_type'] == "firm"){
		$var = array("private_company_name", "private_type", "private_date", "private_capital", "private_representative", "private_phone", "private_stock_rate", "private_place", "private_place_type", "private_rent", "private_term", "private_taxid", "private_commerceid", "private_year_sales", "private_income", "private_employee", "private_introduce");
		$_msg = "˽Ӫҵ�������޸ĳɹ�";
		$_url = "finance";
	}

	//����״��
	elseif ($_U['query_type'] == "finance"){
		$var = array("finance_repayment", "finance_creditcard", "finance_other_live_cost", "finance_living_spend");
		$_msg = "���������޸ĳɹ�";
		$_url = "contact";
	}

	//��ϵ��ʽ
	elseif ($_U['query_type'] == "contact"){
		$var = array("tel", "post", "address", "linkman1", "relation1", "tel1", "phone1", "linkman2", "relation2", "tel2", "phone2", "msn", "qq", "other_net_contact");
		$_msg = "��ϵ��ʽ�޸ĳɹ�";
		$_url = "mate";
	}

	//��ż����
	elseif ($_U['query_type'] == "mate"){
		$var = array("mate_name", "mate_salary", "mate_phone", "mate_tel", "mate_company", "mate_type", "mate_office", "mate_address", "mate_income");
		$_msg = "��ż�����޸ĳɹ�";
		$_url = "edu";
	}

	//��������
	elseif ($_U['query_type'] == "edu"){
		$var = array("education_record", "education_school", "education_study");
		$_msg = "���������޸ĳɹ�";
		$_url = "job";
	}

	elseif ($_U['query_type'] == "job"){
		$var = array("ability", "interest", "others");	
		$_msg = "���������޸ĳɹ�";
		$_url = "list";
	}
	
	
	$data = post_var($var);
    if ($_U['query_type'] == "list"){
        $data2 = post_var($var2);
        $data2['user_id'] = $_G['user_id'];
    }
	$data['user_id'] = $_G['user_id'];
	
	$result = userinfoClass::GetOne(array("user_id"=>$_G['user_id']));


	$info_conf_result = userinfoClass::get_userinfo_conf(array("usertype_id"=>$user_type));
	$info_conf = json_decode($info_conf_result['conflist'], true);
	
	$can_save = true;
	$user_housing = "{$_G['user_result']['housing']}";//�Ƿ��з�
 	$user_car = "{$_G['user_result']['car']}";//�Ƿ��г�
	if($_U['query_type'] == "list"){
		$info_list = array("used_name", "marry", "child", "shebao", "shebaoid", "housing", "car", "late");
	}else if($_U['query_type'] == "building"){
		if($user_housing!=34 && $user_car!=37){//�г�û��
			$info_list = array("car_brand", "car_value", "car_buy_time", "car_loan", "car_month_loan", "car_loan_year");
		}else if($user_housing==34 && $user_car==37){//�з�û��
			$info_list = array("house_address", "house_area", "house_year", "house_status", "house_loanyear", "house_loanprice", "house_balance", "house_bank");
		}else if($user_housing==34 && $user_car!=37){//�г��з�
			$info_list = array("house_address", "house_area", "house_year", "house_status", "house_loanyear", "house_loanprice", "house_balance", "house_bank", "car_brand", "car_value", "car_buy_time", "car_loan", "car_month_loan", "car_loan_year");
		}else{//û��û��
			$info_list = array();
		}
	}else if($_U['query_type'] == "company"){
		$info_list = array("company_name", "company_type", "company_industry", "work_department", "company_jibie", "company_office", "company_worktime1", "company_worktime2", "company_workyear", "company_tel", "company_address", "company_weburl", "income", "other_income", "company_reamrk");
	}else if($_U['query_type'] == "firm"){
		$info_list = array("private_company_name", "private_type", "private_date", "private_capital", "private_representative", "private_phone",  "private_stock_rate", "private_place", "private_place_type", "private_rent", "private_term", "private_taxid", "private_commerceid", "private_year_sales", "private_income", "private_employee", "private_introduce");
	}else if($_U['query_type'] == "finance"){
		$info_list = array("finance_repayment", "finance_creditcard", "finance_other_live_cost", "finance_living_spend");
	}else if($_U['query_type'] == "contact"){
		$info_list = array("tel", "post", "address", "linkman1", "relation1", "tel1", "phone1", "linkman2", "relation2", "tel2", "phone2", "msn", "qq", "other_net_contact");
	}else if($_U['query_type'] == "mate"){
		$info_list = array("mate_name", "mate_salary", "mate_phone", "mate_tel", "mate_company", "mate_type", "mate_office", "mate_address", "mate_income");
	}else if($_U['query_type'] == "edu"){
		$info_list = array("education_record", "education_school", "education_study");
	}else if($_U['query_type'] == "job"){
		$info_list = array("ability", "interest", "others");
	}
	
	
	foreach ($info_list as $key => $value){
		if($info_conf[$value]==1 && $data[$value]==""){
			$can_save = false;
			break;
		}
	}

	
	if ($result == false){
		$result = userinfoClass::Add($data);
	}else{
		$result = userinfoClass::Update($data);
	}
	
	if (isset($_POST['user_type']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['user_type'] = $_POST['user_type'];
		userClass::UpdateUser($datauser);
	}
	
	if (isset($data['qq']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['qq'] = $data['qq'];
		userClass::UpdateUser($datauser);
	}
	if (isset($data['tel']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['tel'] = $data['tel'];
		userClass::UpdateUser($datauser);
	}
	if (isset($data['address']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['address'] = $data['address'];
		userClass::UpdateUser($datauser);
	}
	if (isset($_POST['realname'])){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['realname'] = $_POST['realname'];
		userClass::UpdateUser($datauser);
	}
    /*    
	if (isset($data2['realname']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['realname'] = $data2['realname'];
		userClass::UpdateUser($datauser);
	}

	if (isset($data2['phone']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['phone'] = $data2['phone'];
		userClass::UpdateUser($datauser);
	}
        
	if (isset($data2['province']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['province'] = $data2['province'];
		userClass::UpdateUser($datauser);
	}
        
	if (isset($data2['city']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['city'] = $data2['city'];
		userClass::UpdateUser($datauser);
	}
        
	if (isset($data2['area']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['area'] = $data2['area'];
		userClass::UpdateUser($datauser);
	}
        
	if (isset($data2['sex']) ){
		$datauser['user_id'] = $_G['user_id'];
		$datauser['sex'] = $data2['sex'];
		userClass::UpdateUser($datauser);
	}
	*/
	
	if ($result !== true || $can_save !==true){
// 		$msg = array($result);
		$msg = array("�б�����Ϊ��");
	}else{
		//$msg = array($_msg,"",$_U['query_url']."/".$_url);
		$next_url = $_U['query_url']."/".$_url;
		echo "<script>location.href='".$next_url."';</script>";
	}

}

$template = "user_userinfo.html.php";
?>
