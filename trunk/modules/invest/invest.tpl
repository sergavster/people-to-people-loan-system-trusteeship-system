{if $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	{if $magic.request.user_id==""}
	<form name="form1" method="post" action="" enctype="multipart/form-data" >
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
	</form>
	{else}
	<div class="module_title"><strong>添加用户信息</strong></div>
	
	<form name="form1" method="post" action=""  enctype="multipart/form-data" onsubmit="return check_form();" >
	
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			{$_A.user_result.username|default:$_A.borrow_result.username}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">借款用途：</div>
		<div class="c">
			<script src="/plugins/index.php?q=linkage&name=use&nid=borrow_use&value={$_A.borrow_result.use}"></script> <span >说明借款成功后的具体用途。</span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">借款期限：</div>
		<div class="c">
			<script src="/plugins/index.php?q=linkage&name=time_limit&nid=borrow_time_limit&isid=false&value={$_A.borrow_result.time_limit}"></script> <span >借款成功后,打算以几个月的时间来还清贷款。 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">还款方式：</div>
		<div class="c">
			<script src="/plugins/index.php?q=linkage&name=style&nid=borrow_style&value={$_A.borrow_result.style}"></script> <span >按季度分期还款是指贷款者借款成功后,每月还息，按季还本。</span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">借贷总金额：</div>
		<div class="c">
				<input type="text" name="account" value="{$_A.borrow_result.account}" /> <span >借款金额应在500元至50,000元之间。交易币种均为人民币。借款成功后, 借款2个月 ,收取当前借款金额2%的管理费,借款三个月以上的每月增加0.2%管理费,冻结10%作为保证金,正常还款完后,网站解冻10%的保证金,保证金以最高年利率21.6%返还给用户。更详尽的信息请查看帮助网站 收费规则 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">年利率：</div>
		<div class="c">
			<input type="text" name="apr" value="{$_A.borrow_result.apr}" /> % <span >按季度分期还款是指贷款者借款成功后,每月还息，按季还本。</span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">最低投标金额：</div>
		<div class="c">
			<script src="/plugins/index.php?q=linkage&name=lowest_account&nid=borrow_lowest_account&isid=false&value={$_A.borrow_result.lowest_account}"></script> <span >允许投资者对一个借款标的投标总额的限制。</span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">最多投标总额：</div>
		<div class="c">
			<script src="/plugins/index.php?q=linkage&name=most_account&nid=borrow_most_account&isid=false&value={$_A.borrow_result.most_account}"></script> <span >设置此次借款融资的天数。融资进度达到100%后直接进行网站的复审</span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">有效时间：</div>
		<div class="c">
			<script src="/plugins/index.php?q=linkage&name=valid_time&nid=borrow_valid_time&isid=false&value={$_A.borrow_result.valid_time}"></script> <span>设置此次借款融资的天数。融资进度达到100%后直接进行网站的复审 </span>
		</div>
	</div>
	<div class="module_title"><strong>设置奖励</strong></div>
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="0" {if $_A.borrow_result.award==0 || $_A.borrow_result.award==""} checked="checked"{/if}>不设置奖励</div>
		<div class="c">
			 <span>如果您设置了奖励金额，将会冻结您帐户中相应的账户余额。如果要设置奖励，请确保您的帐户有足够 的账户余额。 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="1" {if $_A.borrow_result.award==1 } checked="checked"{/if}/>按固定金额分摊奖励：</div>
		<div class="c">
			<input type="text" name="part_account" value="{$_A.borrow_result.part_account}" size="5" />元 <span>不能低于5元,不能高于总标的金额的2%，且请保留到“元”为单位。这里设置本次标的要奖励给所有投标用户的总金额。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="2" {if $_A.borrow_result.award==2 } checked="checked"{/if}/>按投标金额比例奖励：</div>
		<div class="c">
			<input type="text" name="funds" value="{$_A.borrow_result.funds}" size="5" />%  <span>范围：0.1%~2% ，这里设置本次标的要奖励给所有投标用户的奖励比例。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="checkbox" name="is_false" value="1" {if $_A.borrow_result.is_false==1 } checked="checked"{/if}/>标的失败时也同样奖励：</div>
		<div class="c">
			  <span>如果您勾选了此选项，到期未满标或复审失败时同样会奖励给投标用户。如果没有勾选，标的失败时会把奖励金额解冻回账户余额。   </span>
		</div>
	</div>
	
	<div class="module_title"><strong>帐户信息公开</strong></div>
	<div class="module_border">
		<div class="w">公开我的帐户资金情况：</div>
		<div class="c">
			<input type="checkbox" name="open_account" value="1" {if $_A.borrow_result.open_account==1 } checked="checked"{/if}/> <span> 如果您勾上此选项，将会实时公开您帐户的：账户总额、可用余额、冻结总额。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w">公开我的借款资金情况：</div>
		<div class="c">
			<input type="checkbox" name="open_borrow" value="1" {if $_A.borrow_result.open_borrow==1 } checked="checked"{/if}/> <span>如果您勾上此选项，将会实时公开您帐户的：借款总额、已还款总额、未还款总额、迟还总额、逾期总额。 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w">公开我的投标资金情况：</div>
		<div class="c">
			<input type="checkbox" name="open_tender" value="1" {if $_A.borrow_result.open_tender==1 } checked="checked"{/if}/> <span>如果您勾上此选项，将会实时公开您帐户的：投标总额、已收回总额、待收回总额。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w">公开我的信用额度情况：</div>
		<div class="c">
			<input type="checkbox" name="open_credit" value="1" {if $_A.borrow_result.open_credit==1 } checked="checked"{/if}/> <span>如果您勾上此选项，将会实时公开您帐户的：最低信用额度、最高信用额度。  </span>
		</div>
	</div>
	
	<div class="module_title"><strong>详细信息</strong></div>
	<div class="module_border">
		<div class="l">标题：</div>
		<div class="c">
			<input type="text" name="name" value="{$_A.borrow_result.name}" size="50" /> 
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">信息：</div>
		<div class="c">
			{editor name="content" type="sinaeditor" value="$_A.borrow_result.content"}
		</div>
	</div>
	<!--基本资料 结束-->
		
	<div class="module_submit" >
		{if $_A.query_type == "edit"}<input type="hidden"  name="id" value="{$magic.request.id}" />{/if}
		<input type="hidden"  name="user_id" value="{$magic.request.user_id}" />
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
	</form>
	
	
	{/if}
</div>
{literal}
<script>


function check_form(){
	 var frm = document.forms['form1'];
	 var name = frm.elements['name'].value;
	 var award = frm.elements['award'].value;
	 var part_account = frm.elements['part_account'].value;
	 var errorMsg = '';
	  if (name.length == 0 ) {
		errorMsg += '标题必须填写' + '\n';
	  }
	   if (award ==1 && part_account<5) {
		errorMsg += '奖励金额不能小于5元' + '\n';
	  }
	  
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return true;
	  }
}

</script>
{/literal}
{elseif $_A.query_type == "view"}
<div class="module_add">
	
	<form name="form1" method="post" action="" onsubmit="return check_form();" enctype="multipart/form-data" >
	<div class="module_title"><strong>证件查看</strong></div>


	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			{$_A.user_result.username|default:$_A.borrow_result.username}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">借款用途：</div>
		<div class="c">
			{$_A.borrow_result.use|linkage}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">借款期限：</div>
		<div class="c">
		{$_A.borrow_result.time_limit|linkage:"borrow_time_limit"}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">还款方式：</div>
		<div class="c">
			{$_A.borrow_result.style|linkage}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">借贷总金额：</div>
		<div class="c">
				{$_A.borrow_result.account} 元
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">年利率：</div>
		<div class="c">
			{$_A.borrow_result.apr} %
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">最低投标金额：</div>
		<div class="c">
			{$_A.borrow_result.lowest_account|linkage:"borrow_lowest_account"}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">最多投标总额：</div>
		<div class="c">
			{$_A.borrow_result.most_account|linkage:"borrow_most_account"}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">有效时间：</div>
		<div class="c">
			{$_A.borrow_result.valid_time|linkage:"borrow_valid_time"}
		</div>
	</div>
	<div class="module_title"><strong>设置奖励</strong></div>
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="0" {if $_A.borrow_result.award==0 || $_A.borrow_result.award==""} checked="checked"{/if} disabled="disabled">不设置奖励</div>
		<div class="c">
			 <span>如果您设置了奖励金额，将会冻结您帐户中相应的账户余额。如果要设置奖励，请确保您的帐户有足够 的账户余额。 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="1" {if $_A.borrow_result.award==1 } checked="checked"{/if} disabled="disabled"/>按固定金额分摊奖励：</div>
		<div class="c">
			<input type="text" name="part_account" value="{$_A.borrow_result.part_account}" size="5" disabled="disabled"/>元 <span>不能低于5元,不能高于总标的金额的2%，且请保留到“元”为单位。这里设置本次标的要奖励给所有投标用户的总金额。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="2" {if $_A.borrow_result.award==2 } checked="checked"{/if} disabled="disabled"/>按投标金额比例奖励：</div>
		<div class="c">
			<input type="text" name="funds" value="{$_A.borrow_result.funds}" size="5" disabled="disabled"/>%  <span>范围：0.1%~2% ，这里设置本次标的要奖励给所有投标用户的奖励比例。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="checkbox" name="is_false" value="1" {if $_A.borrow_result.is_false==1 } checked="checked"{/if}  disabled="disabled"/>标的失败时也同样奖励：</div>
		<div class="c">
			  <span>如果您勾选了此选项，到期未满标或复审失败时同样会奖励给投标用户。如果没有勾选，标的失败时会把奖励金额解冻回账户余额。   </span>
		</div>
	</div>
	
	<div class="module_title"><strong>帐户信息公开</strong></div>
	<div class="module_border">
		<div class="w">公开我的帐户资金情况：</div>
		<div class="c">
			<input type="checkbox" name="open_account" value="1" {if $_A.borrow_result.open_account==1 } checked="checked"{/if} disabled="disabled"/> <span> 如果您勾上此选项，将会实时公开您帐户的：账户总额、可用余额、冻结总额。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w">公开我的借款资金情况：</div>
		<div class="c">
			<input type="checkbox" name="open_borrow" value="1" {if $_A.borrow_result.open_borrow==1 } checked="checked"{/if} disabled="disabled"/> <span>如果您勾上此选项，将会实时公开您帐户的：借款总额、已还款总额、未还款总额、迟还总额、逾期总额。 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w">公开我的投标资金情况：</div>
		<div class="c">
			<input type="checkbox" name="open_tender" value="1" {if $_A.borrow_result.open_tender==1 } checked="checked"{/if} disabled="disabled"/> <span>如果您勾上此选项，将会实时公开您帐户的：投标总额、已收回总额、待收回总额。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w">公开我的信用额度情况：</div>
		<div class="c">
			<input type="checkbox" name="open_credit" value="1" {if $_A.borrow_result.open_credit==1 } checked="checked"{/if} disabled="disabled"/> <span>如果您勾上此选项，将会实时公开您帐户的：最低信用额度、最高信用额度。  </span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">添加时间/IP:</div>
		<div class="c">
			{ $_A.attestation_result.addtime|date_format:'Y-m-d H:i:s'}/{ $_A.attestation_result.addip}</div>
	</div>
	
	
	<div class="module_title"><strong>审核此借款</strong></div>
	
	<div class="module_border">
		<div class="l">状态:</div>
		<div class="c">
		<input type="radio" name="status" value="0" {if $_A.attestation_result.status==0} checked="checked"{/if} />等待审核  <input type="radio" name="status" value="1" {if $_A.attestation_result.status==1} checked="checked"{/if}/>审核通过 <input type="radio" name="status" value="2" {if $_A.attestation_result.status==2} checked="checked"{/if}/>审核不通过 </div>
	</div>
	
	<div class="module_border" >
		<div class="l">通过所应的积分:</div>
		<div class="c">
			<input type="text" name="jifen" value="{ $_A.attestation_result.jifen}" size="5">
		</div>
	</div>
	
	<div class="module_border" >
		<div class="l">审核备注:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5">{ $_A.attestation_result.verify_remark}</textarea>
		</div>
	</div>

	<div class="module_submit" >
		<input type="hidden" name="id" value="{ $_A.attestation_result.id }" />
		
		<input type="submit"  name="reset" value="审核此证件" />
	</div>
	</form>
</div>
{literal}
<script>
function check_form(){
	 var frm = document.forms['form1'];
	 var verify_remark = frm.elements['verify_remark'].value;
	 var errorMsg = '';
	  if (verify_remark.length == 0 ) {
		errorMsg += '备注必须填写' + '\n';
	  }
	  
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return true;
	  }
}

</script>
{/literal}
{elseif $_A.query_type=="list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">用户名称</td>
			<td width="" class="main_td">借款标题</td>
			<td width="" class="main_td">借款金额</td>
			<td width="" class="main_td">客服人员</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{ foreach  from=$_A.borrow_list key=key item=item}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1" align="center" >{ $item.id}</td>
			<td class="main_td1" align="center"><a href="{$_A.query_url}&username={$item.username}">{$item.username}</a></td>
			<td class="main_td1" align="center" >{$item.name}</td>
			<td class="main_td1" align="center" >{$item.account}</td>
			<td class="main_td1" align="center" >{$item.kefu_username}</td>
			<td class="main_td1" align="center" >{ if $item.status ==1}审核通过{ elseif $item.status ==0}等待审核{else}审核未通过{/if}</td>
			<td class="main_td1" align="center" ><a href="{$_A.query_url}/view{$site_url}&user_id={$item.user_id}&id={$item.id}">审核</a> <a href="{$_A.query_url}/edit{$site_url}&user_id={$item.user_id}&id={$item.id}">修改</a></td>
		</tr>
		{ /foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatl">
		
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username}"/> 状态<select id="status" ><option value="">全部</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>已通过</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>未通过</option></select> <input type="button" value="搜索" / onclick="sousuo()">
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
<script>
var url = '{$_A.query_url}';
{literal}
function sousuo(){
	var sou = "";
	var username = $("#username").val();
	if (username!=""){
		sou += "&username="+username;
	}
	var status = $("#status").val();
	if (status!=""){
		sou += "&status="+status;
	}
	if (sou!=""){
	location.href=url+sou;
	}
}
</script>
{/literal}


{/if}