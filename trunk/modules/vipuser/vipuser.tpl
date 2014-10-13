<!--VIP卡类型-->
{if 'ct' == $_A.query_type} 
<div class="module_add">
	<form action="{$_A.query_url}/{if $magic.request.id!=""}uct{else}act{/if}{$_A.site_url}" method="post">
	<div class="module_title"><strong>{if $magic.request.id!=""}修改<input type="hidden" name="id" value="{$result.id}" />{else}添加{/if}充值卡类型</strong></div>
	
	<div class="module_border">
		<div class="l">类 型：</div>
		<div class="c">
			<input name="type" type="text" maxlength="20" value="{$result.name}" /> <span class="red">*</span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">月 数：</div>
		<div class="c">
			<input name="month_num" type="text" value="{$result.month_num}" /> <span style="color:red;">*</span>
		</div>
	</div>
	
	<div class="module_submit" >
		<input name="submit" type="submit" value="添 加" class="btn" />
	</div>
	</form>

	<div class="module_title"><strong>添加充值卡类型列表</strong></div>
</div>
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		
		<tr >
			<td align="center" class="main_td">类 型</td>
			<td align="center" class="main_td">月 数</td>
			<td align="center" class="main_td">操 作</td>
		</tr>
		{foreach from=$rows item=result}
		<tr {if $key%2==1}class="tr2"{/if}>
			<td>{$result.name}</td>
			<td>{$result.month_num}</td>
			<td align="center"><a href="{$_A.query_url}/ct&id={$result.id}">编辑</a>  <a href="javascript:if(confirm('确实要删除吗?'))location='{$_A.query_url}/dct&id={$result.id}';">删除</a></td>
		</tr>
		{/foreach}
	</table>

<!--/VIP卡类型-->


<!--VIP卡-->
    <!--添加新卡-->
{elseif 'ac' == $_A.query_type}
<div class="module_add">
	 <form action="" method="post">
	<div class="module_title"><strong>添加新卡</strong></div>
	
	<div class="module_border">
		<div class="l">所在城市：</div>
		<div class="c">
			<script src="/plugins/index.php?q=area&type=p,c"></script>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">年份学期：</div>
		<div class="c">
			<select name="year">
					{foreach from=$years item=item}
						<option value="{$item}">{$item}</option>
					{/foreach}
				</select>
				<select name="semester">
						<option value="A" {if 'A'==$semester}selected{/if}>上学期</option>
						<option value="B" {if 'B'==$semester}selected{/if}>下学期</option>
				</select>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">添加数量：</div>
		<div class="c">
			<input name="number" type="text" /> <span style="color:red;">*</span> 一次最多只能生成100张
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">密码位数：</div>
		<div class="c">
			<select name="pwd_word_num">
					<option value="4">四位数</option>
					<option value="5">五位数</option>
					<option value="6" selected>六位数</option>
					<option value="7">七位数</option>
					<option value="8">八位数</option>
			</select>
		</div>
	</div>
	
	<div class="module_submit" >
		<input name="step" type="hidden" value="2" />
		<input type="submit" name="submit" value="生成" class="btn" />
	</div>
</form>
</div>
    <!--/添加新卡-->
    <!--充值卡列表-->
{elseif 'lc' == $_A.query_type}

<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="{$_A.query_url}/delcard" method="post">
    <tr>
		<td class="main_td" align="center"><input type="checkbox" name="allcheck" onclick="checkFormAll(this.form)"/></td>
        <td class="main_td" align="center">卡号</td>
        <td class="main_td" align="center">密码</td>
        <td class="main_td" align="center">城市</td>
        <td class="main_td" align="center">充值备注</td>
        <td class="main_td" align="center">充值时间</td>
        <td class="main_td" align="center">到期时间</td>
		<td class="main_td" align="center">VIP类型</td>
		<td class="main_td" align="center">生成人</td>
        <td class="main_td" align="center" width="140px">操 作</td>
    </tr>
   {foreach from=$rows key=key item=item}
        <tr  {if $key%2==1}class="tr2"{/if}>
			<td><input type="checkbox" name="aid[{$key}]" value="{$item.serial_number}"/></td>
			<td>{$item.serial_number}</td>
			<td>{$item.password}</td>
			<td>{$item.city}</td>
			<td>{if $item.realname}<a href="{$_A.admin_url}&q=module/schoolresume/preview&id={$item.re_id}">{$item.realname}</a>{else}未充值{/if}</td>
			<td>{$item.open_time|date_format:'Y-m-d'|default:"-"}</td>
			<td>{$item.end_date|date_format:'Y-m-d'|default:"-"}</td>
			<td>{$item.vct_name|default:"-"}</td>
			<td>{$item.create_user}</td>
			<td><a href="{$_A.query_url}/history&id={$item.id}">{$item.status_name}</a> | <a href="{$_A.query_url}/editcard&id={$item.id}">编辑</a> | <a href="{$_A.query_url}/preview&id={$item.id}">查看</a></td>
        </tr>
   {/foreach}
	<tr>
		<td colspan="12"  class="action" >
			<div class="floatl"> <select name="type">
			<option value="6">删除</option>&nbsp;&nbsp;&nbsp;
			</select> <input type="submit" value="确认操作" />
			</div>
			<div class="floatr">
			状 态：<select name="status" id="status">
            <option value="7" {if 7==$magic.request.status}selected{/if}>全部</option>
            <option value="0" {if 0==$magic.request.status}selected{/if}>未激活</option>
            <option value="1" {if 1==$magic.request.status}selected{/if}>激活</option>
			<option value="5" {if 5==$magic.request.status}selected{/if}>延期</option>
			<option value="6" {if 6==$magic.request.status}selected{/if}>过期</option>
            <option value="2" {if 2==$magic.request.status}selected{/if}>冻结</option>
            <option value="3" {if 3==$magic.request.status}selected{/if}>停卡</option>
            <option value="4" {if 4==$magic.request.status}selected{/if}>禁用</option>
        </select>&nbsp&nbsp;
        VIP卡号：<input type="text" name="serial_number" id="serial_number" value="{$magic.request.serial_number}" maxlength="15" />
        &nbsp;&nbsp;<input type="button" value="搜索" / onclick="sousuo()">&nbsp;<input type="button" value="导出充值卡" / onclick="javascript:location.href='{$_A.query_url}/lc&export=1'">
			</div>
			</td>
		</tr>
    <tr bgcolor='#FFFFFF'><td colspan='11' class="page">{$page}</td></tr>
</form>
</table>
<script>
var url = '{$_A.query_url}/lc/';
{literal}
function sousuo(){
	var status = $("#status").val();
	var serial_number = $("#serial_number").val();
	location.href=url+"&status="+status+"&serial_number="+serial_number+"&export=0";
}

</script>
{/literal}
 <!--/充值卡列表-->
 
<!--查看VIP卡信息-->
{elseif 'preview' == $_A.query_type}
<div class="module_add">
	<div class="module_title"><strong>查看VIP卡信息</strong></div>
	
	
	
	<div class="module_border">
		<div class="l">用户名:</div>
		<div class="c">
			{$result.username|default:"-"}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">卡 号:</div>
		<div class="c">
			{$result.serial_number}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">生成批次:</div>
		<div class="c">
			{$result.batch}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">密 码:</div>
		<div class="c">
			{$result.password}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">创建时间:</div>
		<div class="c">
			{$result.create_time}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">有效期:</div>
		<div class="c">
			{$result.start_date|default -} 至 {$result.end_date|default -}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">类 型:</div>
		<div class="c">
			{$result.vct_name}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">有效月数:</div>
		<div class="c">
			{$result.month_num}
		</div>
	</div>

	<div class="module_border">
		<div class="l">激活时间:</div>
		<div class="c">
			{$result.open_time|default -}
		</div>
	</div>

	<div class="module_border">
		<div class="l">状 态:</div>
		<div class="c">
			{$result.status_name}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">冻结天数:</div>
		<div class="c">
			{$result.freeze_day}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">冻结时间:</div>
		<div class="c">
			{$result.freeze_time|default -}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">被冻结次数:</div>
		<div class="c">
			{$result.freeze_times|default 0}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">停卡天数:</div>
		<div class="c">
			{$result.stop_day}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">停卡时间:</div>
		<div class="c">
			{$result.stop_time|default -}
		</div>
	</div>
	
	<div class="module_submit" >
		<input name="bk" type="button" value="返 回" onclick="javascript:history.back(-1);" />
		<input name="bk" type="button" value="编 辑" onclick="javascript:window.location.href='{$_A.query_url}/editcard&id={$result.id}';" />
	</div>
</div>
    <!--编辑/VIP卡信息-->
<!--/VIP卡-->
{elseif 'usercard' == $_A.query_type}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
		<tr >
			<!-- <td width="" class="main_td"><input type="checkbox" name="allcheck" onclick="checkFormAll(this.form)"/></td> -->
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">标题</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">排序</td>
			<td width="" class="main_td">属性</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{ foreach  from=$result key=key item=item}
		<tr >
			<!-- <td class="main_td1" align="center" width="40"><input type="checkbox" name="aid[{$key}]" value="{$item.id}"/></td> -->
			<td class="main_td1" align="center" width="30">{ $item.id}</td>
			<td class="main_td1" align="center">{$item.name|truncate:34}</td>
			<td class="main_td1" align="center" width="50">{ if $item.status ==1}<a href="{$_A.query_url}{$_A.site_url}&status=0&id={ $item.id}">显示</a>{else}<a href="{$_A.query_url}{$_A.site_url}&status=1&id={ $item.id}">隐藏</a>{/if}</td>
			<td class="main_td1" align="center" width="50"><input type="text" name="order[{$key}]" value="{$item.order}" size="3" /><input type="hidden" name="id[{$key}]" value="{$item.id}" /></td>
			<td class="main_td1" align="center" width="70">{$item.flagname|default:-}{if $item.litpic!=""}图片{/if}</td>
			<td class="main_td1" align="center" width="130"><a href="{$_A.query_url}/edit{$_A.site_url}&id={$item.id}" >修改</a> <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/del{$_A.site_url}&id={$item.id}'">删除</a></td>
		</tr><input type="hidden" name="flag[{$key}]" value="{$item.flag}" />
		{ /foreach}
		<tr>
			<td colspan="8"  class="action" >
			<div class="floatl"><select name="type">
			<option value="0">排序</option>
			<option value="1">显示</option>
			<option value="2">隐藏</option>
			<option value="3">推荐</option>
			<option value="4">头条</option>
			<option value="5">幻灯片</option>
			<option value="6">删除</option>&nbsp;&nbsp;&nbsp;
			</select> <input type="submit" value="确认操作" /> 排序不用全选
			</div>
			<div class="floatr">
			关键字：<input type="text" name="keywords" id="keywords" value="{$magic.request.keywords}"/> <input type="button" value="搜索" / onclick="sousuo()">
			</div>
			</td>
		</tr> 
		<tr>
			<td colspan="8"  class="page" >
			{$page}
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
{elseif 'nvipuser' == $_A.query_type}
<div class="module_add">
	<form action="{$_A.query_url}/nvipuser" method="post">
	<div class="module_title"><strong>激活VIP会员</strong></div>
	<div class="module_border">
		<div class="l">会员帐号:</div>
		<div class="c">
			<input name="user_name" type="text" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">VIP类型：</div>
		<div class="c">
			<select name="vct_id">
					{foreach from=$vct item=item}
					<option value="{$item.id}">{$item.name}</option>
					{/foreach}
				</select>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">VIP卡号：</div>
		<div class="c">
			<input name="serial_number" type="text" value="{$serial_number}" />
		</div>
	</div>
	
	<div class="module_submit" >
		 <input name="step" type="hidden" value="2" />
		 <input type="submit" name="submit" value="激活" class="btn" />
	</div>
	</form>
</div>

{elseif 'editcard' == $_A.query_type}
<div class="module_add">
	<form acton="{$_A.query_url}/editcard" method="post">
	<div class="module_title"><strong>激活VIP会员</strong></div>
	<div class="module_border">
		<div class="l">有效时间:</div>
		<div class="c">
			{$result.start_date|date_format:'Y-m-d'}～{$result.end_date|date_format:'Y-m-d'}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">使用状态：</div>
		<div class="c">
			<input name="status" id="jy" value="jy" type="radio" {if 4==$result.status}checked="true"{/if} onclick="javascript:displayTime('none');" /><label for="jy">禁用此卡</label>
			<input name="status" id="jh" value="jh" type="radio" {if 1==$result.status}checked="true"{/if} onclick="javascript:displayTime('none');" /><label for="jh">激活此卡</label>
			<input name="status" id="tz" value="tz" type="radio" {if 3==$result.status}checked="true"{/if} onclick="javascript:displayTime('');" /><label for="tz">停止此卡</label>
			<input name="status" id="dj" value="dj" type="radio" {if 2==$result.status}checked="true"{/if} onclick="javascript:displayTime('');" /><label for="dj">冻结此卡</label>
			<input name="status" id="yq" value="yq" type="radio" {if 5==$result.status}checked="true"{/if} onclick="javascript:displayTime('');" /><label for="yq">延期此卡</label>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">选择时间：</div>
		<div class="c">
			<select name="year">
				<option value="0">零年</option>
				<option value="1">一年</option>
				<option value="2">二年</option>
				<option value="3">三年</option>
			</select>
			<select name="month">
			<option value="0">零个月</option>
			<option value="1">一个月</option>
			<option value="2">二个月</option>
			<option value="3">三个月</option>
			<option value="4">四个月</option>
			<option value="5">五个月</option>
			<option value="6">六个月</option>
			<option value="7">七个月</option>
			<option value="8">八个月</option>
			<option value="9">九个月</option>
			<option value="10">十个月</option>
			<option value="11">十一个月</option>
			<option value="12">十二个月</option>
		</select>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">操作原因：</div>
		<div class="c">
			<a href="javascript:display('remark');">点击添加操作原因</a>&nbsp;&nbsp;<input name="remark" id="remark" size="50" style="display:none;"></input>
		</div>
	</div>
	
	<div class="module_submit" >
		 <input name="id" type="hidden" value="{$result.id}" />
		<input type="submit" name="submit" value="确认" />
		<input type="button" value="返回" onclick="javascript:history.back(-1);" />
	</div>
	</form>
</div>
{literal}
<script>
	function display(id) {
		status = 'none';
		if (document.getElementById(id).style.display==status) {
			status = '';
		}
		document.getElementById(id).style.display = status;
	}

	function displayTime(status) {
		document.getElementById('time').style.display = status;
	}
</script>
{/literal}
{elseif 'history' == $_A.query_type}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
	<tr >
		<td width="*" class="main_td">卡号</td>
		<td width="" class="main_td">操作类型</td>
		<td width="" class="main_td">期限</td>
		<td width="" class="main_td">操作原因</td>
		<td width="" class="main_td">操作人</td>
		<td width="" class="main_td">操作时间</td>
		<td width="" class="main_td">操作IP</td>
	</tr>
	{ foreach  from=$result key=key item=item}
	<tr {if $key%2==1}class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.serial_number}</td>
		<td class="main_td1" align="center">{$item.op}</td>
		<td class="main_td1" align="center">{$item.expire|default 0}个月</td>
		<td class="main_td1" align="center">{$item.remark}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{$item.optime}</td>
		<td class="main_td1" align="center">{$item.optip}</td>
	</tr>
	{ /foreach}
	<tr>
		<td colspan="7" class="page">
		{$page}
		</td>
	</tr>
	</form>
</table>
<script>
var url = '{$_A.query_url}';
{literal}
function sousuo(){
	var username = $("#user_name").val();
	var keywords = $("#serial_number").val();
	location.href=url+"&user_name="+username+"&serial_number="+keywords;
}

</script>
{/literal}
{elseif 'getjob' == $_A.query_type}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="*" class="main_td">卡号</td>
		<td width="" class="main_td">VIP类型/备注</td>
		<td width="" class="main_td">姓名</td>
		<td width="" class="main_td">性别</td>
		<td width="" class="main_td">身高</td>
		<td width="" class="main_td">电话</td>
		<td width="" class="main_td">学校</td>
		<td width="" class="main_td">校区</td>
		<td width="" class="main_td">专业</td>
		<td width="" class="main_td">年级</td>
		<td width="" class="main_td">自行车</td>
		<td width="" class="main_td">健康证</td>
		</tr>
	<tr class="tr2">
		<td class="main_td1" align="center">{$vip_user.serial_number}</td>
		<td class="main_td1" align="center">{$vip_user.vct_name}/{$vip_user.status_name}</td>
		<td class="main_td1" align="center">{$vip_user.realname}</td>
		<td class="main_td1" align="center">{if 1==$vip_user.sex}男{elseif 2==$vip_user.sex}女{else}无{/if}</td>
		<td class="main_td1" align="center">{$vip_user.height}</td>
		<td class="main_td1" align="center">{$vip_user.phone_number}</td>
		<td class="main_td1" align="center">{$vip_user.school}</td>
		<td class="main_td1" align="center">{$vip_user.school_area}</td>
		<td class="main_td1" align="center">{$vip_user.professional}</td>
		<td class="main_td1" align="center">{$vip_user.grade}</td>
		<td class="main_td1" align="center">{if 1==$vip_user.bike}有{elseif 2==$vip_user.bike}可借{else}无{/if}</td>
		<td class="main_td1" align="center">{if 1==$vip_user.health_certificate}有{elseif 2==$vip_user.health_certificate}可办理{else}无{/if}</td>
	</tr>
</table><br />

<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<!-- <tr>
			<td colspan="16" bgcolor="#ffffff" class="main_td1" height="40">
			岗位编号：<input type="text" name="number" id="number" value="{$magic.request.number}"/>
			商家名称：<input type="text" name="company" id="company" value="{$magic.request.company}"/>
				<input type="button" value="搜索" / onclick="sousuo()">
			</td>
		</tr> -->
		<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
		<tr >
			<td width="" class="main_td"><input type="checkbox" name="allcheck" onclick="checkFormAll(this.form)"/></td>
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">岗位编号</td>
			<td width="" class="main_td">商家名称</td>
			<td width="" class="main_td">工作岗位</td>
			<td width="" class="main_td">岗位类型</td>
			<td width="" class="main_td">人数</td>
			<td width="" class="main_td">性别要求</td>
			<td width="" class="main_td">底薪</td>
			<td width="" class="main_td">提成</td>
			<td width="" class="main_td">健康证</td>
			<td width="" class="main_td">自行车</td>
			<td width="" class="main_td">面试</td>
			<td width="" class="main_td">到期时间</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">操作|人员|详情</td>

		</tr>
		{ foreach  from=$result key=key item=item}
		<tr {if $key%2==1}class="tr2"{/if}>
			<td class="main_td1" align="center" ><input type="checkbox" name="aid[{$key}]" value="{$item.id}"/></td>
			<td class="main_td1" align="center" >{ $item.id}</td>
			<td class="main_td1" align="center">{$item.number}</td>
			<td class="main_td1" align="center" >{$item.company}</td>
			<td class="main_td1" align="center" >{$item.gangwei}</td>
			<td class="main_td1" align="center" >{$item.type}</td>
			<td class="main_td1" align="center" >{$item.apply_man+$item.apply_male+$item.apply_female}</td>
			<td class="main_td1" align="center" >{if $item.apply_man>0}无{else}男{$item.apply_male}人|女{$item.apply_female}人{/if}</td>
			<td class="main_td1" align="center">{$item.pay}</td>
			<td class="main_td1" align="center" >{if $item.is_award}有{else}无{/if}</td>
			<td class="main_td1" align="center" >{if $item.is_health_certificate}需要{else}不需要{/if}</td>
			<td class="main_td1" align="center">{if $item.is_bike}需要{else}不需要{/if}</td>
			<td class="main_td1" align="center" >{if $item.need_interview}需要{else}不需要{/if}</td>
			<td class="main_td1" align="center">{$item.end_time|date_format 'Y-m-d H:i:s'}</td>
			<td class="main_td1" align="center" >{ if $item.status ==1}显示{else}隐藏{/if}</td>
			<td class="main_td1" align="center" >{if !$is_working}<a href="{$_A.query_url}/admit&uid={$user_id}&jid={$item.id}" >加入</a>|{/if}<a href="{$_A.admin_url}&q=module/jianzhi/view{$_A.site_url}&id={$item.id}" >查看</a></td>
		</tr><input type="hidden" name="flag[{$key}]" value="{$item.flag}" />
		{ /foreach}
		<tr>
			<td colspan="13" class="action" >
				<div class="floatl"><select name="type">
				<option value="0">排序</option>
				<option value="1">显示</option>
				<option value="2">隐藏</option>
				<option value="3">推荐</option>
				<option value="4">头条</option>
				<option value="5">幻灯片</option>
				<option value="6">删除</option>&nbsp;&nbsp;&nbsp;
				</select> <input type="submit" value="确认操作" /> 排序不用全选
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="16" class="page">
			{$page}
			</td>
		</tr>
	</form>
</table>
	  <script>
	  var url = '{$_A.query_url}';
	    {literal}
	  	function sousuo(){
			var company = $("#company").val();
			var number = $("#number").val();
			location.href=url+"&company="+company+"&number="+number;
		}

	  </script>
	  {/literal}
{else}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="*" class="main_td">卡号</td>
		<td width="" class="main_td">VIP类型/备注</td>
		<td width="" class="main_td">姓名</td>
		<td width="" class="main_td">性别</td>
		<td width="" class="main_td">身高</td>
		<td width="" class="main_td">电话</td>
		<td width="" class="main_td">学校</td>
		<td width="" class="main_td">校区</td>
		<td width="" class="main_td">专业</td>
		<td width="" class="main_td">年级</td>
		<td width="" class="main_td">自行车</td>
		<td width="" class="main_td">健康证</td>
		<td width="" class="main_td">状态/操作/详情</td>
	</tr>
	{ foreach  from=$_A.vipuser_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.serial_number}</td>
		<td class="main_td1" align="center">{$item.vct_name}/<a href="{$_A.query_url}/editcard&id={$item.id}" title="编辑">{$item.status_name}</a></td>
		<td class="main_td1" align="center">{$item.realname}</td>
		<td class="main_td1" align="center">{if 1==$item.sex}男{elseif 2==$item.sex}女{else}无{/if}</td>
		<td class="main_td1" align="center">{$item.height}</td>
		<td class="main_td1" align="center">{$item.phone_number}</td>
		<td class="main_td1" align="center">{$item.school}</td>
		<td class="main_td1" align="center">{$item.school_area}</td>
		<td class="main_td1" align="center">{$item.professional}</td>
		<td class="main_td1" align="center">{$item.grade}</td>
		<td class="main_td1" align="center">{if 1==$item.bike}有{elseif 2==$item.bike}可借{else}无{/if}</td>
		<td class="main_td1" align="center">{if 1==$item.health_certificate}有{elseif 2==$item.health_certificate}可办理{else}无{/if}</td>
		<td class="main_td1" align="center" width="130">{if $item.lq_status}工作中{else}未工作 <a href="{$_A.query_url}/getjob&id={$item.user_id}{$_A.site_url}">安排</a>{/if} <a href="#">查看</a></td>
	</tr>
	{ /foreach}
	<tr>
		<td colspan="13" class="action" >
		<div class="floatl">
		
		</div>
		<div class="floatr">姓名：<input type="text" name="realname" id="realname" value="{$magic.request.realname}"/> VIP卡号：<input type="text" name="serial_number" id="serial_number" value="{$magic.request.serial_number}"/> <input type="button" value="搜索" / onclick="sousuo()">
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="13" class="page" >
		{$_A.showpage} 
		</td>
	</tr>
</table>
<script>
var url = '{$_A.query_url}{$_A.site_url}';
{literal}
function sousuo(){
	var realname = $("#realname").val();
	var keywords = $("#serial_number").val();
	location.href=url+"&realname="+realname+"&serial_number="+keywords;
}

</script>
{/literal}
{/if}