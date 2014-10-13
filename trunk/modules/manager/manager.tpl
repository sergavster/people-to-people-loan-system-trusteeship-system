{if $_A.query_type == "list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">管理员名</td>
		<th width="" class="main_td">添加时间</th>
		<th width="" class="main_td">状态</th>
		<th width="" class="main_td">管理员类型</th>
		<th width="" class="main_td">登录次数</th>
		<th width="" class="main_td">排序</th>
		<td width="" class="main_td">操作</td>
	</tr>
	<form action="" method="post">
	{ foreach  from=$_A.user_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.user_id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center" >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center" >{if $item.islock ==1}锁定{else}开通{/if}</td>
		<td class="main_td1" align="center" >{$item.typename}</td>
		<td class="main_td1" align="center">{$item.logintime}</td>
		<td class="main_td1" align="center"><input type="text" name="order[]" size="4" value="{$item.order|default:10}" /><input type="hidden" value="{$item.user_id}" name="user_id[]" /></td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/edit&a=system&user_id={$item.user_id}{$_A.site_url}">修改</a> / {if $item.islock!=1}<a href="#" onClick="javascript:if(confirm('确定要锁定吗?锁定后用户将不能登录系统')) location.href='{$_A.query_url}/lock&islock=1&user_id={$item.user_id}{$_A.site_url}'">锁定</a>{else}<a href="#" onClick="javascript:if(confirm('确定要解锁吗?解锁后用户可以正常登录系统')) location.href='{$_A.query_url}/lock&islock=0&user_id={$item.user_id}{$_A.site_url}'">解锁</a>{/if} / <a href="{$_A.query_url}/del&user_id={$item.user_id}{$_A.site_url}">删除</a></td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="8" class="page">
		<input type="submit" value="修改排序" /
		</td>
	</tr>
	<tr>
		<td colspan="8" class="page">
		{$_A.showpage}
		</td>
	</tr>
	</form>
</table>

{elseif $_A.query_type == "salesman_user"}
<table border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">业务员名</td>
		<th width="" class="main_td">添加时间</th>
		<th width="" class="main_td">状态</th>
		<th width="" class="main_td">类型</th>
		<td width="" class="main_td">操作</td>
	</tr>
	<form action="" method="post">
	{foreach from=$_A.user_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.user_id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center" >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center" >{if $item.status==1}开通{else}隐藏{/if}</td>
		<td class="main_td1" align="center" >{$item.typename}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/edit&a=system&salesman=1&user_id={$item.user_id}{$_A.site_url}">修改</a> / <a href="#" onClick="javascript:if(confirm('取消后用户将变为普通用户，确定要操作么？')) location.href='{$_A.query_url}/del&user_id={$item.user_id}{$_A.site_url}'">取消业务员</a></td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="8" class="page">
		<a href="{$_A.query_url}/new&a=system&salesman=1">添加业务员</a>
		</td>
	</tr>
	<tr>
		<td colspan="8" class="page">
		{$_A.showpage}
		</td>
	</tr>
	</form>
</table>

{elseif $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	
	<form name="form_user" method="post" action="" {if $_A.query_type == "new"}onsubmit="return check_user();"{else}onsubmit="submit_fool()"{/if} >
	<div class="module_title"><strong>{if $_A.query_type == "edit"}编辑{else}添加{/if}管理员</strong></div>
	
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			{if $_A.query_type != "edit"}<input name="username" type="text" class="input_border" />{else}{ $_A.user_result.username}<input name="username" type="hidden"  class="input_border" value="{$_A.user_result.username}" />{/if} <font color="#FF0000">*（必填）</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">登录密码：</div>
		<div class="c">
			<input name="password" type="password" class="input_border" />{if $_A.query_type == "edit" } 不修改请为空{/if} <font color="#FF0000">*（必填）</font>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">确认密码：</div>
		<div class="c">
			<input name="password1" type="password" class="input_border" />{if $_A.query_type == "edit" } 不修改请为空{/if} <font color="#FF0000">*（必填）</font>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">真实姓名：</div>
		<div class="c">
			<input name="realname" type="text" value="{$_A.user_result.realname}" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">性　别： </div>
		<div class="c">
			<input type="radio" name="sex" value="0" {if $_A.user_result.sex==0 || $_A.user_result.sex==""} checked="checked" {/if}/>
		保密&nbsp;&nbsp;
		<input type="radio" name="sex" value="1" {if $_A.user_result.sex==1} checked="checked" {/if} />
		男&nbsp;&nbsp;
		<input type="radio" name="sex" value="2"  {if $_A.user_result.sex==2} checked="checked" {/if}/>
	  女&nbsp;&nbsp; 
		</div>
	</div>
	
	  <div class="module_border">
		<div class="l">生日：</div>
		<div class="c">
		<input type="text" name="birthday" class="input_border" value="{$_A.user_result.birthday}" size="20" onclick="change_picktime()"/>
		</div>
	</div>
	
	  <div class="module_border">
		<div class="l">类型： </div>
		<div class="c">
			{if $_A.user_result.type==3 || $_A.salesman == 1}
			业务员<input type="hidden" name="type_id" value="19" />
			{else}
			{html_options name="type_id" options=$list_type selected=$_A.user_result.type_id}
			{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			 <input name="status" type="radio" value="0"  {if $_A.user_result.status=="0"} checked="checked"{/if}/>关闭<input name="status" type="radio" value="1" { if $_A.user_result.status==1 || $_A.user_result.status==""} checked="checked"{/if}/>开通
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">所在地：</div>
		<div class="c">
			<script src="./plugins/index.php?&q=area&area={$_A.user_result.area}" type='text/javascript' language="javascript"></script>
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">电子邮件地址： </div>
		<div class="c">
			<input name="email" value="{ $_A.user_result.email }" type="text"  class="input_border" /> <font color="#FF0000">*（必填）</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">QQ：</div>
		<div class="c">
			<input name="qq" type="text" value="{ $_A.user_result.qq }" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">旺旺：</div>
		<div class="c">
			<input name="wangwang" type="text" value="{ $_A.user_result.wangwang }" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">家庭电话：</div>
		<div class="c">
			<input name="tel" type="text" value="{ $_A.user_result.tel }" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">手机：</div>
		<div class="c">
			<input name="phone" type="text" value="{$_A.user_result.phone}" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">详细地址：</div>
		<div class="c">
			<input name="address" type="text" value="{$_A.user_result.address}" class="input_border" />
		</div>
	</div>
	<!--<div class="module_border">
		<div class="l">令牌序列号SN：</div>
		<div class="c">
			<input name="serial_id" type="text" value="{$_A.user_result.serial_id}" class="input_border" />
		</div>
	</div>-->
	<div class="module_submit border_b" >
	{if $_A.query_type == "edit"}<input type="hidden" name="user_id" value="{ $_A.user_result.user_id}" />{/if}
	<input type="submit" value="确认提交" />
	<input type="reset" name="reset" value="重置表单" />
	</div>
	</form>
</div>
{literal}
<script>
function joincity(id){
	alert($("#"+id+"city option").text());

}

function check_user(){
	 var frm = document.forms['form_user'];
	 var username = frm.elements['username'].value;
	 var password = frm.elements['password'].value;
	  var password1 = frm.elements['password1'].value;
	   var email = frm.elements['email'].value;
	 var errorMsg = '';
	  if (username.length == 0 ) {
		errorMsg += '用户名不能为空' + '\n';
	  }
	   if (username.length<2) {
		errorMsg += '用户名长度不能少于2位' + '\n';
	  }
	  if (password.length==0) {
		errorMsg += '密码不能为空' + '\n';
	  }
	  if (password.length<6) {
		errorMsg += '密码长度不能小于6位' + '\n';
	  }
	   if (password.length!=password1.length) {
		errorMsg += '两次密码不一样' + '\n';
	  }
	   if (email.length==0) {
		errorMsg += '邮箱不能为空' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return submit_fool();
	  }
}
</script>
{/literal}
{elseif $_A.query_type == "type"}
<table width="100%" border="0" cellpadding="5" cellspacing="1" >
	<tr>
		<td class="main_td">类型名称</td>
		<td class="main_td">简要</td>
		<td class="main_td">备注</td>
		<td class="main_td">排序</td>
		<td class="main_td">状态</td>
		<td class="main_td">操作</td>
	</tr>
	<form action="{$_A.query_url}/type_order" method="post">
	{ foreach from = $_A.user_type_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td bgcolor="#ffffff" >{$item.name}</td>
		<td bgcolor="#ffffff" >{$item.summary}</td>
		<td bgcolor="#ffffff">{$item.remark}</td>
		<td bgcolor="#ffffff"><input name="order[]" size="2" value="{ $item.order}"type="text" ><input name="type_id[]" type="hidden" size="2" value="{ $item.type_id}" ></td>
		<td  bgcolor="#ffffff" >{ if $item.status==1}开通{else}<font color=red>关闭</font>{/if}</td>
		<td bgcolor="#ffffff"><a href="{$_A.query_url}/type_edit&a=system&type_id={$item.type_id}">修改</a>/<a href="#" onclick="javascript:if(confirm('确定要删除吗?请慎重')) location.href='{$_A.query_url}/type_del&type_id={$item.type_id}'">删除</a></td>
	</tr>
	{ /foreach}
	<tr>
		<td   colspan="6" class="action"><input type="button" onclick="javascript:location.href='{$_A.query_url}/type_new{$_A.site_url}'" value="添加类型" />  <input type="submit" value="修改排序" /> </td>
	</tr>
	</form>
</table>
{ elseif $_A.query_type == "type_new" || $_A.query_type == "type_edit" }
<div class="module_add">
	
	<form enctype="multipart/form-data" name="form1" method="post" action="" onsubmit="return check_form();"  >
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }编辑{else}添加{/if}管理员类型</strong></div>
	
	<div class="module_border">
		<div class="l">类型名称:</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{ $result.name}" size="30" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">排序:</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{ $result.order|default:10}" size="10" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">状态:</div>
		<div class="c">
			<input type="radio" name="status" value="0"  { if $result.status == 0 }checked="checked"{/if}/> 关闭<input type="radio" name="status" value="1"  { if $result.status ==1 ||$result.status ==""}checked="checked"{/if}/>开通
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">简要说明:</div>
		<div class="c">
			<textarea name="summary" cols="55" rows="6" >{ $result.summary}</textarea>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">备注:</div>
		<div class="c">
			<textarea name="remark" cols="55" rows="6" >{ $result.remark}</textarea>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">权限:</div>
		<div class="c">
			{literal}
				<script>
				var checkflag = false;
				function changeAll(field,id) { 
					var chkArray = document.all(field);
					var checkflag = document.getElementById(id).checked;
					if (checkflag == true) { 
						for (i = 0; i < chkArray.length; i++) { 
							chkArray[i].checked = true; 
						}  
					} else { 
						for (i = 0; i < chkArray.length; i++) { 
							chkArray[i].checked = false;
						} 
					}
				}
				</script>
				{/literal}
				{foreach from = $_A.user_purview key=key item=item}
					<div style="height:auto; width:90%" class="floatr">
					{foreach from=$item key=_key item=_item}
					 <div style="width:97%; border-bottom:1px dashed #CCCCCC; height:28px; padding-top:5px"><strong>{$_key}</strong>
					 <input type="checkbox" title="全选" onclick="changeAll('{$key}','_{$key}')" id="_{$key}"/></div>
					 <div style="width:97%;border-bottom:1px solid #CCCCCC;  padding-top:5px">
						{foreach from=$_item key=__key item=__item}
						<div style="float:left; width:140px; height:25px;" title="{$__key}"><input type="checkbox" value="{$__key}" name="purview[]" id="{ $key}" {if $_A.query_type == "type_edit" }{$__key|checked:$result.purview}{/if}  /> {$__item}</div>
						{/foreach}
					</div>
					{/foreach}
					</div>
				{/foreach}
			</div>
	</div>
	<div class="module_submit" >
	{if $_A.query_type == "type_edit" }<input type="hidden" name="type_id" value="{ $result.type_id }" />{/if}
		<input type="submit" value="确认提交" />
		<input type="reset" name="reset" value="重置表单" />
	</div>
	</form>
</div>

{literal}
<script>
function check_form(){
	 var frm = document.forms['form1'];
	 var title = frm.elements['name'].value;
	 var errorMsg = '';
	  if (title.length == 0 ) {
		errorMsg += '类型名称必须填写' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return true;
	  }
}
{/literal}
</script>

{elseif $_A.query_type == "del"}
{if $_A.del_user.user_info.user_id<1}
<p style="text-align:center">用户不存在</p>
{else}
<div class="module_title"><strong>用户信息</strong></div>
<div class="module_border">
	<div class="l">用户名：</div>
	<div class="c">
		{$_A.del_user.user_info.username}
	</div>
</div>
<div class="module_border">
	<div class="l">注册时间：</div>
	<div class="c">
		{$_A.del_user.user_info.addtime|date_format:"Y-m-d H:i:s"}
	</div>
</div>
<div class="module_border">
	<div class="l">最后次数：</div>
	<div class="c">
		{$_A.del_user.user_info.logintime|default:0}次
	</div>
</div>
<div class="module_border">
	<div class="l">最后登录时间：</div>
	<div class="c">
		{$_A.del_user.user_info.lasttime|date_format:"Y-m-d H:i:s"}
	</div>
</div>
<div class="module_title"><strong>账户信息</strong></div>
<div class="module_border">
	<div class="l">账号总额：</div>
	<div class="c">
		{$_A.del_user.account.total|default:0}元
	</div>
</div>
<div class="module_border">
	<div class="l">可用余额：</div>
	<div class="c">
		{$_A.del_user.account.use_money|default:0}元
	</div>
</div>
<div class="module_border">
	<div class="l">冻结资金：</div>
	<div class="c">
		{$_A.del_user.account.no_use_money|default:0}元
	</div>
</div>
<div class="module_border">
	<div class="l">代收资金：</div>
	<div class="c">
		{$_A.del_user.account.collection|default:0}元
	</div>
</div>
<div class="module_title"><strong>发标信息</strong></div>
{if $_A.del_user.borrow==''}
<p style="text-align:center">此用户未发过标</p>
{else}
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
    <tr>
		<td width="" class="main_td">借款编号</td>
 		<td width="" class="main_td">借款标题</td>
		<td width="" class="main_td">借款金额</td>
		<td width="" class="main_td">利率</td>
		<td width="" class="main_td">借款期限</td>
		<td width="" class="main_td">发布时间</td>
		<td width="" class="main_td">状态</td>
	</tr>
	{foreach from=$_A.del_user.borrow item=item}
    <tr>
		<td>{$item.id}</td>
 		<td><a href="/invest/a{$item.id}.html" target="_blank">{$item.name}</a></td>
		<td>{$item.account}</td>
		<td>{$item.apr}%</td>
		<td>{if $item.isday==1}{$item.time_limit_day}天{else}{$item.time_limit}个月{/if}</td>
		<td>{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td>{if $item.status==0}等待初审{elseif $item.status==1}招标中{elseif $item.status==3 && $item.account=repayment_yesaccount}已还款{elseif $item.status==3 && $item.account>repayment_yesaccount}还款中{elseif $item.status==5}被撤回{/if}</td>
	</tr>
    {/foreach}
    </table>
{/if}
<div class="module_title"><strong>投标信息</strong></div>
{if $_A.del_user.borrow_tender==''}
<p style="text-align:center">此用户未投过标</p>
{else}
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
    <tr>
		<td class="main_td">标id</td>
 		<td class="main_td">标名</td>
		<td class="main_td">投标有效金额</td>
		<td class="main_td">投标时间</td>
	</tr>
	{foreach from=$_A.del_user.borrow_tender item=item}
    <tr>
		<td>{$item.borrow_id}</td>
 		<td>{$item.borrow_name}</td>
		<td>{$item.borrow_account}</td>
		<td>{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
	</tr>
    {/foreach}
    </table>
{/if}
<div class="module_title"><strong>删除操作</strong></div>
{if $_A.del_user.borrow_tender=='' && $_A.del_user.borrow==''}
<form action="" method="post">
<div class="module_border" >
	<div class="l">验证码:</div>
	<div class="c">
		<input type="text" name="valicode" maxlength="4" /><img src="/plugins/index.php?q=imgcode" alt="点击刷新" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" align="absmiddle" style="cursor:pointer;">
	</div>
</div>
<div class="module_border" >
	<div class="l"></div>
	<div class="c">
    	<input type="hidden" value="{$_A.del_user.user_info.user_id}" name="user_id" />
		<input type="submit" value="确认删除用户" />
	</div>
</div>
</form>
{/if}
<p style="color:#F00;text-align:center">警告：用户一旦删除将不可恢复，删除用户的同时将删除有关改用户的所有信息，避免数据出错，投过标，发过标的用户一律无法删除</p>
{/if}
{/if}