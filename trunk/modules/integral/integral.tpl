{if $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return check_form();" enctype="multipart/form-data">
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }编辑{else}添加{/if}积分</strong></div>
	<div class="module_border">
		<div class="l">礼品名称：</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{ $_A.integral_result.name}" size="30" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">属性：</div>
		<div class="c">
			{foreach from="$_A.flag_list" item="var"}<input type="checkbox" name="flag[]" value="{$var.nid}" {$var.nid|checked:$_A.integral_result.flag }/>{$var.name} {/foreach}
		</div>
	</div>
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			<input type="radio" name="status" value="0"  { if $_A.integral_result.status == 0 }checked="checked"{/if}/>隐藏 <input type="radio" name="status" value="1"  { if $_A.integral_result.status ==1 ||$_A.integral_result.status ==""}checked="checked"{/if}/>显示 
		</div>
	</div>
	<div class="module_border">
		<div class="l">排序：</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{ $_A.integral_result.order|default:10}" size="10" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">缩略图：</div>
		<div class="c">
			<input type="file" name="litpic" size="30" class="input_border"/>{if $_A.integral_result.litpic!=""}<a href="{$_A.integral_result.litpic}" target="_blank" title="有图片"><img src="{$_A.tpldir}/images/ico_1.jpg" border="0"  /></a><input type="checkbox" name="clearlitpic" value="1" />去掉缩略图{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">所需积分：</div>
		<div class="c">
			<input type="text" name="need"  class="input_border" value="{ $_A.integral_result.need}" onkeyup="value=value.replace(/[^0-9]/g,'')" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">数量：</div>
		<div class="c">
			<input type="text" name="number"  class="input_border" onkeyup="value=value.replace(/[^0-9]/g,'')" value="{ $_A.integral_result.number}" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">兑换城市：</div>
		<div class="c">
			<script src="./plugins/index.php?&q=area&area={$_A.integral_result.area}" type='text/javascript' language="javascript"></script>
		</div>
	</div>
	<div class="module_border">
		<div class="l">活动积分：</div>
		<div class="c">
			{$_A.integral_result.content}
		</div>
	</div>
	<div class="module_border" style="text-align:center">
		{if $_A.query_type == "edit"}		<input type="hidden" name="id" value="{ $_A.integral_result.id }"/>		{/if}
		<input type="submit" name="submit" value="确认提交" />
		<input type="reset" name="reset" value="重置表单" />
	</div>
	</form>
</div>
{literal}
<script>
function check_form(){
	 var frm = document.forms['form1'];
	 var title = frm.elements['name'].value;
	 var content = frm.elements['content'].value;
	 var errorMsg = '';
	  if (title.length == 0 ) {
		errorMsg += '标题必须填写' + '\n';
	  }
	  if ($("#site_center").val()==""){
		errorMsg += '请选择栏目' + '\n';
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
	<div class="module_title"><strong>查看</strong></div>
	
	<div class="module_border">
		<div class="l">物品名称：</div>
		<div class="c">
			{ $_A.integral_result.name}
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">属性：</div>
		<div class="c">
			 {$_A.integral_result.flag|flag}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			{ if $_A.integral_result.status == 0 }隐藏{else}显示{/if} 
		</div>
	</div>

	<div class="module_border">
		<div class="l">排序：</div>
		<div class="c">
			{ $_A.integral_result.order}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">缩略图：</div>
		<div class="c">
			{if $_A.integral_result.litpic!=""}<a href="./{$_A.integral_result.litpic}" target="_blank" title="有图片"><img src="{ $_A.tpldir }/images/ico_1.jpg" border="0"  /></a>{/if}
		</div>
	</div>


	<div class="module_border">
		<div class="l">所需积分：</div>
		<div class="c">
			{ $_A.integral_result.need}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">数量：</div>
		<div class="c">
			{ $_A.integral_result.number}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">兑换城市：</div>
		<div class="c">
			{$_A.integral_result.area|area}
		</div>
	</div>

	<div class="module_border">
		<div class="l">活动内容：</div>
		<div class="c">
			{$_A.integral_result.content}
		</div>
	</div>
</div>
{elseif $_A.query_type=="convert"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
	<tr >
		<td class="main_td">ID</td>
		<td class="main_td">礼品名称</td>
		<td  class="main_td">兑换人</td>
		<td class="main_td">数量</td>
		<td class="main_td">分值</td>
		<td class="main_td">总兑换积分</td>
		<td  class="main_td">兑换时间</td>
		<td  class="main_td">状态</td>
		<td class="main_td">操作</td>
	</tr>
	{ foreach  from=$_A.integral_convert_list key=key item=item}
	<tr  {if $key%2==1}class="tr2"{/if}>
		<td>{ $item.id}</td>
		<td><a href="{$_A.query_url}/view&id={$item.integral_id}{$_A.site_url}">{$item.goods_name}</a></td>
		<td>{ $item.realname}({ $item.username})</td>
		<td>{$item.number}</td>
		<td>{$item.need}</td>
		<td>{ $item.integral}</td>
		<td>{ $item.addtime|date_format:"Y-m-d H:i"}</td>
		<td >{ if $item.status ==1}已兑换{ elseif $item.status ==2}关闭{else}未兑换{/if}</td>
		<td><a href="{$_A.query_url}/convert_view&id={$item.id}">查看</a></td>
	</tr><input type="hidden" name="flag[{$key}]" value="{$item.flag}" />
	{ /foreach}
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
	var goods = $("#goods").val();
	location.href=url+"&goods="+goods;
}
</script>
{/literal}
{elseif $_A.query_type == "convert_view"}
<div class="module_add">
	<form action="" name="form1" method="post">
	<div class="module_title"><strong>兑换信息查看</strong></div>
	<div class="module_border">
		<div class="l">物品名称：</div>
		<div class="c">
			<a href="{$_A.query_url}/view&id={$_A.integral_convert_result.integral_id}{$_A.site_url}" target="_blank">{ $_A.integral_convert_result.goods_name}</a>
		</div>
	</div>
	

	<div class="module_border">
		<div class="l">兑换人：</div>
		<div class="c">
			{ $_A.integral_convert_result.realname}({ $_A.integral_convert_result.username})
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">兑换数量：</div>
		<div class="c">
			{ $_A.integral_convert_result.number}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">兑换分值：</div>
		<div class="c">
			{ $_A.integral_convert_result.need}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">应扣积分：</div>
		<div class="c">
			{ $_A.integral_convert_result.integral}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			{if $_A.integral_convert_result.status!=1}
			<input type="radio" name="status" value="0" checked="checked" />未兑换 <input type="radio" name="status" value="1" />已兑换 <input type="radio" name="status" value="2" />关闭此兑换（关闭后将会将积分返回到用户的积分总数去）
			{else}
				已兑换
			{/if}
		</div>
	</div>
	
	{if $_A.integral_convert_result.status==0}
	<div class="module_border">
		<div class="l">备注：</div>
		<div class="c">
			<textarea name="remark" cols="50" rows="7">{$_A.integral_convert_result.remark}</textarea>
		</div>
	</div>
	
	<div class="module_submit">
		<input type="hidden" name="id" value="{$_A.integral_convert_result.id}" />
		<input type="submit" value="确认" /> 一旦确定将不能修改
	</div>
	{else}
	<div class="module_border">
		<div class="l">备注：</div>
		<div class="c">
			{$_A.integral_convert_result.remark}
		</div>
	</div>
	{/if}
	</form>

</div>
{literal}
<script>
function check_form(){
	 var frm = document.forms['form1'];
	 var remark = frm.elements['remark'].value;
	 var errorMsg = '';
	  if (remark.length == 0 ) {
		errorMsg += '备注不能为空' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{
		return true;
	  }
}
</script>
{/literal}
{else}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
	<tr >
		<td class="main_td"><input type="checkbox" name="allcheck" onclick="checkFormAll(this.form)"/></td>
		<td class="main_td">ID</td>
		<td class="main_td">物品名称</td>
		<td class="main_td">所需积分</td>
		<td class="main_td">数量</td>
		<td  class="main_td">已兑换数量</td>
		<td  class="main_td">状态</td>
		<td  class="main_td">属性</td>
		<td class="main_td">操作</td>
	</tr>
	{ foreach  from=$_A.integral_list key=key item=item}
	<tr  {if $key%2==1}class="tr2"{/if}>
		<td><input type="checkbox" name="aid[{$key}]" value="{$item.id}"/></td>
		<td>{ $item.id}</td>
		<td>{$item.name}</td>
		<td>{$item.need}</td>
		<td>{ $item.number}</td>
		<td>{ $item.ex_number}</td>
		<td >{ if $item.status ==1}<a href="{$_A.query_url}{$_A.site_url}&status=0&id={ $item.id}">显示</a>{else}<a href="{$_A.query_url}{$_A.site_url}&status=1&id={ $item.id}">隐藏</a>{/if}</td>
		<td >{$item.flagname|default:-}{if $item.litpic!=""}图片{/if}</td>
		<td><a href="{$_A.query_url}/convert&id={$item.id}">兑换人</a>  <a href="{$_A.query_url}/view&id={$item.id}{$_A.site_url}">查看</a> <a href="{$_A.query_url}/edit&id={$item.id}{$_A.site_url}">编辑</a> <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/del&id={$item.id}{$_A.site_url}'">删除</a></td>
	</tr><input type="hidden" name="flag[{$key}]" value="{$item.flag}" />
	{ /foreach}
	<tr>
		<td colspan="9" class="action">
		<div class="floatl"> 
		<select name="type">
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
		物品名称：<input type="text" name="goods" id="goods" value="{$magic.request.goods}"/>
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
<script>
 var url = '{$_A.query_url}';
{literal}
function sousuo(){
	var goods = $("#goods").val();
	location.href=url+"&goods="+goods;
}
</script>
{/literal}
{/if}