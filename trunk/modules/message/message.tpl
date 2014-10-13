{if $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	<div class="module_title"><strong>{$_A.list_title}</strong></div>
	<form name="form1" method="post" action=""  enctype="multipart/form-data" onsubmit="return check_form();" >
	<div class="module_border">
		<div class="l">发送人：</div>
		<div class="c">
			<input type="text" name="send_userid" id="zz" value="{$magic.session.username}"  /> <span  class="label"  onclick='tipsWindown("选择用户","url:get?plugins/index.php?q=user&name=send_username&id=zz&type=input",500,300,"false","","true","text")'>请选择</span> <span>填写用户名</span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">接收人：</div>
		<div class="c">
			<input type="text" name="receive_userid" id="suser"  /> <span  class="label"  onclick='tipsWindown("选择用户","url:get?plugins/index.php?q=user&name=receive_username&id=suser&type=input",500,300,"false","","true","text")'>请选择</span> <span>填写用户名</span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">类型：</div>
		<div class="c">
			<script src="/plugins/index.php?q=linkage&name=type&nid=message_type&value={$_A.message_result.type}"></script> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">内容：</div>
		<div class="c">
			<textarea name="content" rows="5" cols="50" >{$_A.message_result.type}</textarea>
		</div>
	</div>
	<div class="module_submit" >
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
	</form>
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
{elseif $_A.query_type=="list"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">发送人</td>
			<td width="" class="main_td">接收人</td>
			<td width="" class="main_td">发送时间</td>
			<td width="" class="main_td">类型</td>
			<td width="" class="main_td">内容</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{foreach from=$_A.message_list key=key item=item}
			<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}</td>
			<td>{$item.sent_username|default:系统}</td>
			<td class="main_td1" align="center">{$item.receive_username}</td>
			<td>{$item.addtime|date_format:"Y-m-d H:i"}</td>
			<td>{$item.type}</td>
			<td>{$item.content}</td>
			<td>{if $item.status ==1}已看{else}未看{/if}</td>
			<td><a href="{$_A.query_url}/del{$site_url}&id={$item.id}">删除</a></td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username}"/><input type="button" value="搜索" onclick="sousuo()" />
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
	if (sou!=""){
	location.href=url+sou;
	}
}
</script>
{/literal}
{/if}