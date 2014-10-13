{if $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	{if $magic.request.id==""}
	<!-- <form name="form1" method="post" action="" enctype="multipart/form-data" >
	<div class="module_title"><strong>请输入此信息的用户名或ID</strong></div>
	<div class="module_border">
		<div class="l">用户ID：</div>
		<div class="c">
			<input type="text" name="user_id"  class="input_border"  size="20" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			<input type="text" name="username"  class="input_border"  size="20" />
		</div>
	</div>
	<div class="module_submit" >
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
	</form> -->
	{else}
	<div class="module_title"><span id="user_info_menu"> <a href="javascript:void(0)" class="current"  tab="1"  >基本资料</a>  <a href="javascript:void(0)"  tab="2">个人详细资料</a>  <a href="javascript:void(0)" tab="3">房产资料</a>  <a href="javascript:void(0)" tab="4">单位资料</a>  <a href="javascript:void(0)" tab="5">私营业主资料</a>   <a href="javascript:void(0)" tab="6">财务状况</a>   <a href="javascript:void(0)" tab="7">联系方式</a>    <a href="javascript:void(0)" tab="8">配偶资料</a>    <a href="javascript:void(0)" tab="9">教育背景</a><a href="javascript:void(0)" tab="11">其他信息</a></span><strong>添加用户信息</strong></div>
	<form name="form1" method="post" action="" enctype="multipart/form-data" >
	<div id="user_info_menu_tab">
		<!--基本资料 开始-->
		<div id="user_info_menu_1">
			<div class="module_border">
				<div class="l">用户：</div>
				<div class="c">
					{$_A.userinfo_result.username} (ID:{$_A.userinfo_result.user_id})
				</div>
			</div>
			<div class="module_border">
				<div class="l">真实姓名：</div>
				<div class="c">
					{$_A.userinfo_result.realname} 
				</div>
			</div>
			<div class="module_border">
				<div class="l">邮箱：</div>
				<div class="c">
					{$_A.userinfo_result.email} 
				</div>
			</div>
			<div class="module_border">
				<div class="c">
					您可以一起填完了再提交
				</div>
			</div>
		</div>
		<!--基本资料 结束-->
		<!--个人资料 开始-->
		<div id="user_info_menu_2" class="hide">
			<div class="module_border">
				<div class="w">婚姻状况：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=marry&nid=user_marry&value={$_A.userinfo_result.marry}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">子 女：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=child&nid=user_child&value={$_A.userinfo_result.child}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">学 历：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=education&nid=user_education&value={$_A.userinfo_result.education}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">月收入：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=income&nid=user_income&value={$_A.userinfo_result.income}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">社 保：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=shebao&nid=user_shebao&value={$_A.userinfo_result.shebao}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">社保电脑号：</div>
				<div class="c">
					<input type="text" size="30" name="shebaoid" value="{$_A.userinfo_result.shebaoid}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">住房条件：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=housing&nid=user_housing&value={$_A.userinfo_result.housing}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">是否购车：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=car&nid=user_car&value={$_A.userinfo_result.car}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">逾期记录：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=late&nid=user_late&value={$_A.userinfo_result.late}"></script>
				</div>
			</div>
		</div>
		<!--个人资料 开始-->
		<!--房产资料 开始-->
		<div id="user_info_menu_3" class="hide">
			<div class="module_border">
				<div class="w">房产地址：</div>
				<div class="c">
					<input type="text" size="30" name="house_address" value="{$_A.userinfo_result.house_address}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">建筑面积：</div>
				<div class="c">
					<input type="text" size="15" name="house_area" value="{$_A.userinfo_result.house_area}"/> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">建筑年份：</div>
				<div class="c">
					<input type="text" size="15" name="house_year" value="{$_A.userinfo_result.house_year}" onclick="change_picktime()" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">供款状况：</div>
				<div class="c">
					<input type="text" size="15" name="house_status" value="{$_A.userinfo_result.house_status}" /> 元
				</div>
			</div>
			<div class="module_border">
				<div class="w">所有权人1：</div>
				<div class="c">
					<input type="text" size="15" name="house_holder1" value="{$_A.userinfo_result.house_holder1}" /> 产权份额<input type="text" size="15" name="house_right1" value="{$_A.userinfo_result.house_right1}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">所有权人2：</div>
				<div class="c">
					<input type="text" size="15" name="house_holder2" value="{$_A.userinfo_result.house_holder2}" /> 产权份额<input type="text" size="15" name="house_right2" value="{$_A.userinfo_result.house_right2}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">若房产尚在按揭中, 请填写：</div>
				<div class="c">
					贷款年限：<input type="text" size="10" name="house_loanyear" value="{$_A.userinfo_result.house_loanyear}" />每月供款<input type="text" size="10" name="house_loanprice" value="{$_A.userinfo_result.house_loanprice}" /> 元
				</div>
			</div>
			<div class="module_border">
				<div class="w">尚欠贷款余额：</div>
				<div class="c">
					<input type="text" size="15" name="house_balance" value="{$_A.userinfo_result.house_balance}" /> 元
				</div>
			</div>
			<div class="module_border">
				<div class="w">按揭银行：</div>
				<div class="c">
					<input type="text" size="15" name="house_bank" value="{$_A.userinfo_result.house_bank}" /> 
				</div>
			</div>
		</div>
		<!--房产资料 结束-->
		<!--单位资料 开始-->
		<div id="user_info_menu_4" class="hide">
			<div class="module_border">
				<div class="w">公司名称：</div>
				<div class="c">
					<input type="text" size="15" name="company_name" value="{$_A.userinfo_result.company_name}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">公司性质：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_type&nid=user_company_type&value={$_A.userinfo_result.company_type}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">公司行业：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_industry&nid=user_company_industry&value={$_A.userinfo_result.company_industry}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">工作级别：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_jibie&nid=user_company_jibie&value={$_A.userinfo_result.company_jibie}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">职 位：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_office&nid=user_company_office&value={$_A.userinfo_result.company_office}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">服务时间：</div>
				<div class="c">
					<input type="text" size="15" name="company_worktime1" value="{$_A.userinfo_result.company_worktime1}" onclick="change_picktime()" />  到 <input type="text" size="15" name="company_worktime2" value="{$_A.userinfo_result.company_worktime2}" onclick="change_picktime()" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">工作年限：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_workyear&nid=user_company_workyear&value={$_A.userinfo_result.company_workyear}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">工作电话：</div>
				<div class="c">
					<input type="text" size="15" name="company_tel" value="{$_A.userinfo_result.company_tel}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">公司地址：</div>
				<div class="c">
					<input type="text" size="15" name="company_address" value="{$_A.userinfo_result.company_address}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">公司网站：</div>
				<div class="c">
					<input type="text" size="15" name="company_weburl" value="{$_A.userinfo_result.company_weburl}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">备注说明：</div>
				<div class="c">
					<textarea  cols="50" rows="6"name="company_reamrk"  >{$_A.userinfo_result.company_reamrk}</textarea>
				</div>
			</div>
		</div>
		<!--单位资料 结束-->
		<!--私营业主资料 开始-->
		<div id="user_info_menu_5" class="hide">
			<div class="module_border">
				<div class="w">私营企业类型：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=private_type&nid=user_company_industry&value={$_A.userinfo_result.private_type}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">成立日期：</div>
				<div class="c">
					<input type="text" size="15" name="private_date" value="{$_A.userinfo_result.private_date}" onclick="change_picktime()"/> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">经营场所：</div>
				<div class="c">
					<input type="text" size="15" name="private_place" value="{$_A.userinfo_result.private_place}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">租金：</div>
				<div class="c">
					<input type="text" size="15" name="private_rent" value="{$_A.userinfo_result.private_rent}" /> 元
				</div>
			</div>
			<div class="module_border">
				<div class="w">租期：</div>
				<div class="c">
					<input type="text" size="15" name="private_term" value="{$_A.userinfo_result.private_term}" /> 月
				</div>
			</div>
			<div class="module_border">
				<div class="w">税务编号：</div>
				<div class="c">
					<input type="text" size="15" name="private_taxid" value="{$_A.userinfo_result.private_commerceid}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">工商登记号：</div>
				<div class="c">
					<input type="text" size="15" name="private_commerceid" value="{$_A.userinfo_result.private_commerceid}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">全年盈利/亏损额：</div>
				<div class="c">
					<input type="text" size="15" name="private_income" value="{$_A.userinfo_result.private_income}" /> 元（年度）
				</div>
			</div>
			<div class="module_border">
				<div class="w">雇员人数：</div>
				<div class="c">
					<input type="text" size="15" name="private_employee" value="{$_A.userinfo_result.private_employee}" /> 人
				</div>
			</div>
		</div>
		<!--私营业主资料 结束-->
		<!--财务状况 开始-->
		<div id="user_info_menu_6" class="hide">
			<div class="module_border">
				<div class="w">每月无抵押贷款还款额：</div>
				<div class="c">
					<input type="text" size="15" name="finance_repayment" value="{$_A.userinfo_result.finance_repayment}" /> 元
				</div>
			</div>
			<div class="module_border">
				<div class="w">自有房产：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=finance_property&nid=user_finance_property&value={$_A.userinfo_result.finance_property}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">每月房屋按揭金额：</div>
				<div class="c">
					<input type="text" size="15" name="finance_amount" value="{$_A.userinfo_result.finance_amount}" /> 元
				</div>
			</div>
			<div class="module_border">
				<div class="w">自有汽车：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=finance_car&nid=user_finance_car&value={$_A.userinfo_result.finance_car}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">每月汽车按揭金额：</div>
				<div class="c">
					<input type="text" size="15" name="finance_caramount" value="{$_A.userinfo_result.finance_caramount}" /> 元
				</div>
			</div>
			<div class="module_border">
				<div class="w">每月信用卡还款金额：</div>
				<div class="c">
					<input type="text" size="15" name="finance_creditcard" value="{$_A.userinfo_result.finance_creditcard}" /> 元
				</div>
			</div>
		</div>
		<!--财务状况 结束-->
		<!--配偶资料 开始-->
		<div id="user_info_menu_7" class="hide">
			<div class="module_border">
				<div class="w">居住地电话：</div>
				<div class="c">
					<input type="text" size="20" name="tel" value="{$_A.userinfo_result.tel}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">手机号码：</div>
				<div class="c">
					<input type="text" size="20" name="phone" value="{$_A.userinfo_result.phone}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">居住所在省市：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=area&area={$_A.userinfo_result.area}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">居住地邮编：</div>
				<div class="c">
					<input type="text" size="20" name="post" value="{$_A.userinfo_result.post}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">现居住地址：</div>
				<div class="c">
					<input type="text" size="20" name="address" value="{$_A.userinfo_result.address}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">第二联系人姓名：</div>
				<div class="c">
					<input type="text" size="20" name="linkman1" value="{$_A.userinfo_result.linkman1}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">第二联系人关系：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=relation1&nid=user_relation&value={$_A.userinfo_result.relation1}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">第二联系人联系电话：</div>
				<div class="c">
					<input type="text" size="20" name="tel1" value="{$_A.userinfo_result.tel1}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">第二联系人联系手机：</div>
				<div class="c">
					<input type="text" size="20" name="phone1" value="{$_A.userinfo_result.phone1}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">第三联系人姓名：</div>
				<div class="c">
					<input type="text" size="20" name="linkman2" value="{$_A.userinfo_result.linkman2}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">第三联系人关系：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=relation2&nid=user_relation&value={$_A.userinfo_result.relation2}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">第三联系人联系电话：</div>
				<div class="c">
					<input type="text" size="20" name="tel2" value="{$_A.userinfo_result.tel2}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">第三联系人联系手机：</div>
				<div class="c">
					<input type="text" size="20" name="phone2" value="{$_A.userinfo_result.phone2}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">第四联系人姓名：</div>
				<div class="c">
					<input type="text" size="20" name="linkman3" value="{$_A.userinfo_result.linkman3}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">第四联系人关系：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=relation3&nid=user_relation&value={$_A.userinfo_result.relation3}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">第四联系人联系电话：</div>
				<div class="c">
					<input type="text" size="20" name="tel3" value="{$_A.userinfo_result.tel3}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">第四联系人联系手机：</div>
				<div class="c">
					<input type="text" size="20" name="phone3" value="{$_A.userinfo_result.phone3}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">MSN：</div>
				<div class="c">
					<input type="text" size="20" name="msn" value="{$_A.userinfo_result.msn}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">QQ：</div>
				<div class="c">
					<input type="text" size="20" name="qq" value="{$_A.userinfo_result.qq}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">旺旺：</div>
				<div class="c">
					<input type="text" size="20" name="wangwang" value="{$_A.userinfo_result.wangwang}" />
				</div>
			</div>
		</div>
		<!--配偶资料 结束-->
		<!--配偶资料 开始-->
		<div id="user_info_menu_8"  class="hide">
			<div class="module_border">
				<div class="l">配偶姓名：</div>
				<div class="c">
					<input type="text" size="20" name="mate_name" value="{$_A.userinfo_result.mate_name}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">每月薪金：</div>
				<div class="c">
					<input type="text" size="20" name="mate_salary" value="{$_A.userinfo_result.mate_salary}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">移动电话：</div>
				<div class="c">
					<input type="text" size="20" name="mate_phone" value="{$_A.userinfo_result.mate_phone}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">单位电话：</div>
				<div class="c">
					<input type="text" size="20" name="mate_tel" value="{$_A.userinfo_result.mate_tel}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">工作单位：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=mate_type&nid=user_company_industry&value={$_A.userinfo_result.mate_type}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="l">职位：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=mate_office&nid=user_company_office&value={$_A.userinfo_result.mate_office}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="l">单位地址：</div>
				<div class="c">
					<input type="text" size="20" name="mate_address" value="{$_A.userinfo_result.mate_address}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">月收入：</div>
				<div class="c">
					<input type="text" size="20" name="mate_income" value="{$_A.userinfo_result.mate_income}" />
				</div>
			</div>
		</div>
		<!--配偶资料 结束-->
		<!--教育背景 开始-->
		<div id="user_info_menu_9"  class="hide">
			<div class="module_border">
				<div class="l">最高学历：</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=education_record&nid=user_education&value={$_A.userinfo_result.education_record}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="l">最高学历学校：</div>
				<div class="c">
					<input type="text" size="20" name="education_school" value="{$_A.userinfo_result.education_school}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">专业：</div>
				<div class="c">
					<input type="text" size="20" name="education_study" value="{$_A.userinfo_result.education_study}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">时间：</div>
				<div class="c">
					<input type="text" size="20" name="education_time1" value="{$_A.userinfo_result.education_time1}" onclick="change_picktime()" /> 到 <input type="text" size="20" name="education_time2" value="{$_A.userinfo_result.education_time2}" onclick="change_picktime()" /> 
				</div>
			</div>
		</div>
		<!--教育背景 结束-->
		<!--工作经历 开始-->
		<div id="user_info_menu_10" class="hide">
			<div class="module_border">
				<div class="l">个人能力：</div>
				<div class="c">
					<textarea rows="7" cols="50" name="ability">{$_A.userinfo_result.ability}</textarea><br />（如电脑能力、组织协调能力或其他） 
				</div>
			</div>
			<div class="module_border">
				<div class="l">个人爱好：</div>
				<div class="c">
					<textarea rows="7" cols="50" name="interest">{$_A.userinfo_result.interest}</textarea><br />（突出自己的个性，工作态度或他人对自己的评价等）
				</div>
			</div>
			<div class="module_border">
				<div class="l">其他说明：</div>
				<div class="c">
					<textarea rows="7" cols="50" name="others">{$_A.userinfo_result.others}</textarea><br />
				</div>
			</div>
		</div>
		<!--工作经历 结束-->
		<!--其他信息 开始-->
		<div id="user_info_menu_11" class="hide">
			<div class="module_border">
				<div class="l">个人能力：</div>
				<div class="c">
					<textarea rows="7" cols="50" name="ability">{$_A.userinfo_result.ability}</textarea><br />（如电脑能力、组织协调能力或其他） 
				</div>
			</div>
			<div class="module_border">
				<div class="l">个人爱好：</div>
				<div class="c">
					<textarea rows="7" cols="50" name="interest">{$_A.userinfo_result.interest}</textarea><br />（突出自己的个性，工作态度或他人对自己的评价等）
				</div>
			</div>
			<div class="module_border">
				<div class="l">其他说明：</div>
				<div class="c">
					<textarea rows="7" cols="50" name="others">{$_A.userinfo_result.others}</textarea><br />
				</div>
			</div>
		</div>
		<!--其他信息 结束-->
	</div>
	<div class="module_submit" >
		<input type="hidden"  name="user_id" value="{$magic.request.id}" />
		<input type="button"  name="tijiao" value="确认提交" onclick="check_form()" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
	</form>
	{/if}
</div>
{literal}
<script type="text/javascript">
change_menu_tab("user_info_menu");
function check_form(){
	document.forms['form1'].elements['tijiao'].disabled=true;
	document.forms['form1'].elements['tijiao'].value="提交中..";
	document.forms['form1'].submit();
}
</script>
{/literal}
<!-- 客户信息列表 开始 -->
{elseif $_A.query_type=="list"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">用户名称</td>
		<td width="" class="main_td">真实姓名</td>
		<td width="" class="main_td">房产资料</td>
		<td width="" class="main_td">单位资料</td>
		<td width="" class="main_td">私营业主资料</td>
		<td width="" class="main_td">财务状况</td>
		<td width="" class="main_td">联系方式</td>
		<td width="" class="main_td">配偶资料</td>
		<td width="" class="main_td">教育背景</td>
		<td width="" class="main_td">其他信息</td>
		<td width="" class="main_td">操作</td>
	</tr>
	{foreach from=$_A.userinfo_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{$item.realname}</td>
		<td class="main_td1" align="center" >{if $item.building_status==1}信息完整{else}信息不完整{/if}</td>
		<td class="main_td1" align="center" >{if $item.company_status==1}信息完整{else}信息不完整{/if}</td>
		<td class="main_td1" align="center" >{if $item.firm_status==1}信息完整{else}信息不完整{/if}</td>
		<td class="main_td1" align="center" >{if $item.finance_status==1}信息完整{else}信息不完整{/if}</td>
		<td class="main_td1" align="center" >{if $item.contact_status==1}信息完整{else}信息不完整{/if}</td>
		<td class="main_td1" align="center" >{if $item.mate_status==1}信息完整{else}信息不完整{/if}</td>
		<td class="main_td1" align="center" >{if $item.edu_status==1}信息完整{else}信息不完整{/if}</td>
		<td class="main_td1" align="center" >{if $item.job_status==1}信息完整{else}信息不完整{/if}</td>
		<td class="main_td1" align="center" ><a href="{$_A.query_url}/new&id={$item.user_id}{$_A.site_url}">修改</a> </td>
	</tr>
		{/foreach}
	<tr>
		<td colspan="15" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
			<input type="button" value="搜索" onclick="sousuo()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="9" class="page">
		{$_A.showpage} 
		</td>
	</tr>
</table>

<!-- 查看有效验证码 开始 -->
{elseif $_A.query_type=="code"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">用户名</td>
		<td width="" class="main_td">接收手机</td>
		<td width="" class="main_td">验证码</td>
		<td width="" class="main_td">有效时间</td>
		<td width="" class="main_td">状态</td>
		<td width="" class="main_td">是否使用</td>
		<td width="" class="main_td">类型</td>
	</tr>
	{foreach from=$_A.code_result key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{$item.phone}</td>
		<td class="main_td1" align="center">{$item.code}</td>
		<td class="main_td1" align="center">{$item.lasttime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center">{if $item.lasttime>$time}未过期{else}<font color="red">已过期</font>{/if}</td>
		<td class="main_td1" align="center">{if $item.isuse==1}已使用{else}未使用{/if}</td>
		<td class="main_td1" align="center">{if $item.itype==1}提现验证码{elseif $item.itype==2}添加更改银行账户{elseif $item.itype==3}手机认证{elseif $item.itype==4}重置密码{/if}</td>
	</tr>
	{/foreach}
	<tr>
	<td colspan="8" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
			手机号：<input type="text" name="phone" size="12" id="phone" value="{$magic.request.phone|urldecode}"/>
			<input type="button" value="搜索" onclick="sousuo()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="8" class="page">
		{$_A.showpage} 
		</td>
	</tr>
</table>
<!-- 查看有效验证码 结束 -->

<!-- 发送站内信 开始 -->
{elseif $_A.query_type=="send_message"}
<form method="post" action="" name="form1">
<div class="module_border">
	<div class="l">收件人：</div>
	<div class="c">
		<input type="text" name="username" id="username" style="width:200px" />用户名之间用分号隔开
	</div>
</div>
<div class="module_border">
	<div class="l">标题：</div>
	<div class="c">
		<input type="text" name="title" id="title" />
	</div>
</div>
<div class="module_border">
	<div class="l">内容：</div>
	<div class="c">
		<textarea name="content" id="content" rows="5" cols="30"></textarea>
	</div>
</div>
<div class="module_border" style="text-align:center">
	<input type="button" value="发送" name="sub" onclick="sub_form()" />
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var username = $("#username").val();
	var title = $("#title").val();
	var content = $("#content").val();
	if(username==""){
		alert("收件人不能为空");return;
	}
	if(title==""){
		alert("标题不能为空");return;
	}
	if(content==""){
		alert("内容不能为空");return;
	}
	document.forms['form1'].submit();
	document.forms['form1'].elements['sub'].disabled=true;
}
</script>
{/literal}

<!-- 发送短信 开始 -->
{elseif $_A.query_type=="send_phone"}
<form method="post" action="" name="form1">
<div class="module_border">
	<div class="l">手机号码：</div>
	<div class="c">
		<input type="text" name="phone" id="phone" style="width:200px" />
	</div>
</div>
<div class="module_border">
	<div class="l">内容：</div>
	<div class="c">
		<textarea name="content" id="content" rows="5" cols="30"></textarea>
	</div>
</div>
<div class="module_border" style="text-align:center">
	<input type="button" value="发送" name="sub" onclick="sub_form()" />
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var username = $("#phone").val();
	var content = $("#content").val();
	if(username==""){
		alert("手机号码不能为空");return;
	}
	if(content==""){
		alert("内容不能为空");return;
	}
	document.forms['form1'].submit();
	document.forms['form1'].elements['sub'].disabled=true;
}
</script>
{/literal}

<!-- 发送邮件 开始 -->
{elseif $_A.query_type=="send_email"}
<form method="post" action="" name="form1">
<div class="module_border">
	<div class="l">用户名：</div>
	<div class="c">
		<input type="text" name="username" id="username" style="width:200px" />
	</div>
</div>
<div class="module_border">
	<div class="l">标题：</div>
	<div class="c">
		<input type="text" name="title" id="title" />
	</div>
</div>
<div class="module_border">
	<div class="l">内容：</div>
	<div class="c">
		<textarea name="content" id="content" rows="5" cols="30"></textarea>
	</div>
</div>
<div class="module_border" style="text-align:center">
	<input type="button" value="发送" name="sub" onclick="sub_form()" />
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var username = $("#username").val();
	var title = $("#title").val();
	var content = $("#content").val();
	if(username==""){
		alert("收件人不能为空");return;
	}
	if(title==""){
		alert("标题不能为空");return;
	}
	if(content==""){
		alert("内容不能为空");return;
	}
	document.forms['form1'].submit();
	document.forms['form1'].elements['sub'].disabled=true;
}
</script>
{/literal}
<!-- 发送站内信 开始 -->
{elseif $_A.query_type=="infoconf"}
<form method="post" action="" name="form1">
<div class="module_title"><strong>个人基本信息</strong></div>
<div class="module_border">
	<div class="l">曾用名：</div>
	<div class="c">
		<input type="checkbox" name="used_name" value="1" {if $_A.infoconf_result.used_name==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">婚姻状况：</div>
	<div class="c">
		<input type="checkbox" name="marry" value="1" {if $_A.infoconf_result.marry==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">子女状况：</div>
	<div class="c">
		<input type="checkbox" name="child" value="1" {if $_A.infoconf_result.child==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">社保：</div>
	<div class="c">
		<input type="checkbox" name="shebao" value="1" {if $_A.infoconf_result.shebao==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">社保电脑号：</div>
	<div class="c">
		<input type="checkbox" name="shebaoid" value="1" {if $_A.infoconf_result.shebaoid==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">居住性质：</div>
	<div class="c">
		<input type="checkbox" name="housing" value="1" {if $_A.infoconf_result.housing==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">是否购车：</div>
	<div class="c">
		<input type="checkbox" name="car" value="1" {if $_A.infoconf_result.car==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">逾期记录：</div>
	<div class="c">
		<input type="checkbox" name="late" value="1" {if $_A.infoconf_result.late==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_title"><strong>单位资料</strong></div>
<div class="module_border">
	<div class="l">工作单位名称：</div>
	<div class="c">
		<input type="checkbox" name="company_name" value="1" {if $_A.infoconf_result.company_name==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">单位性质：</div>
	<div class="c">
		<input type="checkbox" name="company_type" value="1" {if $_A.infoconf_result.company_type==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">单位行业：</div>
	<div class="c">
		<input type="checkbox" name="company_industry" value="1" {if $_A.infoconf_result.company_industry==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">所在部门：</div>
	<div class="c">
		<input type="checkbox" name="work_department" value="1" {if $_A.infoconf_result.work_department==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">工作级别：</div>
	<div class="c">
		<input type="checkbox" name="company_jibie" value="1" {if $_A.infoconf_result.company_jibie==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">职位：</div>
	<div class="c">
		<input type="checkbox" name="company_office" value="1" {if $_A.infoconf_result.company_office==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">单位地址：</div>
	<div class="c">
		<input type="checkbox" name="company_address" value="1" {if $_A.infoconf_result.company_address==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">公司网站：</div>
	<div class="c">
		<input type="checkbox" name="company_weburl" value="1" {if $_A.infoconf_result.company_weburl==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">单位电话：</div>
	<div class="c">
		<input type="checkbox" name="company_tel" value="1" {if $_A.infoconf_result.company_tel==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">工作年限：</div>
	<div class="c">
		<input type="checkbox" name="company_workyear" value="1" {if $_A.infoconf_result.company_workyear==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">开始服务时间：</div>
	<div class="c">
		<input type="checkbox" name="company_worktime1" value="1" {if $_A.infoconf_result.company_worktime1==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">结束服务时间：</div>
	<div class="c">
		<input type="checkbox" name="company_worktime2" value="1" {if $_A.infoconf_result.company_worktime2==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">月工资收入：</div>
	<div class="c">
		<input type="checkbox" name="income" value="1" {if $_A.infoconf_result.income==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">月其他收入：</div>
	<div class="c">
		<input type="checkbox" name="other_income" value="1" {if $_A.infoconf_result.other_income==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">公司备注说明：</div>
	<div class="c">
		<input type="checkbox" name="company_reamrk" value="1" {if $_A.infoconf_result.company_reamrk==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_title"><strong>私营业主</strong></div>
<div class="module_border">
	<div class="l">公司全称：</div>
	<div class="c">
		<input type="checkbox" name="private_company_name" value="1" {if $_A.infoconf_result.private_company_name==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">企业类型：</div>
	<div class="c">
		<input type="checkbox" name="private_type" value="1" {if $_A.infoconf_result.private_type==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">成立时间：</div>
	<div class="c">
		<input type="checkbox" name="private_date" value="1" {if $_A.infoconf_result.private_date==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">注册资本：</div>
	<div class="c">
		<input type="checkbox" name="private_capital" value="1" {if $_A.infoconf_result.private_capital==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">法人姓名：</div>
	<div class="c">
		<input type="checkbox" name="private_representative" value="1" {if $_A.infoconf_result.private_representative==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">法人手机号码：</div>
	<div class="c">
		<input type="checkbox" name="private_phone" value="1" {if $_A.infoconf_result.private_phone==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">申请人占股比例：</div>
	<div class="c">
		<input type="checkbox" name="private_stock_rate" value="1" {if $_A.infoconf_result.private_stock_rate==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">公司地址：</div>
	<div class="c">
		<input type="checkbox" name="private_place" value="1" {if $_A.infoconf_result.private_place==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">营业执照号码：</div>
	<div class="c">
		<input type="checkbox" name="private_commerceid" value="1" {if $_A.infoconf_result.private_commerceid==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">税务登记证：</div>
	<div class="c">
		<input type="checkbox" name="private_taxid" value="1" {if $_A.infoconf_result.private_taxid==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">经营场所性质：</div>
	<div class="c">
		<input type="checkbox" name="private_place_type" value="1" {if $_A.infoconf_result.private_place_type==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">公司经营场所每月租金：</div>
	<div class="c">
		<input type="checkbox" name="private_rent" value="1" {if $_A.infoconf_result.private_rent==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">公司经营场所租期：</div>
	<div class="c">
		<input type="checkbox" name="private_term" value="1" {if $_A.infoconf_result.private_term==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">员工人数：</div>
	<div class="c">
		<input type="checkbox" name="private_employee" value="1" {if $_A.infoconf_result.private_employee==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">公司年营业额：</div>
	<div class="c">
		<input type="checkbox" name="private_year_sales" value="1" {if $_A.infoconf_result.private_year_sales==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">公司年净利润：</div>
	<div class="c">
		<input type="checkbox" name="private_income" value="1" {if $_A.infoconf_result.private_income==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">企业简单介绍：</div>
	<div class="c">
		<input type="checkbox" name="private_introduce" value="1" {if $_A.infoconf_result.private_introduce==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_title"><strong>财产状况</strong></div>
<div class="module_border">
	<div class="l">房产地址：</div>
	<div class="c">
		<input type="checkbox" name="house_address" value="1" {if $_A.infoconf_result.house_address==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">建筑面积：</div>
	<div class="c">
		<input type="checkbox" name="house_area" value="1" {if $_A.infoconf_result.house_area==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">建筑年份：</div>
	<div class="c">
		<input type="checkbox" name="house_year" value="1" {if $_A.infoconf_result.house_year==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">房产价值：</div>
	<div class="c">
		<input type="checkbox" name="house_status" value="1" {if $_A.infoconf_result.house_status==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">贷款年限：</div>
	<div class="c">
		<input type="checkbox" name="house_loanyear" value="1" {if $_A.infoconf_result.house_loanyear==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">每月供款：</div>
	<div class="c">
		<input type="checkbox" name="house_loanprice" value="1" {if $_A.infoconf_result.house_loanprice==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">尚欠贷款余额：</div>
	<div class="c">
		<input type="checkbox" name="house_balance" value="1" {if $_A.infoconf_result.house_balance==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">按揭银行：</div>
	<div class="c">
		<input type="checkbox" name="house_bank" value="1" {if $_A.infoconf_result.house_bank==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">车辆品牌：</div>
	<div class="c">
		<input type="checkbox" name="car_brand" value="1" {if $_A.infoconf_result.car_brand==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">车辆价值：</div>
	<div class="c">
		<input type="checkbox" name="car_value" value="1" {if $_A.infoconf_result.car_value==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">车辆购买时间：</div>
	<div class="c">
		<input type="checkbox" name="car_buy_time" value="1" {if $_A.infoconf_result.car_buy_time==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">购车按揭金额：</div>
	<div class="c">
		<input type="checkbox" name="car_loan" value="1" {if $_A.infoconf_result.car_loan==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">购车每月还款额：</div>
	<div class="c">
		<input type="checkbox" name="car_month_loan" value="1" {if $_A.infoconf_result.car_month_loan==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">剩余还款年限：</div>
	<div class="c">
		<input type="checkbox" name="car_loan_year" value="1" {if $_A.infoconf_result.car_loan_year==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_title"><strong>财务状况</strong></div>
<div class="module_border">
	<div class="l">每月无抵押贷款还款额：</div>
	<div class="c">
		<input type="checkbox" name="finance_repayment" value="1" {if $_A.infoconf_result.finance_repayment==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">每月信用卡还款金额：</div>
	<div class="c">
		<input type="checkbox" name="finance_creditcard" value="1" {if $_A.infoconf_result.finance_creditcard==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">其他居住方式每月支出：</div>
	<div class="c">
		<input type="checkbox" name="finance_other_live_cost" value="1" {if $_A.infoconf_result.finance_other_live_cost==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">每月生活费用支出：</div>
	<div class="c">
		<input type="checkbox" name="finance_living_spend" value="1" {if $_A.infoconf_result.finance_living_spend==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_title"><strong>联系方式</strong></div>
<div class="module_border">
	<div class="l">居住地电话：</div>
	<div class="c">
		<input type="checkbox" name="tel" value="1" {if $_A.infoconf_result.tel==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">居住地邮编：</div>
	<div class="c">
		<input type="checkbox" name="post" value="1" {if $_A.infoconf_result.post==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">现居住地址：</div>
	<div class="c">
		<input type="checkbox" name="address" value="1" {if $_A.infoconf_result.address==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">QQ：</div>
	<div class="c">
		<input type="checkbox" name="qq" value="1" {if $_A.infoconf_result.qq==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">MSN：</div>
	<div class="c">
		<input type="checkbox" name="msn" value="1" {if $_A.infoconf_result.msn==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">其他网络联系方式及用户名：</div>
	<div class="c">
		<input type="checkbox" name="other_net_contact" value="1" {if $_A.infoconf_result.other_net_contact==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">现居住地址：</div>
	<div class="c">
		<input type="checkbox" name="address" value="1" {if $_A.infoconf_result.address==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">第一联系人姓名：</div>
	<div class="c">
		<input type="checkbox" name="linkman1" value="1" {if $_A.infoconf_result.linkman1==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">第一联系人关系：</div>
	<div class="c">
		<input type="checkbox" name="relation1" value="1" {if $_A.infoconf_result.relation1==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">第一联系人电话：</div>
	<div class="c">
		<input type="checkbox" name="tel1" value="1" {if $_A.infoconf_result.tel1==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">第一联系人手机：</div>
	<div class="c">
		<input type="checkbox" name="phone1" value="1" {if $_A.infoconf_result.phone1==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">第二联系人姓名：</div>
	<div class="c">
		<input type="checkbox" name="linkman2" value="1" {if $_A.infoconf_result.linkman2==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">第二联系人关系：</div>
	<div class="c">
		<input type="checkbox" name="relation2" value="1" {if $_A.infoconf_result.relation2==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">第二联系人电话：</div>
	<div class="c">
		<input type="checkbox" name="tel2" value="1" {if $_A.infoconf_result.tel2==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">第二联系人手机：</div>
	<div class="c">
		<input type="checkbox" name="phone2" value="1" {if $_A.infoconf_result.phone2==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_title"><strong>配偶资料</strong></div>
<div class="module_border">
	<div class="l">配偶姓名：</div>
	<div class="c">
		<input type="checkbox" name="mate_name" value="1" {if $_A.infoconf_result.mate_name==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">每月薪金：</div>
	<div class="c">
		<input type="checkbox" name="mate_salary" value="1" {if $_A.infoconf_result.mate_salary==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">移动电话：</div>
	<div class="c">
		<input type="checkbox" name="mate_phone" value="1" {if $_A.infoconf_result.mate_phone==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">单位电话：</div>
	<div class="c">
		<input type="checkbox" name="mate_tel" value="1" {if $_A.infoconf_result.mate_tel==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">工作单位：</div>
	<div class="c">
		<input type="checkbox" name="mate_company" value="1" {if $_A.infoconf_result.mate_company==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">工作单位类型：</div>
	<div class="c">
		<input type="checkbox" name="mate_type" value="1" {if $_A.infoconf_result.mate_type==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">职位：</div>
	<div class="c">
		<input type="checkbox" name="mate_office" value="1" {if $_A.infoconf_result.mate_office==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">单位地址：</div>
	<div class="c">
		<input type="checkbox" name="mate_address" value="1" {if $_A.infoconf_result.mate_address==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">配偶月收入：</div>
	<div class="c">
		<input type="checkbox" name="mate_income" value="1" {if $_A.infoconf_result.mate_income==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_title"><strong>教育背景</strong></div>
<div class="module_border">
	<div class="l">最高学历：</div>
	<div class="c">
		<input type="checkbox" name="education_record" value="1" {if $_A.infoconf_result.education_record==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">毕业院校：</div>
	<div class="c">
		<input type="checkbox" name="education_school" value="1" {if $_A.infoconf_result.education_school==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">专业：</div>
	<div class="c">
		<input type="checkbox" name="education_study" value="1" {if $_A.infoconf_result.education_study==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_title"><strong>其他信息</strong></div>
<div class="module_border">
	<div class="l">个人能力：</div>
	<div class="c">
		<input type="checkbox" name="ability" value="1" {if $_A.infoconf_result.ability==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">个人爱好：</div>
	<div class="c">
		<input type="checkbox" name="interest" value="1" {if $_A.infoconf_result.interest==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border">
	<div class="l">其他说明：</div>
	<div class="c">
		<input type="checkbox" name="others" value="1" {if $_A.infoconf_result.others ==1} checked="checked"{/if} />是否必填
	</div>
</div>
<div class="module_border" style="text-align:center">
	<input type="hidden" value="{$magic.request.usertype}" name="usertype" /> 
	<input type="button" value="提交" name="sub" onclick="sub_form()" />
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var username = $("#username").val();
	var title = $("#title").val();
	var content = $("#content").val();
	if(username==""){
		alert("收件人不能为空");return;
	}
	if(title==""){
		alert("标题不能为空");return;
	}
	if(content==""){
		alert("内容不能为空");return;
	}
	document.forms['form1'].submit();
	document.forms['form1'].elements['sub'].disabled=true;
}
</script>
{/literal}


{/if}
<script type="text/javascript">
var url = '{$_A.query_url}/{$_A.query_type}{$_A.site_url}';
{literal}
function sousuo(){
	var sou = "";
	var username = $("#username").val();
	var status = $("#status").val() || "";
	var phone = $("#phone").val() || "";
	if (username!=""){
		sou += "&username="+username;
	}
	if (status!=""){
		sou += "&status="+status;
	}
	if(phone!=""){
		sou += "&phone="+phone;
	}
	location.href=url+sou;
}
</script>
{/literal}