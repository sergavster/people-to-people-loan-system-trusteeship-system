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
			{$_A.user_result.username|default:$_A.limitapp_result.username}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">金额：</div>
		<div class="c">
			<input type="text" name="account" value="{$_A.limitapp_result.account}" /> <span >所要增加的额度。</span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">推荐人：</div>
		<div class="c">
			<input type="text" name="recommend_userid" value="{$_A.limitapp_result.recommend_userid}" /> <span >推荐人，多个用“|”隔开。 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">还款方式：</div>
		<div class="c">
			<textarea name="content" rows="5" cols="50">{$_A.limitapp_result.content}</textarea> <span >按季度分期还款是指贷款者借款成功后,每月还息，按季还本。</span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">其他地方详细说明：</div>
		<div class="c">
				<textarea name="other_content" rows="5" cols="50">{$_A.limitapp_result.other_content}</textarea> <span >其他地方详细说明 </span>
		</div>
	</div>
	
		
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
	<div class="module_title"><strong>申请额度查看</strong></div>


	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			{$_A.user_result.username|default:$_A.limitapp_result.username}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">增加额度：</div>
		<div class="c">
			{$_A.limitapp_result.account} 元
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">详细说明：</div>
		<div class="c">
		{$_A.limitapp_result.content}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">其他地方说明：</div>
		<div class="c">
			{$_A.limitapp_result.other_content}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">添加时间/IP:</div>
		<div class="c">
			{ $_A.limitapp_result.addtime|date_format:'Y-m-d H:i:s'}/{ $_A.limitapp_result.addip}</div>
	</div>
	
	{if $_A.limitapp_result.status!=1}
	<div class="module_title"><strong>审核此借款</strong></div>
	
	<div class="module_border">
		<div class="l">状态:</div>
		<div class="c">
		<input type="radio" name="status" value="0" {if $_A.limitapp_result.status==0} checked="checked"{/if} />等待审核  <input type="radio" name="status" value="1" {if $_A.limitapp_result.status==1} checked="checked"{/if}/>审核通过 <input type="radio" name="status" value="2" {if $_A.limitapp_result.status==2} checked="checked"{/if}/>审核不通过 </div>
	</div>
	
	
	<div class="module_border" >
		<div class="l">审核备注:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5">{ $_A.limitapp_result.verify_remark}</textarea>
		</div>
	</div>

	<div class="module_submit" >
		<input type="hidden" name="id" value="{ $_A.limitapp_result.id }" />
		<input type="hidden" name="user_id" value="{ $_A.limitapp_result.user_id }" />
		<input type="submit"  name="reset" value="审核此申请" />
	</div>
	{else}
	<div class="module_border">
		<div class="l">状态:</div>
		<div class="c">
	{if $_A.limitapp_result.status==1} 审核通过 {elseif $_A.limitapp_result.status==2} 审核不通过{elseif $_A.limitapp_result.status==3} 用户取消{/if} </div>
	</div>
	<div class="module_border">
		<div class="l">审核时间:</div>
		<div class="c">
		{ $_A.limitapp_result.verify_time|date_format:"Y-m-d H:i"}
		 </div>
	</div>
	<div class="module_border" >
		<div class="l">审核备注:</div>
		<div class="c">
			{ $_A.limitapp_result.verify_remark}
		</div>
	</div>
	
	{/if}
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
			<td width="*" class="main_td">申请人</td>
			<td width="" class="main_td">申请时间</td>
			<td width="" class="main_td">增加金额</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{ foreach  from=$_A.limitapp_list key=key item=item}
		<tr  {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1" align="center" >{ $item.id}</td>
			<td class="main_td1" align="center"><a href="{$_A.query_url}&username={$item.username}">{$item.username}</a></td>
			<td class="main_td1" align="center" >{$item.realname}</td>
			<td class="main_td1" align="center" >{$item.addtime|date_format:"Y-m-d H:i"}</td>
			<td class="main_td1" align="center" >{$item.account}</td>
			<td class="main_td1" align="center" >{ if $item.status ==1}审核通过{ elseif $item.status ==0}等待审核{ elseif $item.status ==3}用户取消{else}审核未通过{/if}</td>
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