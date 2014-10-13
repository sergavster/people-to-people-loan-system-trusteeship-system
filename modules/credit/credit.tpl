{if $_A.query_type == "list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td class="main_td">用户ID</td>
		<td class="main_td">用户名</td>
		<td class="main_td">真实姓名</td>
		<td class="main_td">总积分</td>
		<td class="main_td">最后调整时间</td>
		<td class="main_td">操作</td>
	</tr>
	{ foreach  from=$_A.credit_list key=key item=item}
	<tr {if $key%2==1}class="tr2"{/if}>
		<td>{ $item.user_id}</td>
		<td>{ $item.username}</td>
		<td >{ $item.realname}</td>
		<td >{ $item.value}分 <img src="{$_G.system.con_credit_picurl}{ $item.pic}"  /></td>
		<td >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td><a href="{$_A.query_url}/log{$_A.site_url}&user_id={$item.user_id}" >查看明细</a> <a href="{$_A.admin_url}&q=module/attestation/jifen&user_id={$item.user_id}" >修改积分</a></td>
	</tr>
	{ /foreach}
	<tr>
		<td colspan="7" class="action">
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username}"/> <input type="button" value="搜索" / onclick="sousuo()">
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="7"  class="page">
		{$_A.showpage} 
		</td>
	</tr>
</table>
<script>
var url = '{$_A.query_url}';
{literal}
function sousuo(){
	var username = $("#username").val();
	location.href=url+"&username="+username;
}

</script>
{/literal}


{elseif $_A.query_type == "log"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td class="main_td">ID</td>
		<td class="main_td">用户名</td>
		<td class="main_td">真实姓名</td>
		<td class="main_td">积分类型</td>
		<td class="main_td">变动类型</td>
		<td class="main_td">变动分值</td>
		<td class="main_td">操作时间</td>
	</tr>
	{ foreach  from=$_A.credit_list key=key item=item}
	<tr {if $key%2==1}class="tr2"{/if}>
		<td>{ $item.id}</td>
		<td>{ $item.username}</td>
		<td>{ $item.realname}</td>
		<td >{ $item.type_name}</td>
		<td >{if $item.op==1}增加{else}减少{/if}</td>
		<td >{ $item.value}</td>
		<td >{$item.addtime|date_format:"Y-m-d"}</td>
	</tr>
	{ /foreach}
	<tr>
		<td colspan="7"  class="page">
		{$_A.showpage} 
		</td>
	</tr>
</table>
<script>
var url = '{$_A.query_url}';
{literal}
function sousuo(){
	var username = $("#username").val();
	location.href=url+"&username="+username;
}

</script>
{/literal}

{elseif $_A.query_type == "rank"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td class="main_td">等级名称</td>
		<td class="main_td">等级</td>
		<td class="main_td">开始分值</td>
		<td class="main_td">最后分值</td>
		<td class="main_td">图片</td>
		<td class="main_td">图片样子</td>
		<td class="main_td">操作</td>
	</tr>
	<form name="form1" method="post" action=""  enctype="multipart/form-data">
	{ foreach  from=$_A.credit_rank_list key=key item=item}
	<tr {if $key%2==1}class="tr2"{/if}>
		<td><input type="text" name="name[]" value="{ $item.name}" size="15" /></td>
		<td><input type="text" name="rank[]" value="{ $item.rank}"size="15" /></td>
		<td><input type="text" name="point1[]" value="{ $item.point1}" size="15" /></td>
		<td><input type="text" name="point2[]" value="{ $item.point2}" size="15" /></td>
		<td><input type="text" name="pic[]" value="{ $item.pic}" size="15" /><input type="hidden" name="id[]" value="{$item.id}" /></td>
		<td><img src="{$_G.system.con_credit_picurl}{ $item.pic}" alt="没有表示图片不正确" /> </td>
		<td><a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/rank_del{$_A.site_url}&id={$item.id}'">删除</a></td>
	</tr>
	{ /foreach}
	
	<tr>
	<td colspan="7"  class="action" >
		 <input type="submit" value="确认操作" /> 
		</td>
	</tr>
	</form>
	<tr >
		<td class="main_td" colspan="7" align="left" >&nbsp;添加</td>
	</tr>
	<form name="form1" method="post" action="{$_A.query_url}/rank_new" enctype="multipart/form-data">
	<tr class="tr2">
		<td >等级名称</td>
		<td >等级</td>
		<td >开始分值</td>
		<td >最后分值</td>
		<td colspan="3" >图片</td>
	</tr>
	<tr >
		<td><input type="text" name="name"  /></td>
		<td><input type="text" name="rank" /></td>
		<td><input type="text" name="point1" /></td>
		<td><input type="text" name="point2" /></td>
		<td colspan="3" ><input type="text" name="pic" /></td>
	</tr>
	
	<tr>
		<td colspan="7"  class="action" >
		 <input type="submit" value="添加等级" /> 
		</td>
	</tr>
	</form>
</table>
<script>
var url = '{$_A.query_url}';
{literal}
function sousuo(){
	var username = $("#username").val();
	location.href=url+"&username="+username;
}

</script>
{/literal}
{elseif $_A.query_type == "type_new" || $_A.query_type == "type_edit"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return check_form();" enctype="multipart/form-data">
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }编辑{else}添加{/if}积分类型</strong></div>
	
	<div class="module_border">
		<div class="l">积分类型名称：</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{ $_A.credit_type_result.name}" size="20" />
		</div>
	</div>

	<div class="module_border">
		<div class="l">积分代码：</div>
		<div class="c">
			<input type="text" name="nid"  class="input_border" value="{ $_A.credit_type_result.nid}" size="20" />
		</div>
	</div>

	<div class="module_border">
		<div class="l">积分值：</div>
		<div class="c">
			<input type="text" name="value"  class="input_border" value="{ $_A.credit_type_result.value}" size="10" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">周期：</div>
		<div class="c">
			<input type="radio" name="cycle" value="1" {if 1==$_A.credit_type_result.cycle}checked{/if} /> 一次
			<input type="radio" name="cycle" value="2" {if 2==$_A.credit_type_result.cycle}checked{/if} /> 每天
			<input type="radio" name="cycle" value="3" {if 3==$_A.credit_type_result.cycle}checked{/if} /> 时间间隔
			<input type="radio" name="cycle" value="4" {if 4==$_A.credit_type_result.cycle}checked{/if} /> 不限
		</div>
	</div>

	<div class="module_border">
		<div class="l">奖励次数：</div>
		<div class="c">
			<input type="text" name="award_times"  class="input_border" value="{ $_A.credit_type_result.award_times}" size="8" />
		</div>
	</div>

	<div class="module_border">
		<div class="l">时间间隔：</div>
		<div class="c">
			<input type="text" name="interval"  class="input_border" value="{ $_A.credit_type_result.interval}" size="8" /> 分钟
		</div>
	</div>

	<div class="module_submit border_b" >
		{ if $_A.query_type == "type_edit" }<input type="hidden" name="id" value="{ $_A.credit_type_result.id }" />{/if}
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
</div>
{literal}
<script>
function check_form(){
	 var frm = document.forms['form1'];
	 var name = frm.elements['name'].value;
	 var nid = frm.elements['nid'].value;
	 var errorMsg = '';
	  if (name.length == 0 ) {
		errorMsg += '标题必须填写' + '\n';
	  }
	  if (nid.length == 0 ) {
		errorMsg += '代码标示名必须填写' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return true;
	  }
}
</script>
{/literal}
{elseif $_A.query_type == "type"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
	<tr >
		<td class="main_td">ID</td>
		<td class="main_td">积分类型名称</td>
		<td class="main_td">积分代码</td>
		<td class="main_td">积分</td>
		<td class="main_td">周期</td>
		<td class="main_td">奖励次数</td>
		<td class="main_td">操作</td>
	</tr>
	{ foreach  from=$_A.credit_type_list key=key item=item}
	<tr {if $key%2==1}class="tr2"{/if}>
		<td>{ $item.id}</td>
		<td>{ $item.name}</td>
		<td >{ $item.nid}</td>
		<td >{ $item.value}</td>
		<td>{if 1==$item.cycle}一次{elseif 2==$item.cycle}每天{elseif 3==$item.cycle}每{$item.interval}分钟{else}不限{/if}</td>
		<td >{if 0==$item.award_times}不限{else}{$item.award_times}次{/if}</td>
		<td><a href="{$_A.query_url}/type_edit{$_A.site_url}&id={$item.id}" >修改</a> <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/type_del{$_A.site_url}&id={$item.id}'">删除</a></td>
	</tr><input type="hidden" name="flag[{$key}]" value="{$item.flag}" />
	{ /foreach}</form>
	<tr>
		<td colspan="7" class="action">
		<div class="floatr">
			关键字：<input type="text" name="keywords" id="keywords" value="{$magic.request.keywords}"/> <input type="button" value="搜索" / onclick="sousuo()">
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="7"  class="page">
		{$_A.showpage} 
		</td>
	</tr>
	</form>
</table>

<script>
var url = '{$_A.query_url}';
{literal}
function sousuo(){
	var username = $("#username").val();
	var keywords = $("#keywords").val();
	location.href=url+"&username="+username+"&keywords="+keywords;
}

</script>
{/literal}
{/if}