{if $_A.query_type == "new" || $_A.query_type == "edit"}

{/literal}
{elseif $_A.query_type == "view"}
<div class="module_add">
	<form name="form1" method="post" action="{$_A.query_url}/{ if $_A.query_type == "edit" }update{else}add{/if}{$_A.site_url}" onsubmit="return check_form();" enctype="multipart/form-data">
	<div class="module_title"><strong>�鿴�ظ�����</strong></div>
	<div class="module_border">
		<div class="l">�����ˣ�</div>
		<div class="c">
			{$_A.comment_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">ģ�飺</div>
		<div class="c">
			{$_A.comment_result.module_name}
		</div>
	</div>
	<div class="module_border">
		<div class="l">�������£�</div>
		<div class="c">
			<a href="/invest/a{$_A.comment_result.article_id}.html" target="_blank">{$_A.comment_result.title}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l"> �������ݣ�</div>
		<div class="c">
			{$_A.comment_result.comment}
		</div>
	</div>	{if $_A.reply.comment==""}
	<div class="module_border">
		<div class="l">�ظ���</div>		{$_A.reply!=""}
		<div class="c">
			<textarea name="comment" rows="5" cols="40"></textarea>
		</div>
	</div>
	<div class="module_submit border_b" >
		<input type="hidden" name="pid" value="{$_A.comment_result.id}" />		<input type="hidden" name="module_code" value="{$_A.comment_result.module_code}" />		<input type="hidden" name="article_id" value="{$_A.comment_result.article_id}" />		<input type="hidden" name="flag" value="{$_A.comment_result.flag}" />		<input type="hidden" name="status" value="{$_A.comment_result.status}" />
		<input type="submit" name="submit" value="ȷ���ύ" />
		<input type="reset" name="reset" value="���ñ�" />
	</div>	{else}	<div class="module_border">		<div class="l">�ظ���</div>		{$_A.reply!=""}		<div class="c">			{$_A.reply.comment}		</div>	</div>	{/if}
	</form>
</div>
{else}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
	<tr >
		<td  class="main_td"><input type="checkbox" name="allcheck" onclick="checkFormAll(this.form)"/></td>
		<td  class="main_td">ID</td>
		<td  class="main_td">��������</td>
		<td  class="main_td">��������</td>
		<td  class="main_td">������</td>
		<td  class="main_td">��������</td>
		<td  class="main_td">״̬</td>
		<td  class="main_td">���� 
		</td>
	</tr>
	{ foreach  from=$_A.comment_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center"><input type="checkbox" name="aid[{$key}]" value="{$item.id}"/></td>
		<td class="main_td1" align="center">{ $item.id}</td>
		<td class="main_td1" align="center">
				{if $item.module_code=='borrow'}
		<a target="_blank" href="/invest/a{$item.article_id}.html">�鿴</a>
		{else}
		<a target="_blank" href="/dongtai/a{$item.article_id}.html">�鿴</a>
		{/if}
		 </td>
		<td class="main_td1" align="center">{ $item.comment}</td>
		<td class="main_td1" align="center">{ $item.username}</td>
		<td class="main_td1" align="center">{ $item.addtime|date_format:"Y-m-d H:i"}</td>
		<td class="main_td1" align="center">{ if $item.status ==1}<a href="{$_A.query_url}{$_A.site_url}&status=0&id={ $item.id}">��ʾ</a>{else}<a href="{$_A.query_url}{$_A.site_url}&status=1&id={ $item.id}">����</a>{/if}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/view{$_A.site_url}&id={$item.id}&module_code={$item.module_code}" >�鿴</a> <a href="#" onClick="javascript:if(confirm('ȷ��Ҫɾ����?ɾ���󽫲��ɻָ�')) location.href='{$_A.query_url}/del{$_A.site_url}&id={$item.id}'">ɾ��</a> 
			</td>
	</tr><input type="hidden" name="flag[{$key}]" value="{$item.flag}" />
	{ /foreach}
	<tr >
		<td colspan="8" class="action">
		<div class="floatl"><select name="type">
 
			<option value="6">ɾ��</option>&nbsp;&nbsp;&nbsp;
			</select> <input type="submit" value="ȷ�ϲ���" />
			
		�û�����<input type="text" name="username" id="username" value="{$magic.request.username}"/>
		״̬��<select name="status" id="status">
			<option value="">ȫ��</option>
			<option value="0">����</option>
			<option value="1">��ʾ</option>
		</select>
		<input type="button" value="����" onclick="sousuo()" />
		 
			</td>
	</tr>
	<tr>
		<td colspan="8" class="page">
		{$page}
		 
		</td>
	</tr>
</table>
 <script>
	  var url = '{$_A.query_url}';
	    {literal}
	  	function sousuo(){
			var module_code = $("#module_code").val();
			var status = $("#status").val();
			location.href=url+"&status="+status+"&module_code="+module_code;
		}

	  </script>
	  {/literal}
{/if}