{if $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	{if $magic.request.id==""}
	<!-- <form name="form1" method="post" action="" enctype="multipart/form-data" >
	<div class="module_title"><strong>���������Ϣ���û�����ID</strong></div>
	<div class="module_border">
		<div class="l">�û�ID��</div>
		<div class="c">
			<input type="text" name="user_id"  class="input_border"  size="20" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<input type="text" name="username"  class="input_border"  size="20" />
		</div>
	</div>
	<div class="module_submit" >
		<input type="submit"  name="submit" value="ȷ���ύ" />
		<input type="reset"  name="reset" value="���ñ�" />
	</div>
	</form> -->
	{else}
	<div class="module_title"><span id="user_info_menu"> <a href="javascript:void(0)" class="current"  tab="1"  >��������</a>  <a href="javascript:void(0)"  tab="2">������ϸ����</a>  <a href="javascript:void(0)" tab="3">��������</a>  <a href="javascript:void(0)" tab="4">��λ����</a>  <a href="javascript:void(0)" tab="5">˽Ӫҵ������</a>   <a href="javascript:void(0)" tab="6">����״��</a>   <a href="javascript:void(0)" tab="7">��ϵ��ʽ</a>    <a href="javascript:void(0)" tab="8">��ż����</a>    <a href="javascript:void(0)" tab="9">��������</a><a href="javascript:void(0)" tab="11">������Ϣ</a></span><strong>����û���Ϣ</strong></div>
	<form name="form1" method="post" action="" enctype="multipart/form-data" >
	<div id="user_info_menu_tab">
		<!--�������� ��ʼ-->
		<div id="user_info_menu_1">
			<div class="module_border">
				<div class="l">�û���</div>
				<div class="c">
					{$_A.userinfo_result.username} (ID:{$_A.userinfo_result.user_id})
				</div>
			</div>
			<div class="module_border">
				<div class="l">��ʵ������</div>
				<div class="c">
					{$_A.userinfo_result.realname} 
				</div>
			</div>
			<div class="module_border">
				<div class="l">���䣺</div>
				<div class="c">
					{$_A.userinfo_result.email} 
				</div>
			</div>
			<div class="module_border">
				<div class="c">
					������һ�����������ύ
				</div>
			</div>
		</div>
		<!--�������� ����-->
		<!--�������� ��ʼ-->
		<div id="user_info_menu_2" class="hide">
			<div class="module_border">
				<div class="w">����״����</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=marry&nid=user_marry&value={$_A.userinfo_result.marry}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">�� Ů��</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=child&nid=user_child&value={$_A.userinfo_result.child}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">ѧ ����</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=education&nid=user_education&value={$_A.userinfo_result.education}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">�����룺</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=income&nid=user_income&value={$_A.userinfo_result.income}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">�� ����</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=shebao&nid=user_shebao&value={$_A.userinfo_result.shebao}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">�籣���Ժţ�</div>
				<div class="c">
					<input type="text" size="30" name="shebaoid" value="{$_A.userinfo_result.shebaoid}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">ס��������</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=housing&nid=user_housing&value={$_A.userinfo_result.housing}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">�Ƿ񹺳���</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=car&nid=user_car&value={$_A.userinfo_result.car}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">���ڼ�¼��</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=late&nid=user_late&value={$_A.userinfo_result.late}"></script>
				</div>
			</div>
		</div>
		<!--�������� ��ʼ-->
		<!--�������� ��ʼ-->
		<div id="user_info_menu_3" class="hide">
			<div class="module_border">
				<div class="w">������ַ��</div>
				<div class="c">
					<input type="text" size="30" name="house_address" value="{$_A.userinfo_result.house_address}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">���������</div>
				<div class="c">
					<input type="text" size="15" name="house_area" value="{$_A.userinfo_result.house_area}"/> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">������ݣ�</div>
				<div class="c">
					<input type="text" size="15" name="house_year" value="{$_A.userinfo_result.house_year}" onclick="change_picktime()" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">����״����</div>
				<div class="c">
					<input type="text" size="15" name="house_status" value="{$_A.userinfo_result.house_status}" /> Ԫ
				</div>
			</div>
			<div class="module_border">
				<div class="w">����Ȩ��1��</div>
				<div class="c">
					<input type="text" size="15" name="house_holder1" value="{$_A.userinfo_result.house_holder1}" /> ��Ȩ�ݶ�<input type="text" size="15" name="house_right1" value="{$_A.userinfo_result.house_right1}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">����Ȩ��2��</div>
				<div class="c">
					<input type="text" size="15" name="house_holder2" value="{$_A.userinfo_result.house_holder2}" /> ��Ȩ�ݶ�<input type="text" size="15" name="house_right2" value="{$_A.userinfo_result.house_right2}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">���������ڰ�����, ����д��</div>
				<div class="c">
					�������ޣ�<input type="text" size="10" name="house_loanyear" value="{$_A.userinfo_result.house_loanyear}" />ÿ�¹���<input type="text" size="10" name="house_loanprice" value="{$_A.userinfo_result.house_loanprice}" /> Ԫ
				</div>
			</div>
			<div class="module_border">
				<div class="w">��Ƿ������</div>
				<div class="c">
					<input type="text" size="15" name="house_balance" value="{$_A.userinfo_result.house_balance}" /> Ԫ
				</div>
			</div>
			<div class="module_border">
				<div class="w">�������У�</div>
				<div class="c">
					<input type="text" size="15" name="house_bank" value="{$_A.userinfo_result.house_bank}" /> 
				</div>
			</div>
		</div>
		<!--�������� ����-->
		<!--��λ���� ��ʼ-->
		<div id="user_info_menu_4" class="hide">
			<div class="module_border">
				<div class="w">��˾���ƣ�</div>
				<div class="c">
					<input type="text" size="15" name="company_name" value="{$_A.userinfo_result.company_name}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">��˾���ʣ�</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_type&nid=user_company_type&value={$_A.userinfo_result.company_type}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">��˾��ҵ��</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_industry&nid=user_company_industry&value={$_A.userinfo_result.company_industry}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">��������</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_jibie&nid=user_company_jibie&value={$_A.userinfo_result.company_jibie}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">ְ λ��</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_office&nid=user_company_office&value={$_A.userinfo_result.company_office}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">����ʱ�䣺</div>
				<div class="c">
					<input type="text" size="15" name="company_worktime1" value="{$_A.userinfo_result.company_worktime1}" onclick="change_picktime()" />  �� <input type="text" size="15" name="company_worktime2" value="{$_A.userinfo_result.company_worktime2}" onclick="change_picktime()" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">�������ޣ�</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=company_workyear&nid=user_company_workyear&value={$_A.userinfo_result.company_workyear}"></script>
				</div>
			</div>
			<div class="module_border">
				<div class="w">�����绰��</div>
				<div class="c">
					<input type="text" size="15" name="company_tel" value="{$_A.userinfo_result.company_tel}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">��˾��ַ��</div>
				<div class="c">
					<input type="text" size="15" name="company_address" value="{$_A.userinfo_result.company_address}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">��˾��վ��</div>
				<div class="c">
					<input type="text" size="15" name="company_weburl" value="{$_A.userinfo_result.company_weburl}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">��ע˵����</div>
				<div class="c">
					<textarea  cols="50" rows="6"name="company_reamrk"  >{$_A.userinfo_result.company_reamrk}</textarea>
				</div>
			</div>
		</div>
		<!--��λ���� ����-->
		<!--˽Ӫҵ������ ��ʼ-->
		<div id="user_info_menu_5" class="hide">
			<div class="module_border">
				<div class="w">˽Ӫ��ҵ���ͣ�</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=private_type&nid=user_company_industry&value={$_A.userinfo_result.private_type}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">�������ڣ�</div>
				<div class="c">
					<input type="text" size="15" name="private_date" value="{$_A.userinfo_result.private_date}" onclick="change_picktime()"/> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">��Ӫ������</div>
				<div class="c">
					<input type="text" size="15" name="private_place" value="{$_A.userinfo_result.private_place}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">���</div>
				<div class="c">
					<input type="text" size="15" name="private_rent" value="{$_A.userinfo_result.private_rent}" /> Ԫ
				</div>
			</div>
			<div class="module_border">
				<div class="w">���ڣ�</div>
				<div class="c">
					<input type="text" size="15" name="private_term" value="{$_A.userinfo_result.private_term}" /> ��
				</div>
			</div>
			<div class="module_border">
				<div class="w">˰���ţ�</div>
				<div class="c">
					<input type="text" size="15" name="private_taxid" value="{$_A.userinfo_result.private_commerceid}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">���̵ǼǺţ�</div>
				<div class="c">
					<input type="text" size="15" name="private_commerceid" value="{$_A.userinfo_result.private_commerceid}" /> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">ȫ��ӯ��/����</div>
				<div class="c">
					<input type="text" size="15" name="private_income" value="{$_A.userinfo_result.private_income}" /> Ԫ����ȣ�
				</div>
			</div>
			<div class="module_border">
				<div class="w">��Ա������</div>
				<div class="c">
					<input type="text" size="15" name="private_employee" value="{$_A.userinfo_result.private_employee}" /> ��
				</div>
			</div>
		</div>
		<!--˽Ӫҵ������ ����-->
		<!--����״�� ��ʼ-->
		<div id="user_info_menu_6" class="hide">
			<div class="module_border">
				<div class="w">ÿ���޵�Ѻ�����</div>
				<div class="c">
					<input type="text" size="15" name="finance_repayment" value="{$_A.userinfo_result.finance_repayment}" /> Ԫ
				</div>
			</div>
			<div class="module_border">
				<div class="w">���з�����</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=finance_property&nid=user_finance_property&value={$_A.userinfo_result.finance_property}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">ÿ�·��ݰ��ҽ�</div>
				<div class="c">
					<input type="text" size="15" name="finance_amount" value="{$_A.userinfo_result.finance_amount}" /> Ԫ
				</div>
			</div>
			<div class="module_border">
				<div class="w">����������</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=finance_car&nid=user_finance_car&value={$_A.userinfo_result.finance_car}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">ÿ���������ҽ�</div>
				<div class="c">
					<input type="text" size="15" name="finance_caramount" value="{$_A.userinfo_result.finance_caramount}" /> Ԫ
				</div>
			</div>
			<div class="module_border">
				<div class="w">ÿ�����ÿ������</div>
				<div class="c">
					<input type="text" size="15" name="finance_creditcard" value="{$_A.userinfo_result.finance_creditcard}" /> Ԫ
				</div>
			</div>
		</div>
		<!--����״�� ����-->
		<!--��ż���� ��ʼ-->
		<div id="user_info_menu_7" class="hide">
			<div class="module_border">
				<div class="w">��ס�ص绰��</div>
				<div class="c">
					<input type="text" size="20" name="tel" value="{$_A.userinfo_result.tel}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">�ֻ����룺</div>
				<div class="c">
					<input type="text" size="20" name="phone" value="{$_A.userinfo_result.phone}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">��ס����ʡ�У�</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=area&area={$_A.userinfo_result.area}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">��ס���ʱࣺ</div>
				<div class="c">
					<input type="text" size="20" name="post" value="{$_A.userinfo_result.post}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">�־�ס��ַ��</div>
				<div class="c">
					<input type="text" size="20" name="address" value="{$_A.userinfo_result.address}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">�ڶ���ϵ��������</div>
				<div class="c">
					<input type="text" size="20" name="linkman1" value="{$_A.userinfo_result.linkman1}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">�ڶ���ϵ�˹�ϵ��</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=relation1&nid=user_relation&value={$_A.userinfo_result.relation1}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">�ڶ���ϵ����ϵ�绰��</div>
				<div class="c">
					<input type="text" size="20" name="tel1" value="{$_A.userinfo_result.tel1}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">�ڶ���ϵ����ϵ�ֻ���</div>
				<div class="c">
					<input type="text" size="20" name="phone1" value="{$_A.userinfo_result.phone1}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">������ϵ��������</div>
				<div class="c">
					<input type="text" size="20" name="linkman2" value="{$_A.userinfo_result.linkman2}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">������ϵ�˹�ϵ��</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=relation2&nid=user_relation&value={$_A.userinfo_result.relation2}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">������ϵ����ϵ�绰��</div>
				<div class="c">
					<input type="text" size="20" name="tel2" value="{$_A.userinfo_result.tel2}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">������ϵ����ϵ�ֻ���</div>
				<div class="c">
					<input type="text" size="20" name="phone2" value="{$_A.userinfo_result.phone2}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">������ϵ��������</div>
				<div class="c">
					<input type="text" size="20" name="linkman3" value="{$_A.userinfo_result.linkman3}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">������ϵ�˹�ϵ��</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=relation3&nid=user_relation&value={$_A.userinfo_result.relation3}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="w">������ϵ����ϵ�绰��</div>
				<div class="c">
					<input type="text" size="20" name="tel3" value="{$_A.userinfo_result.tel3}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">������ϵ����ϵ�ֻ���</div>
				<div class="c">
					<input type="text" size="20" name="phone3" value="{$_A.userinfo_result.phone3}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">MSN��</div>
				<div class="c">
					<input type="text" size="20" name="msn" value="{$_A.userinfo_result.msn}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">QQ��</div>
				<div class="c">
					<input type="text" size="20" name="qq" value="{$_A.userinfo_result.qq}" />
				</div>
			</div>
			<div class="module_border">
				<div class="w">������</div>
				<div class="c">
					<input type="text" size="20" name="wangwang" value="{$_A.userinfo_result.wangwang}" />
				</div>
			</div>
		</div>
		<!--��ż���� ����-->
		<!--��ż���� ��ʼ-->
		<div id="user_info_menu_8"  class="hide">
			<div class="module_border">
				<div class="l">��ż������</div>
				<div class="c">
					<input type="text" size="20" name="mate_name" value="{$_A.userinfo_result.mate_name}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">ÿ��н��</div>
				<div class="c">
					<input type="text" size="20" name="mate_salary" value="{$_A.userinfo_result.mate_salary}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">�ƶ��绰��</div>
				<div class="c">
					<input type="text" size="20" name="mate_phone" value="{$_A.userinfo_result.mate_phone}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">��λ�绰��</div>
				<div class="c">
					<input type="text" size="20" name="mate_tel" value="{$_A.userinfo_result.mate_tel}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">������λ��</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=mate_type&nid=user_company_industry&value={$_A.userinfo_result.mate_type}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="l">ְλ��</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=mate_office&nid=user_company_office&value={$_A.userinfo_result.mate_office}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="l">��λ��ַ��</div>
				<div class="c">
					<input type="text" size="20" name="mate_address" value="{$_A.userinfo_result.mate_address}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">�����룺</div>
				<div class="c">
					<input type="text" size="20" name="mate_income" value="{$_A.userinfo_result.mate_income}" />
				</div>
			</div>
		</div>
		<!--��ż���� ����-->
		<!--�������� ��ʼ-->
		<div id="user_info_menu_9"  class="hide">
			<div class="module_border">
				<div class="l">���ѧ����</div>
				<div class="c">
					<script type="text/javascript" src="/plugins/index.php?q=linkage&name=education_record&nid=user_education&value={$_A.userinfo_result.education_record}"></script> 
				</div>
			</div>
			<div class="module_border">
				<div class="l">���ѧ��ѧУ��</div>
				<div class="c">
					<input type="text" size="20" name="education_school" value="{$_A.userinfo_result.education_school}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">רҵ��</div>
				<div class="c">
					<input type="text" size="20" name="education_study" value="{$_A.userinfo_result.education_study}" />
				</div>
			</div>
			<div class="module_border">
				<div class="l">ʱ�䣺</div>
				<div class="c">
					<input type="text" size="20" name="education_time1" value="{$_A.userinfo_result.education_time1}" onclick="change_picktime()" /> �� <input type="text" size="20" name="education_time2" value="{$_A.userinfo_result.education_time2}" onclick="change_picktime()" /> 
				</div>
			</div>
		</div>
		<!--�������� ����-->
		<!--�������� ��ʼ-->
		<div id="user_info_menu_10" class="hide">
			<div class="module_border">
				<div class="l">����������</div>
				<div class="c">
					<textarea rows="7" cols="50" name="ability">{$_A.userinfo_result.ability}</textarea><br />���������������֯Э�������������� 
				</div>
			</div>
			<div class="module_border">
				<div class="l">���˰��ã�</div>
				<div class="c">
					<textarea rows="7" cols="50" name="interest">{$_A.userinfo_result.interest}</textarea><br />��ͻ���Լ��ĸ��ԣ�����̬�Ȼ����˶��Լ������۵ȣ�
				</div>
			</div>
			<div class="module_border">
				<div class="l">����˵����</div>
				<div class="c">
					<textarea rows="7" cols="50" name="others">{$_A.userinfo_result.others}</textarea><br />
				</div>
			</div>
		</div>
		<!--�������� ����-->
		<!--������Ϣ ��ʼ-->
		<div id="user_info_menu_11" class="hide">
			<div class="module_border">
				<div class="l">����������</div>
				<div class="c">
					<textarea rows="7" cols="50" name="ability">{$_A.userinfo_result.ability}</textarea><br />���������������֯Э�������������� 
				</div>
			</div>
			<div class="module_border">
				<div class="l">���˰��ã�</div>
				<div class="c">
					<textarea rows="7" cols="50" name="interest">{$_A.userinfo_result.interest}</textarea><br />��ͻ���Լ��ĸ��ԣ�����̬�Ȼ����˶��Լ������۵ȣ�
				</div>
			</div>
			<div class="module_border">
				<div class="l">����˵����</div>
				<div class="c">
					<textarea rows="7" cols="50" name="others">{$_A.userinfo_result.others}</textarea><br />
				</div>
			</div>
		</div>
		<!--������Ϣ ����-->
	</div>
	<div class="module_submit" >
		<input type="hidden"  name="user_id" value="{$magic.request.id}" />
		<input type="button"  name="tijiao" value="ȷ���ύ" onclick="check_form()" />
		<input type="reset"  name="reset" value="���ñ�" />
	</div>
	</form>
	{/if}
</div>
{literal}
<script type="text/javascript">
change_menu_tab("user_info_menu");
function check_form(){
	document.forms['form1'].elements['tijiao'].disabled=true;
	document.forms['form1'].elements['tijiao'].value="�ύ��..";
	document.forms['form1'].submit();
}
</script>
{/literal}
<!-- �ͻ���Ϣ�б� ��ʼ -->
{elseif $_A.query_type=="list"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">�û�����</td>
		<td width="" class="main_td">��ʵ����</td>
		<td width="" class="main_td">��������</td>
		<td width="" class="main_td">��λ����</td>
		<td width="" class="main_td">˽Ӫҵ������</td>
		<td width="" class="main_td">����״��</td>
		<td width="" class="main_td">��ϵ��ʽ</td>
		<td width="" class="main_td">��ż����</td>
		<td width="" class="main_td">��������</td>
		<td width="" class="main_td">������Ϣ</td>
		<td width="" class="main_td">����</td>
	</tr>
	{foreach from=$_A.userinfo_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{$item.realname}</td>
		<td class="main_td1" align="center" >{if $item.building_status==1}��Ϣ����{else}��Ϣ������{/if}</td>
		<td class="main_td1" align="center" >{if $item.company_status==1}��Ϣ����{else}��Ϣ������{/if}</td>
		<td class="main_td1" align="center" >{if $item.firm_status==1}��Ϣ����{else}��Ϣ������{/if}</td>
		<td class="main_td1" align="center" >{if $item.finance_status==1}��Ϣ����{else}��Ϣ������{/if}</td>
		<td class="main_td1" align="center" >{if $item.contact_status==1}��Ϣ����{else}��Ϣ������{/if}</td>
		<td class="main_td1" align="center" >{if $item.mate_status==1}��Ϣ����{else}��Ϣ������{/if}</td>
		<td class="main_td1" align="center" >{if $item.edu_status==1}��Ϣ����{else}��Ϣ������{/if}</td>
		<td class="main_td1" align="center" >{if $item.job_status==1}��Ϣ����{else}��Ϣ������{/if}</td>
		<td class="main_td1" align="center" ><a href="{$_A.query_url}/new&id={$item.user_id}{$_A.site_url}">�޸�</a> </td>
	</tr>
		{/foreach}
	<tr>
		<td colspan="15" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
			<input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="9" class="page">
		{$_A.showpage} 
		</td>
	</tr>
</table>

<!-- �鿴��Ч��֤�� ��ʼ -->
{elseif $_A.query_type=="code"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">�û���</td>
		<td width="" class="main_td">�����ֻ�</td>
		<td width="" class="main_td">��֤��</td>
		<td width="" class="main_td">��Чʱ��</td>
		<td width="" class="main_td">״̬</td>
		<td width="" class="main_td">�Ƿ�ʹ��</td>
		<td width="" class="main_td">����</td>
	</tr>
	{foreach from=$_A.code_result key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{$item.phone}</td>
		<td class="main_td1" align="center">{$item.code}</td>
		<td class="main_td1" align="center">{$item.lasttime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center">{if $item.lasttime>$time}δ����{else}<font color="red">�ѹ���</font>{/if}</td>
		<td class="main_td1" align="center">{if $item.isuse==1}��ʹ��{else}δʹ��{/if}</td>
		<td class="main_td1" align="center">{if $item.itype==1}������֤��{elseif $item.itype==2}��Ӹ��������˻�{elseif $item.itype==3}�ֻ���֤{elseif $item.itype==4}��������{/if}</td>
	</tr>
	{/foreach}
	<tr>
	<td colspan="8" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
			�ֻ��ţ�<input type="text" name="phone" size="12" id="phone" value="{$magic.request.phone|urldecode}"/>
			<input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="8" class="page">
		{$_A.showpage} 
		</td>
	</tr>
</table>
<!-- �鿴��Ч��֤�� ���� -->

<!-- ����վ���� ��ʼ -->
{elseif $_A.query_type=="send_message"}
<form method="post" action="" name="form1">
<div class="module_border">
	<div class="l">�ռ��ˣ�</div>
	<div class="c">
		<input type="text" name="username" id="username" style="width:200px" />�û���֮���÷ֺŸ���
	</div>
</div>
<div class="module_border">
	<div class="l">���⣺</div>
	<div class="c">
		<input type="text" name="title" id="title" />
	</div>
</div>
<div class="module_border">
	<div class="l">���ݣ�</div>
	<div class="c">
		<textarea name="content" id="content" rows="5" cols="30"></textarea>
	</div>
</div>
<div class="module_border" style="text-align:center">
	<input type="button" value="����" name="sub" onclick="sub_form()" />
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var username = $("#username").val();
	var title = $("#title").val();
	var content = $("#content").val();
	if(username==""){
		alert("�ռ��˲���Ϊ��");return;
	}
	if(title==""){
		alert("���ⲻ��Ϊ��");return;
	}
	if(content==""){
		alert("���ݲ���Ϊ��");return;
	}
	document.forms['form1'].submit();
	document.forms['form1'].elements['sub'].disabled=true;
}
</script>
{/literal}

<!-- ���Ͷ��� ��ʼ -->
{elseif $_A.query_type=="send_phone"}
<form method="post" action="" name="form1">
<div class="module_border">
	<div class="l">�ֻ����룺</div>
	<div class="c">
		<input type="text" name="phone" id="phone" style="width:200px" />
	</div>
</div>
<div class="module_border">
	<div class="l">���ݣ�</div>
	<div class="c">
		<textarea name="content" id="content" rows="5" cols="30"></textarea>
	</div>
</div>
<div class="module_border" style="text-align:center">
	<input type="button" value="����" name="sub" onclick="sub_form()" />
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var username = $("#phone").val();
	var content = $("#content").val();
	if(username==""){
		alert("�ֻ����벻��Ϊ��");return;
	}
	if(content==""){
		alert("���ݲ���Ϊ��");return;
	}
	document.forms['form1'].submit();
	document.forms['form1'].elements['sub'].disabled=true;
}
</script>
{/literal}

<!-- �����ʼ� ��ʼ -->
{elseif $_A.query_type=="send_email"}
<form method="post" action="" name="form1">
<div class="module_border">
	<div class="l">�û�����</div>
	<div class="c">
		<input type="text" name="username" id="username" style="width:200px" />
	</div>
</div>
<div class="module_border">
	<div class="l">���⣺</div>
	<div class="c">
		<input type="text" name="title" id="title" />
	</div>
</div>
<div class="module_border">
	<div class="l">���ݣ�</div>
	<div class="c">
		<textarea name="content" id="content" rows="5" cols="30"></textarea>
	</div>
</div>
<div class="module_border" style="text-align:center">
	<input type="button" value="����" name="sub" onclick="sub_form()" />
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var username = $("#username").val();
	var title = $("#title").val();
	var content = $("#content").val();
	if(username==""){
		alert("�ռ��˲���Ϊ��");return;
	}
	if(title==""){
		alert("���ⲻ��Ϊ��");return;
	}
	if(content==""){
		alert("���ݲ���Ϊ��");return;
	}
	document.forms['form1'].submit();
	document.forms['form1'].elements['sub'].disabled=true;
}
</script>
{/literal}
<!-- ����վ���� ��ʼ -->
{elseif $_A.query_type=="infoconf"}
<form method="post" action="" name="form1">
<div class="module_title"><strong>���˻�����Ϣ</strong></div>
<div class="module_border">
	<div class="l">��������</div>
	<div class="c">
		<input type="checkbox" name="used_name" value="1" {if $_A.infoconf_result.used_name==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">����״����</div>
	<div class="c">
		<input type="checkbox" name="marry" value="1" {if $_A.infoconf_result.marry==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��Ů״����</div>
	<div class="c">
		<input type="checkbox" name="child" value="1" {if $_A.infoconf_result.child==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�籣��</div>
	<div class="c">
		<input type="checkbox" name="shebao" value="1" {if $_A.infoconf_result.shebao==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�籣���Ժţ�</div>
	<div class="c">
		<input type="checkbox" name="shebaoid" value="1" {if $_A.infoconf_result.shebaoid==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��ס���ʣ�</div>
	<div class="c">
		<input type="checkbox" name="housing" value="1" {if $_A.infoconf_result.housing==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�Ƿ񹺳���</div>
	<div class="c">
		<input type="checkbox" name="car" value="1" {if $_A.infoconf_result.car==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">���ڼ�¼��</div>
	<div class="c">
		<input type="checkbox" name="late" value="1" {if $_A.infoconf_result.late==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_title"><strong>��λ����</strong></div>
<div class="module_border">
	<div class="l">������λ���ƣ�</div>
	<div class="c">
		<input type="checkbox" name="company_name" value="1" {if $_A.infoconf_result.company_name==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��λ���ʣ�</div>
	<div class="c">
		<input type="checkbox" name="company_type" value="1" {if $_A.infoconf_result.company_type==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��λ��ҵ��</div>
	<div class="c">
		<input type="checkbox" name="company_industry" value="1" {if $_A.infoconf_result.company_industry==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">���ڲ��ţ�</div>
	<div class="c">
		<input type="checkbox" name="work_department" value="1" {if $_A.infoconf_result.work_department==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��������</div>
	<div class="c">
		<input type="checkbox" name="company_jibie" value="1" {if $_A.infoconf_result.company_jibie==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">ְλ��</div>
	<div class="c">
		<input type="checkbox" name="company_office" value="1" {if $_A.infoconf_result.company_office==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��λ��ַ��</div>
	<div class="c">
		<input type="checkbox" name="company_address" value="1" {if $_A.infoconf_result.company_address==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��˾��վ��</div>
	<div class="c">
		<input type="checkbox" name="company_weburl" value="1" {if $_A.infoconf_result.company_weburl==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��λ�绰��</div>
	<div class="c">
		<input type="checkbox" name="company_tel" value="1" {if $_A.infoconf_result.company_tel==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�������ޣ�</div>
	<div class="c">
		<input type="checkbox" name="company_workyear" value="1" {if $_A.infoconf_result.company_workyear==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��ʼ����ʱ�䣺</div>
	<div class="c">
		<input type="checkbox" name="company_worktime1" value="1" {if $_A.infoconf_result.company_worktime1==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��������ʱ�䣺</div>
	<div class="c">
		<input type="checkbox" name="company_worktime2" value="1" {if $_A.infoconf_result.company_worktime2==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�¹������룺</div>
	<div class="c">
		<input type="checkbox" name="income" value="1" {if $_A.infoconf_result.income==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">���������룺</div>
	<div class="c">
		<input type="checkbox" name="other_income" value="1" {if $_A.infoconf_result.other_income==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��˾��ע˵����</div>
	<div class="c">
		<input type="checkbox" name="company_reamrk" value="1" {if $_A.infoconf_result.company_reamrk==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_title"><strong>˽Ӫҵ��</strong></div>
<div class="module_border">
	<div class="l">��˾ȫ�ƣ�</div>
	<div class="c">
		<input type="checkbox" name="private_company_name" value="1" {if $_A.infoconf_result.private_company_name==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��ҵ���ͣ�</div>
	<div class="c">
		<input type="checkbox" name="private_type" value="1" {if $_A.infoconf_result.private_type==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">����ʱ�䣺</div>
	<div class="c">
		<input type="checkbox" name="private_date" value="1" {if $_A.infoconf_result.private_date==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">ע���ʱ���</div>
	<div class="c">
		<input type="checkbox" name="private_capital" value="1" {if $_A.infoconf_result.private_capital==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">����������</div>
	<div class="c">
		<input type="checkbox" name="private_representative" value="1" {if $_A.infoconf_result.private_representative==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�����ֻ����룺</div>
	<div class="c">
		<input type="checkbox" name="private_phone" value="1" {if $_A.infoconf_result.private_phone==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">������ռ�ɱ�����</div>
	<div class="c">
		<input type="checkbox" name="private_stock_rate" value="1" {if $_A.infoconf_result.private_stock_rate==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��˾��ַ��</div>
	<div class="c">
		<input type="checkbox" name="private_place" value="1" {if $_A.infoconf_result.private_place==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">Ӫҵִ�պ��룺</div>
	<div class="c">
		<input type="checkbox" name="private_commerceid" value="1" {if $_A.infoconf_result.private_commerceid==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">˰��Ǽ�֤��</div>
	<div class="c">
		<input type="checkbox" name="private_taxid" value="1" {if $_A.infoconf_result.private_taxid==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��Ӫ�������ʣ�</div>
	<div class="c">
		<input type="checkbox" name="private_place_type" value="1" {if $_A.infoconf_result.private_place_type==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��˾��Ӫ����ÿ�����</div>
	<div class="c">
		<input type="checkbox" name="private_rent" value="1" {if $_A.infoconf_result.private_rent==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��˾��Ӫ�������ڣ�</div>
	<div class="c">
		<input type="checkbox" name="private_term" value="1" {if $_A.infoconf_result.private_term==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">Ա��������</div>
	<div class="c">
		<input type="checkbox" name="private_employee" value="1" {if $_A.infoconf_result.private_employee==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��˾��Ӫҵ�</div>
	<div class="c">
		<input type="checkbox" name="private_year_sales" value="1" {if $_A.infoconf_result.private_year_sales==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��˾�꾻����</div>
	<div class="c">
		<input type="checkbox" name="private_income" value="1" {if $_A.infoconf_result.private_income==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��ҵ�򵥽��ܣ�</div>
	<div class="c">
		<input type="checkbox" name="private_introduce" value="1" {if $_A.infoconf_result.private_introduce==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_title"><strong>�Ʋ�״��</strong></div>
<div class="module_border">
	<div class="l">������ַ��</div>
	<div class="c">
		<input type="checkbox" name="house_address" value="1" {if $_A.infoconf_result.house_address==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">���������</div>
	<div class="c">
		<input type="checkbox" name="house_area" value="1" {if $_A.infoconf_result.house_area==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">������ݣ�</div>
	<div class="c">
		<input type="checkbox" name="house_year" value="1" {if $_A.infoconf_result.house_year==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">������ֵ��</div>
	<div class="c">
		<input type="checkbox" name="house_status" value="1" {if $_A.infoconf_result.house_status==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�������ޣ�</div>
	<div class="c">
		<input type="checkbox" name="house_loanyear" value="1" {if $_A.infoconf_result.house_loanyear==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">ÿ�¹��</div>
	<div class="c">
		<input type="checkbox" name="house_loanprice" value="1" {if $_A.infoconf_result.house_loanprice==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��Ƿ������</div>
	<div class="c">
		<input type="checkbox" name="house_balance" value="1" {if $_A.infoconf_result.house_balance==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�������У�</div>
	<div class="c">
		<input type="checkbox" name="house_bank" value="1" {if $_A.infoconf_result.house_bank==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">����Ʒ�ƣ�</div>
	<div class="c">
		<input type="checkbox" name="car_brand" value="1" {if $_A.infoconf_result.car_brand==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">������ֵ��</div>
	<div class="c">
		<input type="checkbox" name="car_value" value="1" {if $_A.infoconf_result.car_value==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��������ʱ�䣺</div>
	<div class="c">
		<input type="checkbox" name="car_buy_time" value="1" {if $_A.infoconf_result.car_buy_time==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�������ҽ�</div>
	<div class="c">
		<input type="checkbox" name="car_loan" value="1" {if $_A.infoconf_result.car_loan==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">����ÿ�»���</div>
	<div class="c">
		<input type="checkbox" name="car_month_loan" value="1" {if $_A.infoconf_result.car_month_loan==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">ʣ�໹�����ޣ�</div>
	<div class="c">
		<input type="checkbox" name="car_loan_year" value="1" {if $_A.infoconf_result.car_loan_year==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_title"><strong>����״��</strong></div>
<div class="module_border">
	<div class="l">ÿ���޵�Ѻ�����</div>
	<div class="c">
		<input type="checkbox" name="finance_repayment" value="1" {if $_A.infoconf_result.finance_repayment==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">ÿ�����ÿ������</div>
	<div class="c">
		<input type="checkbox" name="finance_creditcard" value="1" {if $_A.infoconf_result.finance_creditcard==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">������ס��ʽÿ��֧����</div>
	<div class="c">
		<input type="checkbox" name="finance_other_live_cost" value="1" {if $_A.infoconf_result.finance_other_live_cost==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">ÿ���������֧����</div>
	<div class="c">
		<input type="checkbox" name="finance_living_spend" value="1" {if $_A.infoconf_result.finance_living_spend==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_title"><strong>��ϵ��ʽ</strong></div>
<div class="module_border">
	<div class="l">��ס�ص绰��</div>
	<div class="c">
		<input type="checkbox" name="tel" value="1" {if $_A.infoconf_result.tel==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��ס���ʱࣺ</div>
	<div class="c">
		<input type="checkbox" name="post" value="1" {if $_A.infoconf_result.post==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�־�ס��ַ��</div>
	<div class="c">
		<input type="checkbox" name="address" value="1" {if $_A.infoconf_result.address==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">QQ��</div>
	<div class="c">
		<input type="checkbox" name="qq" value="1" {if $_A.infoconf_result.qq==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">MSN��</div>
	<div class="c">
		<input type="checkbox" name="msn" value="1" {if $_A.infoconf_result.msn==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">����������ϵ��ʽ���û�����</div>
	<div class="c">
		<input type="checkbox" name="other_net_contact" value="1" {if $_A.infoconf_result.other_net_contact==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�־�ס��ַ��</div>
	<div class="c">
		<input type="checkbox" name="address" value="1" {if $_A.infoconf_result.address==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��һ��ϵ��������</div>
	<div class="c">
		<input type="checkbox" name="linkman1" value="1" {if $_A.infoconf_result.linkman1==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��һ��ϵ�˹�ϵ��</div>
	<div class="c">
		<input type="checkbox" name="relation1" value="1" {if $_A.infoconf_result.relation1==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��һ��ϵ�˵绰��</div>
	<div class="c">
		<input type="checkbox" name="tel1" value="1" {if $_A.infoconf_result.tel1==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��һ��ϵ���ֻ���</div>
	<div class="c">
		<input type="checkbox" name="phone1" value="1" {if $_A.infoconf_result.phone1==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�ڶ���ϵ��������</div>
	<div class="c">
		<input type="checkbox" name="linkman2" value="1" {if $_A.infoconf_result.linkman2==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�ڶ���ϵ�˹�ϵ��</div>
	<div class="c">
		<input type="checkbox" name="relation2" value="1" {if $_A.infoconf_result.relation2==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�ڶ���ϵ�˵绰��</div>
	<div class="c">
		<input type="checkbox" name="tel2" value="1" {if $_A.infoconf_result.tel2==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�ڶ���ϵ���ֻ���</div>
	<div class="c">
		<input type="checkbox" name="phone2" value="1" {if $_A.infoconf_result.phone2==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_title"><strong>��ż����</strong></div>
<div class="module_border">
	<div class="l">��ż������</div>
	<div class="c">
		<input type="checkbox" name="mate_name" value="1" {if $_A.infoconf_result.mate_name==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">ÿ��н��</div>
	<div class="c">
		<input type="checkbox" name="mate_salary" value="1" {if $_A.infoconf_result.mate_salary==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">�ƶ��绰��</div>
	<div class="c">
		<input type="checkbox" name="mate_phone" value="1" {if $_A.infoconf_result.mate_phone==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��λ�绰��</div>
	<div class="c">
		<input type="checkbox" name="mate_tel" value="1" {if $_A.infoconf_result.mate_tel==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">������λ��</div>
	<div class="c">
		<input type="checkbox" name="mate_company" value="1" {if $_A.infoconf_result.mate_company==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">������λ���ͣ�</div>
	<div class="c">
		<input type="checkbox" name="mate_type" value="1" {if $_A.infoconf_result.mate_type==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">ְλ��</div>
	<div class="c">
		<input type="checkbox" name="mate_office" value="1" {if $_A.infoconf_result.mate_office==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��λ��ַ��</div>
	<div class="c">
		<input type="checkbox" name="mate_address" value="1" {if $_A.infoconf_result.mate_address==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��ż�����룺</div>
	<div class="c">
		<input type="checkbox" name="mate_income" value="1" {if $_A.infoconf_result.mate_income==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_title"><strong>��������</strong></div>
<div class="module_border">
	<div class="l">���ѧ����</div>
	<div class="c">
		<input type="checkbox" name="education_record" value="1" {if $_A.infoconf_result.education_record==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">��ҵԺУ��</div>
	<div class="c">
		<input type="checkbox" name="education_school" value="1" {if $_A.infoconf_result.education_school==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">רҵ��</div>
	<div class="c">
		<input type="checkbox" name="education_study" value="1" {if $_A.infoconf_result.education_study==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_title"><strong>������Ϣ</strong></div>
<div class="module_border">
	<div class="l">����������</div>
	<div class="c">
		<input type="checkbox" name="ability" value="1" {if $_A.infoconf_result.ability==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">���˰��ã�</div>
	<div class="c">
		<input type="checkbox" name="interest" value="1" {if $_A.infoconf_result.interest==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border">
	<div class="l">����˵����</div>
	<div class="c">
		<input type="checkbox" name="others" value="1" {if $_A.infoconf_result.others ==1} checked="checked"{/if} />�Ƿ����
	</div>
</div>
<div class="module_border" style="text-align:center">
	<input type="hidden" value="{$magic.request.usertype}" name="usertype" /> 
	<input type="button" value="�ύ" name="sub" onclick="sub_form()" />
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var username = $("#username").val();
	var title = $("#title").val();
	var content = $("#content").val();
	if(username==""){
		alert("�ռ��˲���Ϊ��");return;
	}
	if(title==""){
		alert("���ⲻ��Ϊ��");return;
	}
	if(content==""){
		alert("���ݲ���Ϊ��");return;
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