{if $_A.query_type == "new" || $_A.query_type == "edit"}
<form name="form1" method="post" action="" onsubmit="return check_form();" enctype="multipart/form-data" >
<div class="module_add">
	<div class="module_title"><strong>添加链接</strong></div>
	
	<div class="module_border">
		<div class="l">网站名称：</div>
		<div class="c">
			<input type="text" name="webname"  class="input_border" value="{ $_A.arealinks_result.webname}" size="30" />  
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">地区：</div>
		<div class="c">
		<script src="./plugins/index.php?&q=area&area={$_A.arealinks_result.area}&type=p,c" type='text/javascript' language="javascript"></script>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">定义属性：</div>
		<div class="c">
		{foreach from="$_A.flag_list" item="var"}
		<input type="checkbox" name="flag[]" value="{$var.nid}" {$var.nid|checked:$_A.arealinks_result.flag } />{$var.name} 
		{/foreach}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c"><input type="radio" name="status" value="0"  { if $_A.arealinks_result.status == 0 }checked="checked"{/if}/>隐藏 <input type="radio" name="status" value="1"  { if $_A.arealinks_result.status ==1 ||$_A.arealinks_result.status ==""}checked="checked"{/if}/>显示 
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">排序：</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{ $_A.arealinks_result.order|default:10}" size="10" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">LOGO:</div>
		<div class="c">
			<input type="file" name="logo"  class="input_border" size="20" />{if $_A.arealinks_result.logo!=""}<a href="./{$_A.arealinks_result.logo}" target="_blank" title="有图片"><img src="{ $tpldir }/images/ico_1.jpg" border="0"  /></a><input type="checkbox" name="clearlogo" value="1" />去掉缩略图{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">网站地址:</div>
		<div class="c">
			<input type="text" name="url"  class="input_border" value="{ $_A.arealinks_result.url}" size="30" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">网站pr值:</div>
		<div class="c">
			<input type="text" name="pr"  class="input_border" value="{ $_A.arealinks_result.pr}" size="5" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">联系人:</div>
		<div class="c">
			<input type="text" name="linkman"  class="input_border" value="{ $_A.arealinks_result.linkman}" size="20" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">Email:</div>
		<div class="c">
			<input type="text" name="email"  class="input_border" value="{ $_A.arealinks_result.email}" size="20" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">网站简介:</div>
		<div class="c">
			<textarea name="summary" cols="40" rows="5">{$_A.arealinks_result.summary}</textarea>
		</div>
	</div>
	
	<div class="module_submit" >
		{ if $_A.query_type == "edit" }<input type="hidden" name="id" value="{ $_A.arealinks_result.id }" />{/if}
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
</div>
</form>
{literal}
<script>
function check_form(){
	 var frm = document.forms['form1'];
	 var webname = frm.elements['webname'].value;
	 var url = frm.elements['url'].value;
	 var errorMsg = '';
	  if (webname.length == 0 ) {
		errorMsg += '网站标题必须填写' + '\n';
	  }
	  if (url.length == 0 ) {
		errorMsg += '链接地址不能为空' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return true;
	  }
}
</script>
{/literal}
{elseif $t == "view"}
<div class="module_add">

	<div class="module_title"><strong>链接查看</strong></div>
	
	<div class="module_border">
		<div class="l">网站名称：</div>
		<div class="c">
			{ $_A.arealinks_result.webname}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">类别：</div>
		<div class="c">
			{ $_A.arealinks_result.typename}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			{ if $_A.arealinks_result.status ==1 ||$_A.arealinks_result.status ==""}隐藏{else}显示{/if} 
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">排序：</div>
		<div class="c">
			{ $_A.arealinks_result.order}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">LOGO:</div>
		<div class="c">
			{if $_A.arealinks_result.logo!=""}<a href="./{$_A.arealinks_result.logo}" target="_blank" title="有图片"><img src="{ $tpldir }/images/ico_1.jpg" border="0"  /></a>{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">网站地址：</div>
		<div class="c">
			{ $_A.arealinks_result.url}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">联系人：</div>
		<div class="c">
			{ $_A.arealinks_result.linkman}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">简介:</div>
		<div class="c">
			{ $_A.arealinks_result.summary}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">添加时间/IP:</div>
		<div class="c">
			{$_A.arealinks_result.addtime|date_format:'Y-m-d'}/{$_A.arealinks_result.addip}</div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c"><input type="button"  name="reset" value="修改链接" onclick="javascript:location.href('{$_A.query_url}/edit&id={$_A.arealinks_result.id}')"/>
		</div>
	</div>
</div>
{else}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="{$_A.query_url}/action{$_A.site_url}" method="post">
		<tr >
			<td class="main_td">ID</td>
			<td class="main_td">网站名称</td>
			<td class="main_td">所在城市</td>
			<td class="main_td">状态</td>
			<td class="main_td">排序</td>
			<td class="main_td">添加时间</td>
			<td class="main_td">网站logo</td>
			<td class="main_td">操作</td>
		</tr>
		{ foreach  from=$_A.arealinks_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{ $item.id}</td>
			<td>{$item.webname}</td>
			<td>{$item.city_name}</td>
			<td >{ if $item.status ==1}显示{else}隐藏{/if}</td>
			<td><input type="text" value="{$item.order|default:10}" name="order[]" size="5" /><input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td>{$item.addtime|date_format:"Y-m-d"}</td>
			<td >{if $item.logo!=""}<a href="./{$item.logo}" target="_blank"><img height="20" src="./{$item.logo}" border="0" /></a>{else}无logo{/if}</td>
			<td><a href="{$_A.query_url}/edit&id={$item.id}">修改</a> / <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/del&id={$item.id}'">删除</a></td>
		</tr>
		{ /foreach}
		<tr>
			<td colspan="12" class="action">
			<div class="floatl">
			<script>
	  var url = '{$_A.query_url}';
	    {literal}
	  	function sousuo(){
			var username = $("#username").val();
			var keywords = $("#keywords").val();
			location.href=url+"&keywords="+keywords;
		}
	  
	  </script>
	  {/literal}
			</div>
			<div class="floatr">
				关键字：<input type="text" name="keywords" id="keywords" value="{$magic.request.keywords|urldecode}"/> <input type="button" value="搜索" / onclick="sousuo()">
			</div>
			</td>
		</tr>
		
		<tr >
		<td colspan="12"  class="page" height="30">
			<input value="0" name="type" type="hidden" />{$_A.showpage}
		</td>
	</tr>
	<tr >
		<td colspan="12"  class="submit" height="30">
			<input type="submit" name="submit" value="确认提交" />
		</td>
	</tr>
	</form>	
</table>
{/if}