{if $_A.query_type == "new" || $_A.query_type == "edit"}
<!-- �ʻ���Ϣ�б� ��ʼ -->
{elseif $_A.query_type=="list"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">�û���</td>
			<td width="" class="main_td">��ʵ����</td>
			<td width="" class="main_td">�����</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">���ս��</td>
            <td width="" class="main_td">�������</td>
            <td width="" class="main_td">���ʲ�</td>
		</tr>
		{foreach from=$_A.account_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{ $item.user_id}</td>
			<td><a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene",500,230,"true","","true","text");'>{$item.username}</a></td>
			<td>{$item.realname}</td>
			<td>{$item.total|default:0}</td>
			<td>{$item.use_money|default:0}</td>
			<td>{$item.no_use_money|default:0}</td>
			<td>{$item.collection|default:0}</td>
			{article module="borrow" function="GetWaitPayment" user_id=$item.user_id var="acc"}
			<td>
			{$acc.wait_payment|default:0}
			</td>
			<td>			<script type="text/javascript">
			document.write({$item.total|default:0}-{$acc.wait_payment|default:0});
			</script>
			</td>
			{/article}
		</tr>
		{/foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> <input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="9" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table><!-- �ʻ���Ϣ�б� ���� -->

{elseif $_A.query_type=="listTJ"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="" class="main_td">�û����</td>
			<td width="" class="main_td">�û���</td>
			<td width="" class="main_td">��ʵ����</td>
			<td width="" class="main_td">�����</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">���ս��</td>
            <td width="" class="main_td">�������</td>
            <td width="" class="main_td">���ʲ�</td>
			<td width="" class="main_td">����ʱ��</td>
		</tr>
		{foreach  from=$_A.account_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.user_id}</td>
			<td>{$item.username}</td>
			<td>{$item.realname}</td>
			<td>{$item.total|default:0}</td>
			<td>{$item.use_money|default:0}</td>
			<td>{$item.no_use_money|default:0}</td>
			<td>{$item.collection|default:0}</td>
            <td>{$item.wait_repayMoney|default:0}</td>
            <td>{$item.jin_money|default:0}</td>
			<td>{$item.addtime|default:0}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="11" class="action">
		<div class="floatl">
		<input type="button" onclick="javascript:location.href='{$_A.query_url}/listTJ&type=excel'" value="�����б�" />
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username}"/> <input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
	</tr>
		<tr>
			<td colspan="11" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table>

<!-- ������� ��ʼ -->
{elseif $_A.query_type == "fs_list"}
<table border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">�û����</td>
			<td width="" class="main_td">�û���</td>
			<td width="" class="main_td">��ʵ����</td>
			<td width="" class="main_td">�����</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">���ս��</td>
		</tr>
		{foreach  from=$_A.fs_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.user_id}</td>
			<td>{$item.username}</td>
			<td>{$item.realname}</td>
			<td>{$item.total|default:0}</td>
			<td>{$item.use_money|default:0}</td>
			<td>{$item.no_use_money|default:0}</td>
			<td>{$item.collection|default:0}</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="7" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table><!-- ������� ���� -->
<!-- �û���� ��ʼ -->
{elseif $_A.query_type=="ticheng"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="" class="main_td">ʱ��</td>
			<td width="" class="main_td">�û���</td>
			<td width="" class="main_td">����Ͷ���ܶ�(��)</td>
		</tr>
		{foreach from=$_A.account_ticheng key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.addtimes}</td>
			<td><a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene",500,230,"true","","true","text");'>{$item.usernames}</a></td>
			<td >{$item.money}</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="4" class="action">
			<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="�����б�" />
			</div>
			<div class="floatr">
				�û�����<input type="text" name="username" id="username" value="{$magic.request.username}"/><input type="button" value="����" onclick="sousuo()" />
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="4" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table>  <!-- �û���� ���� -->

<!-- vip��� ��ʼ -->
{elseif $_A.query_type=="vipTC"}
<table border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">�ƹ����û���</td>	
			<td width="" class="main_td">�����û���</td>
			<td width="" class="main_td">��ʵ����</td>
			<td width="" class="main_td">ע��ʱ��</td>
			<td width="" class="main_td">�Ƿ�VIP��Ա</td>
			<td width="" class="main_td">Ӧ���������</td>
			<td width="" class="main_td">ʵ���������(��֧��)</td>
		</tr>
		{foreach  from=$_A.vipTC_list key=key item=item}
		<tr>
			<td>{$item.inviteUserName}</td>
			<td>{$item.username}</td>
			<td>{$item.realname}</td>
			<td>{$item.addtime|date_format}</td>
			<td>{if $item.vip_status == 1}��{else}��{/if}</td>
			<td>{if $item.vip_status == 1}100Ԫ{else}0Ԫ{/if}</td>
			<td>{$item.invite_money}Ԫ</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
		�������û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
                  �������û�����<input type="text" name="username2" id="username2" value="{$magic.request.username2|urldecode}"/>
                  <input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="9" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>	
</table>
<!-- vip��� ���� -->

<!-- �ʽ���˱� ��ʼ -->
{elseif $_A.query_type=="moneyCheck"}
<table border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr >
			<td width="" class="main_td">�û���</td>
			<td width="" class="main_td">�ʽ��ܶ�</td>
			<td width="" class="main_td">�����ʽ�</td>
			<td width="" class="main_td">�����ʽ�</td>
			<td width="" class="main_td">�����ʽ�(1)</td>
			<td width="" class="main_td">�����ʽ�(2)</td>
			<td width="" class="main_td">��ֵ�ʽ�(1)</td>
			<td width="" class="main_td">��ֵ�ʽ�(2)</td>
			<td width="" class="main_td">���У�����</td>
			<td width="" class="main_td">���У�����1</td>
			<td width="" class="main_td">���У�����2</td>
			<td width="" class="main_td">�ɹ����ֽ��</td>
			<!--td width="" class="main_td">����ʵ�ʵ���</td>
			<td width="" class="main_td">���ַ���</td-->
			<td width="" class="main_td">Ͷ�꽱�����</td>
			<!--td width="" class="main_td">Ͷ�������ʽ�</td-->
			<td width="" class="main_td">Ͷ��������Ϣ</td>
			<td width="" class="main_td">Ͷ�������Ϣ</td>
			<!--td width="" class="main_td">����ܽ��</td-->
			<td width="" class="main_td">���꽱��</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">��������</td>
			<td width="" class="main_td">������Ϣ</td>
			<td width="" class="main_td">����ѻ���Ϣ</td>
			<td width="" class="main_td">ϵͳ�۷�</td>
			<td width="" class="main_td">�ƹ㽱��</td>
			<td width="" class="main_td">VIP�۷�</td> 
			<td width="" class="main_td">�����ܶ�</td>
			<!--td width="" class="main_td">�ʽ��ܶ�1</td>
			<td width="" class="main_td">�ʽ��ܶ�2</td-->
		</tr>
		{foreach  from=$_A.moneyCheck_list key=key item=item}
		<tr>
			<td>{$item.username}</td>
			<td>{$item.total}</td>
			<td>{$item.use_money}</td>
			<td>{$item.no_use_money}</td>
			<td>{$item.collection}</td>
			<td>{$item.collection2}</td>
			<td>{$item.reMoney}</td>
			<td>{$item.reMoney2}</td>
			<td>{$item.reMoney_1}</td>
			<td>{$item.reMoney_2}</td>
			<td>{$item.reMoney_3}</td>
			<td>{$item.txTotal}</td>
			<!--td>{$item.txCredited}</td>
			<td>{$item.txFee}</td-->
			<td>{$item.awardAdd}</td>
			<!--td>{$item.collecdMoney}</td-->
			<td>{$item.interestYes}</td>
			<td>{$item.interestWait}</td>
			<!--td>{$item.accountBorrow}</td-->
			<td>{$item.borrowAward}</td>
			<td>{$item.borrowMgrFee}</td>
			<td>{$item.waitMoney_money}</td>
			<td>{$item.waitMoney_interest}</td>
			<td>
				<script type="text/javascript">
				document.write({$item.repayment_yesaccount|default:0});
				</script>
			</td>
			<td>{$item.feeSystem}</td>
			<td>{$item.invite_money}</td>
			<td>{$item.vipMoney}</td>
			<td>{$item.accountLateAll|default:0}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="24" class="action">
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
            <input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="24" class="page">
			{$_A.showpage}
			</td>
		</tr>
	</form>
</table>  <!-- �ʽ���˱� ���� -->
                        <!-- ���ֲο� ��ʼ -->
{elseif $_A.query_type=="cashCK"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">�û���</td>
			<td width="" class="main_td">��ʵ����</td>
			<td width="" class="main_td">Ͷ�ʵ������</td>
			<td width="" class="main_td">ʹ�õ����ö�ȣ�X��</td>
			<td width="" class="main_td">���ʲ�(W)</td>
			<td width="" class="main_td">������Ϣ(E)</td>
 			<td width="" class="main_td">���ֱ�׼��W+1.1X-E��</td>
		</tr>
		{foreach from=$_A.account_cashCK_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>			<td>{ $item.user_id}</td>
			<td>
			{$item.username}
			</td>
			<td>{$item.realname}</td>
			<td>{$item.tender_vouch|default:0}</td>
			<td>
                <script type="text/javascript">
                       document.write({$item.credit|default:0}-{$item.credit_use|default:0}+{$item.borrow_vouch|default:0}-{$item.borrow_vouch_use|default:0});
                </script>
          	</td>
			<td>
                {article module="borrow" function="GetUserLog" user_id=$item.user_id var="acc"}
                <script type="text/javascript">
                document.write({$item.total|default:0}-{$acc.wait_payment|default:0});
                </script>
                {/article}			</td>
			<td >				{article module="borrow" function="GetUserLog" user_id=$item.user_id var="acc"}				{$acc.collection_interest0|default:0}				{/article}			</td>			<td >				{article module="borrow" function="GetUserLog" user_id=$item.user_id var="acc"}				<script type="text/javascript">				document.write({$item.credit|default:0}*1.1-{$item.credit_use|default:0}*1.1+{$item.borrow_vouch|default:0}*1.1-{$item.borrow_vouch_use|default:0}*1.1+{$item.total|default:0}-{$acc.wait_payment|default:0}-{$acc.collection_interest0|default:0});				</script>
				{/article}			</td>		</tr>		{/foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> <input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
	</tr>
		<tr>
			<td colspan="9" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>	
</table>
<!-- ���ֲο� ���� -->
<!--���ּ�¼�б� ��ʼ-->
{elseif $_A.query_type=="cash"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">�û�����</td>
			<td width="" class="main_td">��ʵ����</td>
			<!--<td width="" class="main_td">�����˺�</td>
			<td width="" class="main_td">��������</td>
			<td width="" class="main_td">֧��</td>-->
			<td width="" class="main_td">�����ܶ�</td>
			<td width="" class="main_td">���˽��</td>
			<td width="" class="main_td">������</td>
			<!--<td width="" class="main_td">����ֿ�</td>-->
			<td width="" class="main_td">����ʱ��</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">����</td>
		</tr>
		{foreach  from=$_A.account_cash_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td >{ $item.id}</td>
			<td><a href="{$_A.query_url}/cash&username={$item.username}&a=cash">{$item.username}</a></td>
			<td >{ $item.realname}</td>
			<!--<td >{ $item.account}</td>
			<td >{ $item.bank_name}</td>
			<td >{ $item.branch}</td>-->
			<td >{ $item.total}</td>
			<td >{ $item.credited}</td>
			<td >{ $item.fee}</td>	
			<!--<td >{ $item.hongbao}</td>-->
			<td >{ $item.addtime|date_format:"Y-m-d H:i"}</td>
			<td >{if $item.status==0}�����{elseif $item.status==1}��ͨ�� {elseif $item.status==2}���ܾ�{elseif $item.status==-1}�ȴ��û�ȷ��{/if}</td>
			<td ><a href="{$_A.query_url}/cash_view{$_A.site_url}&id={$item.id}">���/�鿴</a></td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="9" class="action">
		<div class="floatl">
			<input type="button" value="������ǰ��������" onclick="sousuo('excel')" />
		</div>
		<div class="floatr">
		�����˺ţ�<input type="text" name="account" id="account" value="{$magic.request.account}" maxlength="19" />
		��ֵʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()"/>�� <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()"/>	
		�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		״̬��<select id="status"><option value="">ȫ��</option><option value="-1" {if $magic.request.status==-1} selected="selected"{/if}>�ȴ��û�ȷ��</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>��ͨ��</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>�����</option><option value="2" {if $magic.request.status=="2"} selected="selected"{/if}>���ʧ��</option></select><input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="13" class="page">
			{$_A.showpage} 
		</td>
	</tr>
	</form>	
</table>
<!--���ּ�¼�б� ����-->
<!--������� ��ʼ-->
{elseif $_A.query_type == "cash_view"}
<div class="module_add">
	<form name="form1" method="post" action="">
	<div class="module_title"><strong>�������/�鿴</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			{$_A.account_cash_result.username}		</div>
	</div>
	<!--
	<div class="module_border">
		<div class="l">�������У�</div>
		<div class="c">
			{$_A.account_cash_result.bank_name }
		</div>
	</div>
	<div class="module_border">
		<div class="l">����֧�У�</div>
		<div class="c">
			{$_A.account_cash_result.branch }
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����˺ţ�</div>
		<div class="c">
			{$_A.account_cash_result.account }
		</div>
	</div>
	-->
	<div class="module_border">
		<div class="l">�����ܶ</div>
		<div class="c">
			{$_A.account_cash_result.total }
		</div>
	</div>
	<div class="module_border">
		<div class="l">���˽�</div>
		<div class="c">
			{$_A.account_cash_result.credited }
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ѣ�</div>
		<div class="c">
			{$_A.account_cash_result.fee }
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
		{if $_A.account_cash_result.status==0}���������{elseif $_A.account_cash_result.status==1}������ͨ�� {elseif $_A.account_cash_result.status==2}���ֱ��ܾ�{elseif $_A.account_cash_result.status==-1}�ȴ��û�ȷ��{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ʱ��/IP:</div>
		<div class="c">
			{$_A.account_cash_result.addtime|date_format:'Y-m-d H:i:s'}/{ $_A.account_cash_result.addip}</div>
	</div>
	{if $_A.account_cash_result.status==0}
	<div class="module_title"><strong>��˴�������Ϣ</strong></div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
		<input type="radio" name="status" value="0" {if $_A.account_cash_result.status==0} checked="checked"{/if} />�ȴ����  
		<input type="radio" name="status" value="-1" {if $_A.account_cash_result.status==-1} checked="checked"{/if}/>���ͨ�� 
		<input type="radio" name="status" value="2" {if $_A.account_cash_result.status==2} checked="checked"{/if}/>��˲�ͨ�� 
		</div>
	</div>
	<div class="module_border" >
		<div class="l">���˽��:</div>
		<div class="c">
			<input type="text" name="credited" readonly="readonly" style="background:#CCCCCC" value="{ $_A.account_cash_result.credited}" size="10">
		</div>
	</div>
	<div class="module_border" >
		<div class="l">������:</div>
		<div class="c">
			<input type="text" name="fee" value="{$_A.account_cash_result.fee}" size="5" onBlur="updateFee({$_A.account_cash_result.total})" />			{literal}			<script type="text/javascript">			function updateFee(total){				var form = document.forms['form1'];				var fee = parseFloat(form.elements['fee'].value);
				var hongbao = parseFloat(form.elements['hongbao'].value);				if(isNaN(fee)){					fee = 0;				}				if(fee<hongbao){					alert("�����Ѳ���С�ڵֿ۵ĺ��");					form.elements['fee'].value = hongbao;				}else if(fee>total/2){					alert("�����Ѳ��ܴ��������ܶ��50%");					form.elements['fee'].value = total/2;				}else{					form.elements['fee'].value = fee;				}				form.elements['credited'].value = parseFloat(total)-parseFloat(form.elements['fee'].value)+parseFloat(form.elements['hongbao'].value);			}
			function check_form(){
				var frm = document.forms['form1'];
				var verify_remark = frm.elements['verify_remark'].value;
				var errorMsg = '';
				if(verify_remark.length == 0 ) {
					errorMsg += '--��ע������д' + '\n';
				}
				if(errorMsg.length == 0){
					frm.submit();
					frm.elements['reset'].disabled=true;
					frm.elements['reset'].value="����ύ��....";
					submit_fool();
				}else{
					alert(errorMsg);
					return;
				}
			}			</script>			{/literal}
		</div>
	</div>
	<!--
	<div class="module_border" >
		<div class="l">����ֿ�:</div>
		<div class="c">
			<input type="text" name="hongbao" readonly="readonly" style="background:#CCCCCC" value="{ $_A.account_cash_result.hongbao}" size="10">
		</div>
	</div>
	-->
	<input type="hidden" name="hongbao" value="{ $_A.account_cash_result.hongbao}">
	<div class="module_border" >
		<div class="l">��˱�ע:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5">{$_A.account_result.verify_remark}</textarea>
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="id" value="{ $_A.account_cash_result.id }" />
		<input type="hidden" name="user_id" value="{ $_A.account_cash_result.user_id }" />
		<input type="button" name="reset" value="��˴�������Ϣ" onclick="check_form()" />
	</div>
	{else}
	<div class="module_border">
		<div class="l">�����Ϣ��</div>
		<div class="c">
			����ˣ�{ $_A.account_cash_result.verify_username },���ʱ�䣺{ $_A.account_cash_result.verify_time|date_format:"Y-m-d H:i" },��˱�ע��{ $_A.account_cash_result.verify_remark}
		</div>
	</div>
	{/if}
	</form>
</div>


<!--��ֵ��¼�б� ��ʼ-->
{elseif $_A.query_type=="recharge"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
            <td width="*" class="main_td">��ˮ��</td>
			<td width="*" class="main_td">�û�����</td>
			<td width="*" class="main_td">��ʵ����</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">��������</td>
			<td width="" class="main_td">��ֵ���</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">���˽��</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">��ֵʱ��</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">���з���</td>
			<td width="" class="main_td">����</td>
		</tr>
		{ foreach  from=$_A.account_recharge_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td >{$item.id}</td>			<td >{$item.trade_no}</td>
			<td><a href="{$_A.query_url}/recharge&username={$item.username}&a=cash">{$item.username}</a></td>
			<td >{$item.realname}</td>
			<td >{if $item.type==1}���ϳ�ֵ{else}���³�ֵ{/if}</td>
			<td >{if $item.payment==0}�ֶ���ֵ{else}{ $item.payment_name}{/if}</td>
			<td >{$item.money}Ԫ</td>
			<td >{$item.fee}Ԫ</td>
			<td ><font color="#FF0000">{$item.total}Ԫ</font></td>
			<td >{$item.hongbao}Ԫ</td>
			<td ><font color="#FF3300">�ύ��{$item.addtime|date_format:"Y-m-d H:i:s"}</font><br/>
			<font color="#aaaaaa">��ɣ�{$item.verify_time|date_format:"Y-m-d H:i:s"}</font>
			</td>
			<td >{if $item.status==0 || $item.status== -1 }<font color="#6699CC">�����</font>{elseif  $item.status==1} �ɹ� {else}<font color="#FF0000">ʧ��</font>{/if}</td>
            <td >{if $item.return==""&& $item.type==1  }<span style="color:#F00">����δ����</span>{elseif $item.return<>""&& $item.type==1} �����ѵ���{else}���º˶�{/if}</td>
			<td ><a href="{$_A.query_url}/recharge_view{$_A.site_url}&id={$item.id}">���/�鿴</a></td>
		</tr>
		{/foreach}
	<tr>
		<td colspan="14" class="action">
		<div class="floatl">		<input type="button" value="������ǰ����" onclick="sousuo('excel')" />
		</div>
		<div class="floatr">		�������У�<select id="pertainbank"><option value="0">ȫ��</option><option value="-1">���³�ֵ</option><option value="-2">���ϳ�ֵ</option><option value="-3">�ֶ���ֵ</option>		{foreach from=$_A.account_payment_list item="var"}		{if $magic.request.pertainbank==$var.id}		<option value="{$var.id}" selected="selected">{$var.name}</option>		{else}		<option value="{$var.id}">{$var.name}</option>		{/if}		{/foreach}</select>
		��ֵʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()"/>
		�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		��ˮ�ţ�<input type="text" name="trade_no" id="trade_no" value="{$magic.request.trade_no}"/> 
		״̬<select id="status" ><option value=''>ȫ��</option><option value="-1" {if $magic.request.status=="-1"} selected="selected"{/if}>δ���</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>��˳ɹ�</option><option value="2" {if $magic.request.status=="2"} selected="selected"{/if}>���ʧ��</option></select> <input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
	</tr>
		<tr>
			<td colspan="14" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>	
</table>
<!--��ֵ��¼�б� ����-->
<!--���������ֵ��¼-->
{elseif $_A.query_type=="rechargefromexcel"}
<form action='' method='post' enctype="multipart/form-data">
<div class="module_border">
	<div class="l">�����ļ���</div>
	<div class="c">
		<input type="file" name="excelfile" />
	</div>
	<div class="c">
		<input type="submit" value="�ύ����" />
	</div>
</div>
</form>
<!--��ֵ��� ��ʼ-->
{elseif $_A.query_type == "recharge_view"}
<div class="module_add">
	<form name="form1" method="post" action="">
	<div class="module_title"><strong>��ֵ�鿴</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/user/view&user_id={$_A.account_recharge_result.user_id}&type=scene",500,230,"true","","true","text");'>{ $_A.account_recharge_result.username}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ֵ���ͣ�</div>
		<div class="c">
			{if $_A.account_recharge_result.type==1}���ϳ�ֵ{else}���³�ֵ{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">֧����ʽ��</div>
		<div class="c">
			{$_A.account_recharge_result.payment_name|default:����Ա��ӳ�ֵ}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ֵ�ܶ</div>
		<div class="c">
			{$_A.account_recharge_result.money}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ã�</div>
		<div class="c">
			{$_A.account_recharge_result.fee}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">���������</div>
		<div class="c">
			{$_A.account_recharge_result.hongbao}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ֽ�����</div>
		<div class="c">
			{$_A.account_recharge_result.reward}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">ʵ�ʵ��ˣ�</div>
		<div class="c">
			{$_A.account_recharge_result.total}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">�û���ע��</div>
		<div class="c">
		{$_A.account_recharge_result.remark}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ˮ�ţ�</div>
		<div class="c">
		{$_A.account_recharge_result.trade_no}
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
		{if $_A.account_recharge_result.status==0}�ȴ����{elseif $_A.account_recharge_result.status==1} ��ֵ�ɹ� {elseif $_A.account_recharge_result.status==2}��ֵʧ��{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ʱ��/IP:</div>
		<div class="c">
			{$_A.account_recharge_result.addtime|date_format:'Y-m-d H:i:s'}/{$_A.account_recharge_result.addip}</div>
	</div>
	{if $_A.account_recharge_result.status==0}
	<div class="module_title"><strong>��˴˳�ֵ��Ϣ</strong></div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
	<input type="radio" name="status" value="1"/>��ֵ�ɹ�   <input type="radio" name="status" value="2"  checked="checked"/>��ֵʧ�� </div>
	</div>
	<div class="module_border" >
		<div class="l">���˽��:</div>
		<div class="c">
			{$_A.account_recharge_result.total}Ԫ
			<input type="hidden" name="total" value="{$_A.account_recharge_result.total}" size="15" readonly="readonly">
		</div>
	</div>
	{if $_A.account_recharge_result.type!=1}
	<div class="module_border" >
		<div class="l">�����ֽ���:</div>
		<div class="c">{$_A.account_recharge_result.reward}Ԫ</div>
	</div>
	{/if}
	<div class="module_border" >
		<div class="l">��˱�ע:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5">{ $_A.account_recharge_result.verify_remark}</textarea>
		</div>
	</div>
	<div class="module_border" >
		<div class="l">��֤��:</div>
		<div class="c">
		<input type="text" size="5" maxlength="4" name="valicode">
		<img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="���ˢ��" src="/plugins/index.php?q=imgcode">
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="id" value="{ $_A.account_recharge_result.id }" />
		<input type="button" name="reset" value="��˴˳�ֵ��Ϣ" onclick="check_form()" />
	</div>
	{else}
		{if $_A.account_recharge_result.type==2 }
	<div class="module_border">
		<div class="l">�����Ϣ��</div>
		<div class="c">
			����ˣ�{ $_A.account_result.verify_username },���ʱ�䣺{ $_A.account_result.verify_time|date_format:"Y-m-d H:i" },��˱�ע��{ $_A.account_result.verify_remark}
		</div>
	</div>
	{/if}
	{/if}
	</form>
</div>
{literal}
<script type="text/javascript">
function check_form(){
	var frm = document.forms['form1'];
	var verify_remark = frm.elements['verify_remark'].value;
	var valicode = frm.elements['valicode'].value;
	var errorMsg = '';
	if(verify_remark.length == 0 ) {
		errorMsg += '--��ע������д' + '\n';
	}
	if(valicode.length != 4){
		errorMsg += '--��֤����������' + '\n';
	}
	if(errorMsg.length == 0){
		frm.submit();
		frm.elements['reset'].disabled=true;
		frm.elements['reset'].value="����ύ��....";
		submit_fool();
	}else{
		alert(errorMsg);
		return;
	}
}
</script>
{/literal}
<!--��ֵ��� ����-->

<!--��ӳ�ֵ��¼ ��ʼ-->
{elseif $_A.query_type == "recharge_new"}
<div class="module_add">
	<form name="form1" method="post" action="" enctype="multipart/form-data">
	<div class="module_title"><strong>��ӳ�ֵ</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<input type="text" name="username" /><font style="color:red">*</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ͣ�</div>
		<div class="c">
			���³�ֵ<input type="hidden" name="type" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��</div>
		<div class="c">
			<input type="text" name="money" maxlength="6" /><font style="color:red">*</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ע��</div>
		<div class="c">
			<input type="text" name="remark" value="����֧��Ͷ�겹��ֵ" /><font style="color:red">*</font>
		</div>
	</div>
	<!-- <div class="module_border">
		<div class="l">������ӣ�</div>
		<div class="c">
			<input type="file" name="excelfile" />
			<input type="button" value="�������" onclick="document.forms['form1'].submit();this.disabled=true;" />
		</div>
	</div> -->
	<div class="module_border">
		<div class="l"></div>
		<div class="c" style="color:red">
			ʹ�ô˹���ǰ���Ⱥ��û�ȷ���Ƿ�ͨ������֧��Ͷ��������ʽ����,���û��ṩ��ֵ֤��
		</div>
	</div>
	<div class="module_submit" >
		<input type="button" name="reset" value="ȷ�ϳ�ֵ" onclick="check_form()" />
	</div>
</form>
</div>
{literal}
<script type="text/javascript">
function check_form(){
	var frm = document.forms['form1'];
	var remark = frm.elements['remark'].value;
	var username = frm.elements['username'].value;
	var money = frm.elements['money'].value;
	var errorMsg = '';
	if(remark.length == 0 ) {
		errorMsg += '--��ע������д' + '\n';
	}
	if(username.length == 0){
		errorMsg += '--�û���������д' + '\n';
	}
	if(money.length == 0){
		errorMsg += '--��������д' + '\n';
	}
	if(errorMsg.length == 0){
		frm.submit();
		frm.elements['reset'].disabled=true;
		frm.elements['reset'].value="��ֵ�ύ��...";
		submit_fool();
	}else{
		alert(errorMsg);
		return;
	}
}
</script>
{/literal}
<!--��ӳ�ֵ��¼ ����-->
<!--���ÿ۳� ��ʼ-->
{elseif $_A.query_type == "deduct"}
<div class="module_add">
	<form name="form1" method="post" action="">
	<div class="module_title"><strong>���ÿ۳�</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<input type="text" name="username" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ͣ�</div>
		<div class="c">
			<select name="type">
				<option value="scene_account">�ֳ���֤����</option>
				<option value="vouch_advanced">�����渶�۷�</option>
				<option value="borrow_kouhui">����˷���ۻ�</option>
				<option value="account_other">����</option>
			</select>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��</div>
		<div class="c">
			<input type="text" name="money" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ע��</div>
		<div class="c">
			<input type="text" name="remark" />���磬�ֳ����ÿ۳�200Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">��֤�룺</div>
		<div class="c"><input  class="user_aciton_input"  name="valicode" type="text" size="8" maxlength="4" style=" padding-top:4px; height:16px; width:70px;"/>&nbsp;<img src="/plugins/index.php?q=imgcode" alt="���ˢ��" onClick="this.src='/plugins/index.php?q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>
	<div class="module_submit" >
		<input type="button"  name="reset" value="ȷ���۳�" onclick="check_form()" />
	</div>
</form>
</div>
{literal}
<script type="text/javascript">
function check_form(){
	var frm = document.forms['form1'];
	var remark = frm.elements['remark'].value;
	var username = frm.elements['username'].value;
	var money = frm.elements['money'].value;
	var valicode = frm.elements['valicode'].value;
	var errorMsg = '';
	if(remark.length == 0 ) {
		errorMsg += '--��ע������д' + '\n';
	}
	if(username.length == 0){
		errorMsg += '--�û���������д' + '\n';
	}
	if(money.length == 0){
		errorMsg += '--��������д' + '\n';
	}
	if(valicode.length != 4){
		errorMsg += '--��֤����������' + '\n';
	}
	if(errorMsg.length == 0){
		frm.submit();
		frm.elements['reset'].disabled=true;
		frm.elements['reset'].value="�ύ��...";
		submit_fool();
	}else{
		alert(errorMsg);
		return;
	}
}
</script>
{/literal}
<!--���ÿ۳�  ����-->
<!--�ʽ�ʹ�ü�¼�б� ��ʼ-->
{elseif $_A.query_type=="log"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">�û�����</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">�ܽ��</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">���ý��</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">���ս��</td>
			<td width="" class="main_td">���׶Է�</td>
			<td width="" class="main_td">��¼ʱ��</td>
            <td width="" class="main_td">��ע</td>
			<td width="" class="main_td">����</td>
		</tr>
		{foreach from=$_A.account_log_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1" ><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene" class="thickbox" title="�û���ϸ��Ϣ�鿴">{$item.username}</a></td>
			<td>{$item.type|linkage:"account_type"}</td>
			<td>{$item.total}</td>
			<td>{$item.money}</td>
			<td>{$item.use_money}</td>
			<td>{$item.no_use_money|default:0}</td>
			<td>{$item.collection|default:0}</td>
			<td>{$item.to_username|default:ϵͳ}</td>
			<td>{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
            <td>{$item.remark}</td>
			<td>--</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="12" class="action">
		<div class="floatl"><input type="button" value="������ǰ����" onclick="sousuo('excel')" /></div>
		<div class="floatr">
		ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/>��<input type="text" name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/>   
		{linkages nid="account_type" value="$magic.request.typeaction" name="typeaction" type="value" default="ȫ��"}
		�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		<input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="11" class="page">
			{$_A.showpage}
			</td>
		</tr>
	</form>
</table>
<!--�ʽ�ʹ�ü�¼�б� ����-->
<!--�����鿴-->
{elseif $_A.query_type=="tgviewcash"}
{if $magic.get.view==1}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
        <td width="" class="main_td">�û���</td>
		<td width="" class="main_td">����</td>
		<td width="" class="main_td">������</td>
		<td width="" class="main_td">��id</td>
		<td width="" class="main_td">����id</td>
		<td width="" class="main_td">����id</td>
		<td width="" class="main_td">�ύʱ��</td>
		<td width="" class="main_td">������Ϣ</td>
		<td width="" class="main_td">����״̬</td>
		<td width="" class="main_td">����</td>
	</tr>
	{foreach from=$_A.order_result key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td>{$item.id}</td>
        <td>{$item.username}</td>
		<td>{$item.tran_code_show}</td>
		<td>{$item.order_number}</td>
		<td>{$item.borrow_id}</td>
		<td>{$item.collection_id}</td>
		<td>{$item.repayment_id}</td>
		<td>{$item.tran_time_show}</td>
		<td>{$item.err_msg}</td>
		<td>{$item.err_code}</td>
		<td><a href="{$_A.query_url}/{$_A.query_type}&view=2&order_id={$item.id}&a=cash">�鿴</a></td>
	</tr>
	{/foreach}
	<tr>
	<td colspan="11" class="action">
		�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		�����ţ�<input type="text" name="order_number" id="order_number" value="{$magic.request.order_number}"/>
		��id��<input type="text" name="borrow_id" id="borrow_id" value="{$magic.request.borrow_id}"/>
		����id��<input type="text" name="repayment_id" id="repayment_id" value="{$magic.request.repayment_id}"/>
		����id��<input type="text" name="collection_id" id="collection_id" value="{$magic.request.collection_id}"/>
		<input type="button" value="����" onclick="tgviewcs()" />
		</td>
	</tr>
	<tr>
		<td colspan="11" class="page">
		{$_A.showpage}
		</td>
	</tr>
</table>
{elseif $magic.get.view==2}
<div class="module_add">
	<div class="module_title"><strong>�����鿴</strong></div>
	<div class="module_border">
		<div class="l">���ͣ�</div>
		<div class="c">
			{$_A.order_result.tran_code_show}
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ţ�</div>
		<div class="c">{$_A.order_result.order_number}</div>
	</div>
	{if $_A.order_result.borrow_id!=0}
	<div class="module_border">
		<div class="l">��id��</div>
		<div class="c">{$_A.order_result.borrow_id}</div>
	</div>
	{/if}
	{if $_A.order_result.collection_id!=0}
	<div class="module_border">
		<div class="l">����id��</div>
		<div class="c">{$_A.order_result.collection_id}</div>
	</div>
	{/if}
	{if $_A.order_result.repayment_id!=0}
	<div class="module_border">
		<div class="l">����id��</div>
		<div class="c">{$_A.order_result.repayment_id}</div>
	</div>
	{/if}
	<div class="module_border">
		<div class="l">�ύʱ�䣺</div>
		<div class="c">{$_A.order_result.tran_time_show}</div>
	</div>
	{if $_A.order_result.user_id!=0}
	<div class="module_border">
		<div class="l">�ύ�û���</div>
		<div class="c">{$_A.order_result.username}</div>
	</div>
	{/if}
	<!--
	{if $_A.order_result.tran_code=='P009'}
	<div class="module_title"><strong>��վ��ֵ״̬</strong></div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c">{if $_A.web_recharge.status==1}�ѵ���{else}δ����&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="����" value="����" onclick="location.href='{$_A.query_url}/{$_A.query_type}&view=4&order_id={$_A.order_result.id}&a=cash'"><span style="color:red">(ע�����������˶�ƽ̨δ����ʱ���д˲�������)</span>{/if}</div>
	</div>

	{/if}-->
	<div class="module_title"><strong>������Ϣ</strong></div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c" style="width:100%">
			<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
			{foreach from=$_A.order_result.tg_return_show key=key item=item}
			{if $item!=''}
			<tr>
				<td width='40%'>{$key}</td><td>{$item}</td>
			</tr>
			{/if}
			{/foreach}
			</table>
		</div>
	</div>

	<div class="module_title"><strong>��ѯ������Ϣ</strong></div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c" style="width:100%">
			<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
			{foreach from=$_A.order_result.cx_return_show key=key item=item}
			{if $item!=''}
			<tr>
				<td width='40%'>{$key}</td><td>{$item}</td>
			</tr>
			{/if}
			{/foreach}
			</table>
		</div>
	</div>

	{if $_A.order_result.cx_return_time>=0}
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><input type="button" name="���²�ѯ" value="���²�ѯ" id="button_cx" onclick="cxdd({$_A.order_result.id})" /></div>
	</div>
	<div class="module_border" id="cxxxxs"></div>
	{/if}
</div>
{/if}
<script type="text/javascript">
var cx_url = '{$_A.query_url}/{$_A.query_type}&view=3';
var ss_url = '{$_A.query_url}/{$_A.query_type}&view={$magic.get.view}&viewtype={$magic.get.viewtype}&a=cash';
{literal}
$(".c table tr:even").addClass("tr2");
function cxdd(id){
	$.jBox.tip('��ѯ��...','loading');
	$.get(cx_url, 'order_id='+id, function(re){
		location.reload(true);
	})
}
function tgviewcs(){
	var username = $("#username").val();
	var order_number = $("#order_number").val();
	var borrow_id = $("#borrow_id").val();
	var repayment_id = $("#repayment_id").val();
	var collection_id = $("#collection_id").val();
	var url = ss_url+'&username='+username+'&order_number='+order_number+'&borrow_id='+borrow_id+'&repayment_id='+repayment_id+'&collection_id='+collection_id;
	location.href=url;
}
{/literal}
</script>


{elseif $_A.query_type=="tgcheck"}

<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
        <td width="" class="main_td">�û���</td>
		<td width="" class="main_td">ƽ̨�ܶ�</td>
		<td width="" class="main_td">ƽ̨����</td>
		<td width="" class="main_td">ƽ̨����</td>
		<td width="" class="main_td">ƽ̨����</td>
		<td width="" class="main_td">�йܿ���</td>
	</tr>
	{foreach from=$_A.tgcheck key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
        <td>{$item.username}</td>
		<td>{$item.total}</td>
		<td>{$item.no_use_money}</td>
		<td>{$item.collection}</td>
		<td>{$item.use_money}</td>
		<td>{$item.tg_account.use_money}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="11" class="action">
		�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		<input type="button" value="����" onclick="if($('#username').val()!='{$magic.request.username|urldecode}') location.href='{$_A.query_url}/{$_A.query_type}&page=1&a=cash&username='+$('#username').val()" />
		</td>
	</tr>
	<tr>
		<td colspan="11" class="page">
		{$_A.showpage}
		</td>
	</tr>
</table>

{elseif $_A.query_type=="tgzhuanz"}
<form action='' method='post'>
	<div class="module_title"><strong>ת����Ϣ</strong></div>
	<div class="module_border">
		<div class="l">ת���û�����</div>
		<div class="c"><input type="text" name="out_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">ת���û�����</div>
		<div class="c"><input type="text" name="in_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">ת�˽�</div>
		<div class="c"><input type="text" name="account" /></div>
	</div>
	<div class="module_border">
		<div class="l">��֤�룺</div>
		<div class="c"><input type="text" name="valicode" /><img src="/plugins/index.php?q=imgcode" alt="���ˢ��" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" align="absmiddle" style="cursor:pointer;"></div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><input type="submit" value="ȷ��ת��" /></div>
	</div>
</form>
{elseif $_A.query_type=="tgzhuanz_c"}
<form action='' method='post'>
	<div class="module_title"><strong>ת����Ϣ</strong></div>
	<div class="module_border">
		<div class="l">ת���û�����</div>
		<div class="c"><input type="text" name="out_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">ת���û�����</div>
		<div class="c"><input type="text" name="in_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">ת�˽�</div>
		<div class="c"><input type="text" name="account" /></div>
	</div>
	<div class="module_border">
		<div class="l">��֤�룺</div>
		<div class="c"><input type="text" name="valicode" /><img src="/plugins/index.php?q=imgcode" alt="���ˢ��" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" align="absmiddle" style="cursor:pointer;"></div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><input type="submit" value="ȷ��ת��" />��ת��ֻ������й��ʽ��ף�ƽ̨�ʽ𲻻�仯</div>
	</div>
</form>

{elseif $_A.query_type=="tgzhuanz_r"}
<form action='' method='post'>
	<div class="module_title"><strong>ת����Ϣ</strong></div>
	<div class="module_border">
		<div class="l">ת���û�����</div>
		<div class="c"><input type="text" name="out_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">ת�˽�</div>
		<div class="c"><input type="text" name="account" /></div>
	</div>
	<div class="module_border">
		<div class="l">��֤�룺</div>
		<div class="c"><input type="text" name="valicode" /><img src="/plugins/index.php?q=imgcode" alt="���ˢ��" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" align="absmiddle" style="cursor:pointer;"></div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><input type="submit" value="ȷ��ת��" />��ת�˽�ֱ�ӽ���ƽ̨�˻�</div>
	</div>
</form>

{elseif $_A.query_type=="tgzhuanzlist"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
        <td width="" class="main_td">ID</td>
		<td width="" class="main_td">������</td>
		<td width="" class="main_td">ת�˽��</td>
		<td width="" class="main_td">�����û�</td>
		<td width="" class="main_td">�����û�</td>
		<td width="" class="main_td">״̬</td>
		<td width="" class="main_td">ת��ʱ��</td>
	</tr>
	{foreach from=$_A.tgzhuanzlist key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td>{$item.id}</td>
		<td>{$item.trade_no}</td>
		<td>{$item.amount}</td>
		<td>{$item.out_username}</td>
		<td>{$item.in_username}</td>
		<td>{if $item.status==1}ת�˳ɹ�{elseif $item.status==2}ת��ʧ��{else}�ȴ�ת��{/if}</td>
		<td>{$item.addtime}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="7" class="action">
		�����ţ�<input type="text" name="trade_no" id="trade_no" value="{$magic.request.trade_no}"/>
		�����û���<input type="text" name="out_username" id="out_username" value="{$magic.request.out_username}"/>
		�����û���<input type="text" name="in_username" id="in_username" value="{$magic.request.in_username}"/>
		<input type="button" value="����" onclick="sousuo()" />
		</td>
	</tr>
	<tr>
		<td colspan="7" class="page">
		{$_A.showpage}
		</td>
	</tr>
</table>
{/if}
<script type="text/javascript">
var url = '{$_A.query_url}/{$_A.query_type}{$_A.site_url}';
{literal}
function sousuo(){
	var sou = "";
	if(arguments[0]=="excel"){
		sou += "&type=excel";
	}
	var trade_no = $("#trade_no").val() || "";
	var status = $("#status").val() || "";
	var dotime1 = $("#dotime1").val() || "";
	var keywords = $("#keywords").val() || "";
	var username = $("#username").val() || "";
    var username2 = $("#username2").val() || "";
	var dotime2 = $("#dotime2").val() || "";
	var typeaction = $("#typeaction").val() || "";
	var pertainbank = $("#pertainbank").val() || "";
	var account = $("#account").val() || "";
	var out_username = $("#out_username").val() || "";
	var in_username = $("#in_username").val() || "";
	if (trade_no!=""){
		sou += "&trade_no="+trade_no;
	}
	if (status!=""){
		sou += "&status="+status;
	}
	if (username!=""){
		 sou += "&username="+username;
	}
	if (username2!=""){
		 sou += "&username2="+username2;
	}
	if (trade_no!=""){
		 sou += "&trade_no="+trade_no;
	}
	if (keywords!=""){
		 sou += "&keywords="+keywords;
	}
	if (dotime1!=""){
		 sou += "&dotime1="+dotime1;
	}
	if (dotime2!=""){
		 sou += "&dotime2="+dotime2;
	}
	if (typeaction!=""){
		 sou += "&typeaction="+typeaction;
	}
	if(pertainbank!=""){
		sou += "&pertainbank="+pertainbank;
	}
	if(account!=""){
		if(account.length!=19){
			alert("�����˺���������");
			return;
		}
		sou += "&account="+account;
	}
	if(out_username!=""){
		sou += "&out_username="+out_username;
	}
	if(in_username!=""){
		sou += "&in_username="+in_username;
	}
	location.href=url+sou;
}
</script>
{/literal}