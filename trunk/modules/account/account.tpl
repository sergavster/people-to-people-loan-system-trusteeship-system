{if $_A.query_type == "new" || $_A.query_type == "edit"}
<!-- 帐户信息列表 开始 -->
{elseif $_A.query_type=="list"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">用户名</td>
			<td width="" class="main_td">真实姓名</td>
			<td width="" class="main_td">总余额</td>
			<td width="" class="main_td">可用余额</td>
			<td width="" class="main_td">冻结金额</td>
			<td width="" class="main_td">待收金额</td>
            <td width="" class="main_td">待还金额</td>
            <td width="" class="main_td">净资产</td>
		</tr>
		{foreach from=$_A.account_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{ $item.user_id}</td>
			<td><a href="javascript:void(0)" onclick='tipsWindown("用户详细信息查看","url:get?{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene",500,230,"true","","true","text");'>{$item.username}</a></td>
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
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> <input type="button" value="搜索" onclick="sousuo()" />
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="9" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table><!-- 帐户信息列表 结束 -->

{elseif $_A.query_type=="listTJ"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="" class="main_td">用户编号</td>
			<td width="" class="main_td">用户名</td>
			<td width="" class="main_td">真实姓名</td>
			<td width="" class="main_td">总余额</td>
			<td width="" class="main_td">可用余额</td>
			<td width="" class="main_td">冻结金额</td>
			<td width="" class="main_td">待收金额</td>
            <td width="" class="main_td">待还金额</td>
            <td width="" class="main_td">净资产</td>
			<td width="" class="main_td">对账时间</td>
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
		<input type="button" onclick="javascript:location.href='{$_A.query_url}/listTJ&type=excel'" value="导出列表" />
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username}"/> <input type="button" value="搜索" onclick="sousuo()" />
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

<!-- 负数监控 开始 -->
{elseif $_A.query_type == "fs_list"}
<table border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">用户编号</td>
			<td width="" class="main_td">用户名</td>
			<td width="" class="main_td">真实姓名</td>
			<td width="" class="main_td">总余额</td>
			<td width="" class="main_td">可用余额</td>
			<td width="" class="main_td">冻结金额</td>
			<td width="" class="main_td">待收金额</td>
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
</table><!-- 负数监控 结束 -->
<!-- 用户提成 开始 -->
{elseif $_A.query_type=="ticheng"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="" class="main_td">时间</td>
			<td width="" class="main_td">用户名</td>
			<td width="" class="main_td">好友投资总额(月)</td>
		</tr>
		{foreach from=$_A.account_ticheng key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.addtimes}</td>
			<td><a href="javascript:void(0)" onclick='tipsWindown("用户详细信息查看","url:get?{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene",500,230,"true","","true","text");'>{$item.usernames}</a></td>
			<td >{$item.money}</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="4" class="action">
			<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="导出列表" />
			</div>
			<div class="floatr">
				用户名：<input type="text" name="username" id="username" value="{$magic.request.username}"/><input type="button" value="搜索" onclick="sousuo()" />
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="4" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table>  <!-- 用户提成 结束 -->

<!-- vip提成 开始 -->
{elseif $_A.query_type=="vipTC"}
<table border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">推广者用户名</td>	
			<td width="" class="main_td">下线用户名</td>
			<td width="" class="main_td">真实姓名</td>
			<td width="" class="main_td">注册时间</td>
			<td width="" class="main_td">是否VIP会员</td>
			<td width="" class="main_td">应得提成收入</td>
			<td width="" class="main_td">实际提成收入(已支付)</td>
		</tr>
		{foreach  from=$_A.vipTC_list key=key item=item}
		<tr>
			<td>{$item.inviteUserName}</td>
			<td>{$item.username}</td>
			<td>{$item.realname}</td>
			<td>{$item.addtime|date_format}</td>
			<td>{if $item.vip_status == 1}是{else}否{/if}</td>
			<td>{if $item.vip_status == 1}100元{else}0元{/if}</td>
			<td>{$item.invite_money}元</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
		介绍人用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
                  下线人用户名：<input type="text" name="username2" id="username2" value="{$magic.request.username2|urldecode}"/>
                  <input type="button" value="搜索" onclick="sousuo()" />
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
<!-- vip提成 结束 -->

<!-- 资金对账表 开始 -->
{elseif $_A.query_type=="moneyCheck"}
<table border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr >
			<td width="" class="main_td">用户名</td>
			<td width="" class="main_td">资金总额</td>
			<td width="" class="main_td">可用资金</td>
			<td width="" class="main_td">冻结资金</td>
			<td width="" class="main_td">待收资金(1)</td>
			<td width="" class="main_td">待收资金(2)</td>
			<td width="" class="main_td">充值资金(1)</td>
			<td width="" class="main_td">充值资金(2)</td>
			<td width="" class="main_td">其中：线上</td>
			<td width="" class="main_td">其中：线下1</td>
			<td width="" class="main_td">其中：线下2</td>
			<td width="" class="main_td">成功提现金额</td>
			<!--td width="" class="main_td">提现实际到账</td>
			<td width="" class="main_td">提现费用</td-->
			<td width="" class="main_td">投标奖励金额</td>
			<!--td width="" class="main_td">投标已收资金</td-->
			<td width="" class="main_td">投标已收利息</td>
			<td width="" class="main_td">投标待收利息</td>
			<!--td width="" class="main_td">借款总金额</td-->
			<td width="" class="main_td">借款标奖励</td>
			<td width="" class="main_td">借款管理费</td>
			<td width="" class="main_td">待还本金</td>
			<td width="" class="main_td">待还利息</td>
			<td width="" class="main_td">借款已还利息</td>
			<td width="" class="main_td">系统扣费</td>
			<td width="" class="main_td">推广奖励</td>
			<td width="" class="main_td">VIP扣费</td> 
			<td width="" class="main_td">逾期总额</td>
			<!--td width="" class="main_td">资金总额1</td>
			<td width="" class="main_td">资金总额2</td-->
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
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
            <input type="button" value="搜索" onclick="sousuo()" />
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="24" class="page">
			{$_A.showpage}
			</td>
		</tr>
	</form>
</table>  <!-- 资金对账表 结束 -->
                        <!-- 提现参考 开始 -->
{elseif $_A.query_type=="cashCK"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">用户名</td>
			<td width="" class="main_td">真实姓名</td>
			<td width="" class="main_td">投资担保额度</td>
			<td width="" class="main_td">使用的信用额度（X）</td>
			<td width="" class="main_td">净资产(W)</td>
			<td width="" class="main_td">待收利息(E)</td>
 			<td width="" class="main_td">提现标准（W+1.1X-E）</td>
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
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> <input type="button" value="搜索" onclick="sousuo()" />
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
<!-- 提现参考 结束 -->
<!--提现记录列表 开始-->
{elseif $_A.query_type=="cash"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">用户名称</td>
			<td width="" class="main_td">真实姓名</td>
			<!--<td width="" class="main_td">提现账号</td>
			<td width="" class="main_td">提现银行</td>
			<td width="" class="main_td">支行</td>-->
			<td width="" class="main_td">提现总额</td>
			<td width="" class="main_td">到账金额</td>
			<td width="" class="main_td">手续费</td>
			<!--<td width="" class="main_td">红包抵扣</td>-->
			<td width="" class="main_td">提现时间</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">操作</td>
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
			<td >{if $item.status==0}审核中{elseif $item.status==1}已通过 {elseif $item.status==2}被拒绝{elseif $item.status==-1}等待用户确认{/if}</td>
			<td ><a href="{$_A.query_url}/cash_view{$_A.site_url}&id={$item.id}">审核/查看</a></td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="9" class="action">
		<div class="floatl">
			<input type="button" value="导出当前条件报表" onclick="sousuo('excel')" />
		</div>
		<div class="floatr">
		提现账号：<input type="text" name="account" id="account" value="{$magic.request.account}" maxlength="19" />
		充值时间：<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()"/>到 <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()"/>	
		用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		状态：<select id="status"><option value="">全部</option><option value="-1" {if $magic.request.status==-1} selected="selected"{/if}>等待用户确认</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>已通过</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>待审核</option><option value="2" {if $magic.request.status=="2"} selected="selected"{/if}>审核失败</option></select><input type="button" value="搜索" onclick="sousuo()" />
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
<!--提现记录列表 结束-->
<!--提现审核 开始-->
{elseif $_A.query_type == "cash_view"}
<div class="module_add">
	<form name="form1" method="post" action="">
	<div class="module_title"><strong>提现审核/查看</strong></div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			{$_A.account_cash_result.username}		</div>
	</div>
	<!--
	<div class="module_border">
		<div class="l">提现银行：</div>
		<div class="c">
			{$_A.account_cash_result.bank_name }
		</div>
	</div>
	<div class="module_border">
		<div class="l">提现支行：</div>
		<div class="c">
			{$_A.account_cash_result.branch }
		</div>
	</div>
	<div class="module_border">
		<div class="l">提现账号：</div>
		<div class="c">
			{$_A.account_cash_result.account }
		</div>
	</div>
	-->
	<div class="module_border">
		<div class="l">提现总额：</div>
		<div class="c">
			{$_A.account_cash_result.total }
		</div>
	</div>
	<div class="module_border">
		<div class="l">到账金额：</div>
		<div class="c">
			{$_A.account_cash_result.credited }
		</div>
	</div>
	<div class="module_border">
		<div class="l">手续费：</div>
		<div class="c">
			{$_A.account_cash_result.fee }
		</div>
	</div>
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
		{if $_A.account_cash_result.status==0}提现审核中{elseif $_A.account_cash_result.status==1}提现已通过 {elseif $_A.account_cash_result.status==2}提现被拒绝{elseif $_A.account_cash_result.status==-1}等待用户确认{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">添加时间/IP:</div>
		<div class="c">
			{$_A.account_cash_result.addtime|date_format:'Y-m-d H:i:s'}/{ $_A.account_cash_result.addip}</div>
	</div>
	{if $_A.account_cash_result.status==0}
	<div class="module_title"><strong>审核此提现信息</strong></div>
	<div class="module_border">
		<div class="l">状态:</div>
		<div class="c">
		<input type="radio" name="status" value="0" {if $_A.account_cash_result.status==0} checked="checked"{/if} />等待审核  
		<input type="radio" name="status" value="-1" {if $_A.account_cash_result.status==-1} checked="checked"{/if}/>审核通过 
		<input type="radio" name="status" value="2" {if $_A.account_cash_result.status==2} checked="checked"{/if}/>审核不通过 
		</div>
	</div>
	<div class="module_border" >
		<div class="l">到账金额:</div>
		<div class="c">
			<input type="text" name="credited" readonly="readonly" style="background:#CCCCCC" value="{ $_A.account_cash_result.credited}" size="10">
		</div>
	</div>
	<div class="module_border" >
		<div class="l">手续费:</div>
		<div class="c">
			<input type="text" name="fee" value="{$_A.account_cash_result.fee}" size="5" onBlur="updateFee({$_A.account_cash_result.total})" />			{literal}			<script type="text/javascript">			function updateFee(total){				var form = document.forms['form1'];				var fee = parseFloat(form.elements['fee'].value);
				var hongbao = parseFloat(form.elements['hongbao'].value);				if(isNaN(fee)){					fee = 0;				}				if(fee<hongbao){					alert("手续费不能小于抵扣的红包");					form.elements['fee'].value = hongbao;				}else if(fee>total/2){					alert("手续费不能大于提现总额的50%");					form.elements['fee'].value = total/2;				}else{					form.elements['fee'].value = fee;				}				form.elements['credited'].value = parseFloat(total)-parseFloat(form.elements['fee'].value)+parseFloat(form.elements['hongbao'].value);			}
			function check_form(){
				var frm = document.forms['form1'];
				var verify_remark = frm.elements['verify_remark'].value;
				var errorMsg = '';
				if(verify_remark.length == 0 ) {
					errorMsg += '--备注必须填写' + '\n';
				}
				if(errorMsg.length == 0){
					frm.submit();
					frm.elements['reset'].disabled=true;
					frm.elements['reset'].value="审核提交中....";
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
		<div class="l">红包抵扣:</div>
		<div class="c">
			<input type="text" name="hongbao" readonly="readonly" style="background:#CCCCCC" value="{ $_A.account_cash_result.hongbao}" size="10">
		</div>
	</div>
	-->
	<input type="hidden" name="hongbao" value="{ $_A.account_cash_result.hongbao}">
	<div class="module_border" >
		<div class="l">审核备注:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5">{$_A.account_result.verify_remark}</textarea>
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="id" value="{ $_A.account_cash_result.id }" />
		<input type="hidden" name="user_id" value="{ $_A.account_cash_result.user_id }" />
		<input type="button" name="reset" value="审核此提现信息" onclick="check_form()" />
	</div>
	{else}
	<div class="module_border">
		<div class="l">审核信息：</div>
		<div class="c">
			审核人：{ $_A.account_cash_result.verify_username },审核时间：{ $_A.account_cash_result.verify_time|date_format:"Y-m-d H:i" },审核备注：{ $_A.account_cash_result.verify_remark}
		</div>
	</div>
	{/if}
	</form>
</div>


<!--充值记录列表 开始-->
{elseif $_A.query_type=="recharge"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
            <td width="*" class="main_td">流水号</td>
			<td width="*" class="main_td">用户名称</td>
			<td width="*" class="main_td">真实姓名</td>
			<td width="" class="main_td">类型</td>
			<td width="" class="main_td">所属银行</td>
			<td width="" class="main_td">充值金额</td>
			<td width="" class="main_td">费用</td>
			<td width="" class="main_td">到账金额</td>
			<td width="" class="main_td">奖励红包</td>
			<td width="" class="main_td">充值时间</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">银行返回</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{ foreach  from=$_A.account_recharge_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td >{$item.id}</td>			<td >{$item.trade_no}</td>
			<td><a href="{$_A.query_url}/recharge&username={$item.username}&a=cash">{$item.username}</a></td>
			<td >{$item.realname}</td>
			<td >{if $item.type==1}网上充值{else}线下充值{/if}</td>
			<td >{if $item.payment==0}手动充值{else}{ $item.payment_name}{/if}</td>
			<td >{$item.money}元</td>
			<td >{$item.fee}元</td>
			<td ><font color="#FF0000">{$item.total}元</font></td>
			<td >{$item.hongbao}元</td>
			<td ><font color="#FF3300">提交：{$item.addtime|date_format:"Y-m-d H:i:s"}</font><br/>
			<font color="#aaaaaa">完成：{$item.verify_time|date_format:"Y-m-d H:i:s"}</font>
			</td>
			<td >{if $item.status==0 || $item.status== -1 }<font color="#6699CC">待审核</font>{elseif  $item.status==1} 成功 {else}<font color="#FF0000">失败</font>{/if}</td>
            <td >{if $item.return==""&& $item.type==1  }<span style="color:#F00">线上未到帐</span>{elseif $item.return<>""&& $item.type==1} 线上已到账{else}线下核对{/if}</td>
			<td ><a href="{$_A.query_url}/recharge_view{$_A.site_url}&id={$item.id}">审核/查看</a></td>
		</tr>
		{/foreach}
	<tr>
		<td colspan="14" class="action">
		<div class="floatl">		<input type="button" value="导出当前报表" onclick="sousuo('excel')" />
		</div>
		<div class="floatr">		所属银行：<select id="pertainbank"><option value="0">全部</option><option value="-1">线下充值</option><option value="-2">网上充值</option><option value="-3">手动充值</option>		{foreach from=$_A.account_payment_list item="var"}		{if $magic.request.pertainbank==$var.id}		<option value="{$var.id}" selected="selected">{$var.name}</option>		{else}		<option value="{$var.id}">{$var.name}</option>		{/if}		{/foreach}</select>
		充值时间：<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()"/> 到 <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()"/>
		用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		流水号：<input type="text" name="trade_no" id="trade_no" value="{$magic.request.trade_no}"/> 
		状态<select id="status" ><option value=''>全部</option><option value="-1" {if $magic.request.status=="-1"} selected="selected"{/if}>未审核</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>审核成功</option><option value="2" {if $magic.request.status=="2"} selected="selected"{/if}>审核失败</option></select> <input type="button" value="搜索" onclick="sousuo()" />
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
<!--充值记录列表 结束-->
<!--批量导入充值记录-->
{elseif $_A.query_type=="rechargefromexcel"}
<form action='' method='post' enctype="multipart/form-data">
<div class="module_border">
	<div class="l">导入文件：</div>
	<div class="c">
		<input type="file" name="excelfile" />
	</div>
	<div class="c">
		<input type="submit" value="提交导入" />
	</div>
</div>
</form>
<!--充值审核 开始-->
{elseif $_A.query_type == "recharge_view"}
<div class="module_add">
	<form name="form1" method="post" action="">
	<div class="module_title"><strong>充值查看</strong></div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			<a href="javascript:void(0)" onclick='tipsWindown("用户详细信息查看","url:get?{$_A.admin_url}&q=module/user/view&user_id={$_A.account_recharge_result.user_id}&type=scene",500,230,"true","","true","text");'>{ $_A.account_recharge_result.username}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">充值类型：</div>
		<div class="c">
			{if $_A.account_recharge_result.type==1}网上充值{else}线下充值{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">支付方式：</div>
		<div class="c">
			{$_A.account_recharge_result.payment_name|default:管理员添加充值}
		</div>
	</div>
	<div class="module_border">
		<div class="l">充值总额：</div>
		<div class="c">
			{$_A.account_recharge_result.money}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">费用：</div>
		<div class="c">
			{$_A.account_recharge_result.fee}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">奖励红包：</div>
		<div class="c">
			{$_A.account_recharge_result.hongbao}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">现金奖励：</div>
		<div class="c">
			{$_A.account_recharge_result.reward}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">实际到账：</div>
		<div class="c">
			{$_A.account_recharge_result.total}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">用户备注：</div>
		<div class="c">
		{$_A.account_recharge_result.remark}
		</div>
	</div>
	<div class="module_border">
		<div class="l">流水号：</div>
		<div class="c">
		{$_A.account_recharge_result.trade_no}
		</div>
	</div>
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
		{if $_A.account_recharge_result.status==0}等待审核{elseif $_A.account_recharge_result.status==1} 充值成功 {elseif $_A.account_recharge_result.status==2}充值失败{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">添加时间/IP:</div>
		<div class="c">
			{$_A.account_recharge_result.addtime|date_format:'Y-m-d H:i:s'}/{$_A.account_recharge_result.addip}</div>
	</div>
	{if $_A.account_recharge_result.status==0}
	<div class="module_title"><strong>审核此充值信息</strong></div>
	<div class="module_border">
		<div class="l">状态:</div>
		<div class="c">
	<input type="radio" name="status" value="1"/>充值成功   <input type="radio" name="status" value="2"  checked="checked"/>充值失败 </div>
	</div>
	<div class="module_border" >
		<div class="l">到账金额:</div>
		<div class="c">
			{$_A.account_recharge_result.total}元
			<input type="hidden" name="total" value="{$_A.account_recharge_result.total}" size="15" readonly="readonly">
		</div>
	</div>
	{if $_A.account_recharge_result.type!=1}
	<div class="module_border" >
		<div class="l">到账现金奖励:</div>
		<div class="c">{$_A.account_recharge_result.reward}元</div>
	</div>
	{/if}
	<div class="module_border" >
		<div class="l">审核备注:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5">{ $_A.account_recharge_result.verify_remark}</textarea>
		</div>
	</div>
	<div class="module_border" >
		<div class="l">验证码:</div>
		<div class="c">
		<input type="text" size="5" maxlength="4" name="valicode">
		<img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="点击刷新" src="/plugins/index.php?q=imgcode">
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="id" value="{ $_A.account_recharge_result.id }" />
		<input type="button" name="reset" value="审核此充值信息" onclick="check_form()" />
	</div>
	{else}
		{if $_A.account_recharge_result.type==2 }
	<div class="module_border">
		<div class="l">审核信息：</div>
		<div class="c">
			审核人：{ $_A.account_result.verify_username },审核时间：{ $_A.account_result.verify_time|date_format:"Y-m-d H:i" },审核备注：{ $_A.account_result.verify_remark}
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
		errorMsg += '--备注必须填写' + '\n';
	}
	if(valicode.length != 4){
		errorMsg += '--验证码输入有误' + '\n';
	}
	if(errorMsg.length == 0){
		frm.submit();
		frm.elements['reset'].disabled=true;
		frm.elements['reset'].value="审核提交中....";
		submit_fool();
	}else{
		alert(errorMsg);
		return;
	}
}
</script>
{/literal}
<!--充值审核 结束-->

<!--添加充值记录 开始-->
{elseif $_A.query_type == "recharge_new"}
<div class="module_add">
	<form name="form1" method="post" action="" enctype="multipart/form-data">
	<div class="module_title"><strong>添加充值</strong></div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			<input type="text" name="username" /><font style="color:red">*</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">类型：</div>
		<div class="c">
			线下充值<input type="hidden" name="type" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">金额：</div>
		<div class="c">
			<input type="text" name="money" maxlength="6" /><font style="color:red">*</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">备注：</div>
		<div class="c">
			<input type="text" name="remark" value="在线支付投标补充值" /><font style="color:red">*</font>
		</div>
	</div>
	<!-- <div class="module_border">
		<div class="l">批量添加：</div>
		<div class="c">
			<input type="file" name="excelfile" />
			<input type="button" value="批量添加" onclick="document.forms['form1'].submit();this.disabled=true;" />
		</div>
	</div> -->
	<div class="module_border">
		<div class="l"></div>
		<div class="c" style="color:red">
			使用此功能前请先和用户确认是否通过在线支付投标造成了资金错误,让用户提供充值证明
		</div>
	</div>
	<div class="module_submit" >
		<input type="button" name="reset" value="确认充值" onclick="check_form()" />
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
		errorMsg += '--备注必须填写' + '\n';
	}
	if(username.length == 0){
		errorMsg += '--用户名必须填写' + '\n';
	}
	if(money.length == 0){
		errorMsg += '--金额必须填写' + '\n';
	}
	if(errorMsg.length == 0){
		frm.submit();
		frm.elements['reset'].disabled=true;
		frm.elements['reset'].value="充值提交中...";
		submit_fool();
	}else{
		alert(errorMsg);
		return;
	}
}
</script>
{/literal}
<!--添加充值记录 结束-->
<!--费用扣除 开始-->
{elseif $_A.query_type == "deduct"}
<div class="module_add">
	<form name="form1" method="post" action="">
	<div class="module_title"><strong>费用扣除</strong></div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			<input type="text" name="username" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">类型：</div>
		<div class="c">
			<select name="type">
				<option value="scene_account">现场认证费用</option>
				<option value="vouch_advanced">担保垫付扣费</option>
				<option value="borrow_kouhui">借款人罚金扣回</option>
				<option value="account_other">其他</option>
			</select>
		</div>
	</div>
	<div class="module_border">
		<div class="l">金额：</div>
		<div class="c">
			<input type="text" name="money" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">备注：</div>
		<div class="c">
			<input type="text" name="remark" />比如，现场费用扣除200元
		</div>
	</div>
	<div class="module_border">
		<div class="l">验证码：</div>
		<div class="c"><input  class="user_aciton_input"  name="valicode" type="text" size="8" maxlength="4" style=" padding-top:4px; height:16px; width:70px;"/>&nbsp;<img src="/plugins/index.php?q=imgcode" alt="点击刷新" onClick="this.src='/plugins/index.php?q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>
	<div class="module_submit" >
		<input type="button"  name="reset" value="确定扣除" onclick="check_form()" />
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
		errorMsg += '--备注必须填写' + '\n';
	}
	if(username.length == 0){
		errorMsg += '--用户名必须填写' + '\n';
	}
	if(money.length == 0){
		errorMsg += '--金额必须填写' + '\n';
	}
	if(valicode.length != 4){
		errorMsg += '--验证码输入有误' + '\n';
	}
	if(errorMsg.length == 0){
		frm.submit();
		frm.elements['reset'].disabled=true;
		frm.elements['reset'].value="提交中...";
		submit_fool();
	}else{
		alert(errorMsg);
		return;
	}
}
</script>
{/literal}
<!--费用扣除  结束-->
<!--资金使用记录列表 开始-->
{elseif $_A.query_type=="log"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">用户名称</td>
			<td width="" class="main_td">类型</td>
			<td width="" class="main_td">总金额</td>
			<td width="" class="main_td">操作金额</td>
			<td width="" class="main_td">可用金额</td>
			<td width="" class="main_td">冻结金额</td>
			<td width="" class="main_td">待收金额</td>
			<td width="" class="main_td">交易对方</td>
			<td width="" class="main_td">记录时间</td>
            <td width="" class="main_td">备注</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{foreach from=$_A.account_log_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1" ><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene" class="thickbox" title="用户详细信息查看">{$item.username}</a></td>
			<td>{$item.type|linkage:"account_type"}</td>
			<td>{$item.total}</td>
			<td>{$item.money}</td>
			<td>{$item.use_money}</td>
			<td>{$item.no_use_money|default:0}</td>
			<td>{$item.collection|default:0}</td>
			<td>{$item.to_username|default:系统}</td>
			<td>{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
            <td>{$item.remark}</td>
			<td>--</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="12" class="action">
		<div class="floatl"><input type="button" value="导出当前报表" onclick="sousuo('excel')" /></div>
		<div class="floatr">
		时间：<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1|default:"$day7"|date_format:"Y-m-d"}" size="15" onclick="change_picktime()"/>到<input type="text" name="dotime2" value="{$magic.request.dotime2|default:"$nowtime"|date_format:"Y-m-d"}" id="dotime2" size="15" onclick="change_picktime()"/>   
		{linkages nid="account_type" value="$magic.request.typeaction" name="typeaction" type="value" default="全部"}
		用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		<input type="button" value="搜索" onclick="sousuo()" />
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
<!--资金使用记录列表 结束-->
<!--订单查看-->
{elseif $_A.query_type=="tgviewcash"}
{if $magic.get.view==1}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
        <td width="" class="main_td">用户名</td>
		<td width="" class="main_td">类型</td>
		<td width="" class="main_td">订单号</td>
		<td width="" class="main_td">标id</td>
		<td width="" class="main_td">待收id</td>
		<td width="" class="main_td">待还id</td>
		<td width="" class="main_td">提交时间</td>
		<td width="" class="main_td">返回信息</td>
		<td width="" class="main_td">返回状态</td>
		<td width="" class="main_td">操作</td>
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
		<td><a href="{$_A.query_url}/{$_A.query_type}&view=2&order_id={$item.id}&a=cash">查看</a></td>
	</tr>
	{/foreach}
	<tr>
	<td colspan="11" class="action">
		用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		订单号：<input type="text" name="order_number" id="order_number" value="{$magic.request.order_number}"/>
		标id：<input type="text" name="borrow_id" id="borrow_id" value="{$magic.request.borrow_id}"/>
		待还id：<input type="text" name="repayment_id" id="repayment_id" value="{$magic.request.repayment_id}"/>
		待收id：<input type="text" name="collection_id" id="collection_id" value="{$magic.request.collection_id}"/>
		<input type="button" value="搜索" onclick="tgviewcs()" />
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
	<div class="module_title"><strong>订单查看</strong></div>
	<div class="module_border">
		<div class="l">类型：</div>
		<div class="c">
			{$_A.order_result.tran_code_show}
		</div>
	</div>
	<div class="module_border">
		<div class="l">订单号：</div>
		<div class="c">{$_A.order_result.order_number}</div>
	</div>
	{if $_A.order_result.borrow_id!=0}
	<div class="module_border">
		<div class="l">标id：</div>
		<div class="c">{$_A.order_result.borrow_id}</div>
	</div>
	{/if}
	{if $_A.order_result.collection_id!=0}
	<div class="module_border">
		<div class="l">待收id：</div>
		<div class="c">{$_A.order_result.collection_id}</div>
	</div>
	{/if}
	{if $_A.order_result.repayment_id!=0}
	<div class="module_border">
		<div class="l">待还id：</div>
		<div class="c">{$_A.order_result.repayment_id}</div>
	</div>
	{/if}
	<div class="module_border">
		<div class="l">提交时间：</div>
		<div class="c">{$_A.order_result.tran_time_show}</div>
	</div>
	{if $_A.order_result.user_id!=0}
	<div class="module_border">
		<div class="l">提交用户：</div>
		<div class="c">{$_A.order_result.username}</div>
	</div>
	{/if}
	<!--
	{if $_A.order_result.tran_code=='P009'}
	<div class="module_title"><strong>网站充值状态</strong></div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c">{if $_A.web_recharge.status==1}已到账{else}未到账&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="补单" value="补单" onclick="location.href='{$_A.query_url}/{$_A.query_type}&view=4&order_id={$_A.order_result.id}&a=cash'"><span style="color:red">(注：当宝付到账而平台未到账时进行此补单操作)</span>{/if}</div>
	</div>

	{/if}-->
	<div class="module_title"><strong>返回信息</strong></div>
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

	<div class="module_title"><strong>查询返回信息</strong></div>
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
		<div class="c"><input type="button" name="重新查询" value="重新查询" id="button_cx" onclick="cxdd({$_A.order_result.id})" /></div>
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
	$.jBox.tip('查询中...','loading');
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
        <td width="" class="main_td">用户名</td>
		<td width="" class="main_td">平台总额</td>
		<td width="" class="main_td">平台冻结</td>
		<td width="" class="main_td">平台代收</td>
		<td width="" class="main_td">平台可用</td>
		<td width="" class="main_td">托管可用</td>
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
		用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
		<input type="button" value="搜索" onclick="if($('#username').val()!='{$magic.request.username|urldecode}') location.href='{$_A.query_url}/{$_A.query_type}&page=1&a=cash&username='+$('#username').val()" />
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
	<div class="module_title"><strong>转账信息</strong></div>
	<div class="module_border">
		<div class="l">转出用户名：</div>
		<div class="c"><input type="text" name="out_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">转入用户名：</div>
		<div class="c"><input type="text" name="in_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">转账金额：</div>
		<div class="c"><input type="text" name="account" /></div>
	</div>
	<div class="module_border">
		<div class="l">验证码：</div>
		<div class="c"><input type="text" name="valicode" /><img src="/plugins/index.php?q=imgcode" alt="点击刷新" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" align="absmiddle" style="cursor:pointer;"></div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><input type="submit" value="确认转账" /></div>
	</div>
</form>
{elseif $_A.query_type=="tgzhuanz_c"}
<form action='' method='post'>
	<div class="module_title"><strong>转账信息</strong></div>
	<div class="module_border">
		<div class="l">转出用户名：</div>
		<div class="c"><input type="text" name="out_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">转入用户名：</div>
		<div class="c"><input type="text" name="in_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">转账金额：</div>
		<div class="c"><input type="text" name="account" /></div>
	</div>
	<div class="module_border">
		<div class="l">验证码：</div>
		<div class="c"><input type="text" name="valicode" /><img src="/plugins/index.php?q=imgcode" alt="点击刷新" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" align="absmiddle" style="cursor:pointer;"></div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><input type="submit" value="确认转账" />此转账只会进行托管资金交易，平台资金不会变化</div>
	</div>
</form>

{elseif $_A.query_type=="tgzhuanz_r"}
<form action='' method='post'>
	<div class="module_title"><strong>转账信息</strong></div>
	<div class="module_border">
		<div class="l">转出用户名：</div>
		<div class="c"><input type="text" name="out_user" /></div>
	</div>
	<div class="module_border">
		<div class="l">转账金额：</div>
		<div class="c"><input type="text" name="account" /></div>
	</div>
	<div class="module_border">
		<div class="l">验证码：</div>
		<div class="c"><input type="text" name="valicode" /><img src="/plugins/index.php?q=imgcode" alt="点击刷新" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" align="absmiddle" style="cursor:pointer;"></div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><input type="submit" value="确认转账" />此转账将直接进入平台账户</div>
	</div>
</form>

{elseif $_A.query_type=="tgzhuanzlist"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
        <td width="" class="main_td">ID</td>
		<td width="" class="main_td">订单号</td>
		<td width="" class="main_td">转账金额</td>
		<td width="" class="main_td">出账用户</td>
		<td width="" class="main_td">入账用户</td>
		<td width="" class="main_td">状态</td>
		<td width="" class="main_td">转账时间</td>
	</tr>
	{foreach from=$_A.tgzhuanzlist key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td>{$item.id}</td>
		<td>{$item.trade_no}</td>
		<td>{$item.amount}</td>
		<td>{$item.out_username}</td>
		<td>{$item.in_username}</td>
		<td>{if $item.status==1}转账成功{elseif $item.status==2}转账失败{else}等待转账{/if}</td>
		<td>{$item.addtime}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="7" class="action">
		订单号：<input type="text" name="trade_no" id="trade_no" value="{$magic.request.trade_no}"/>
		出账用户：<input type="text" name="out_username" id="out_username" value="{$magic.request.out_username}"/>
		入账用户：<input type="text" name="in_username" id="in_username" value="{$magic.request.in_username}"/>
		<input type="button" value="搜索" onclick="sousuo()" />
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
			alert("提现账号输入有误");
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