{if $_A.query_type == "list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">����Ա��</td>
		<th width="" class="main_td">���ʱ��</th>
		<th width="" class="main_td">״̬</th>
		<th width="" class="main_td">����Ա����</th>
		<th width="" class="main_td">��¼����</th>
		<th width="" class="main_td">����</th>
		<td width="" class="main_td">����</td>
	</tr>
	<form action="" method="post">
	{ foreach  from=$_A.user_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.user_id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center" >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center" >{if $item.islock ==1}����{else}��ͨ{/if}</td>
		<td class="main_td1" align="center" >{$item.typename}</td>
		<td class="main_td1" align="center">{$item.logintime}</td>
		<td class="main_td1" align="center"><input type="text" name="order[]" size="4" value="{$item.order|default:10}" /><input type="hidden" value="{$item.user_id}" name="user_id[]" /></td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/edit&a=system&user_id={$item.user_id}{$_A.site_url}">�޸�</a> / {if $item.islock!=1}<a href="#" onClick="javascript:if(confirm('ȷ��Ҫ������?�������û������ܵ�¼ϵͳ')) location.href='{$_A.query_url}/lock&islock=1&user_id={$item.user_id}{$_A.site_url}'">����</a>{else}<a href="#" onClick="javascript:if(confirm('ȷ��Ҫ������?�������û�����������¼ϵͳ')) location.href='{$_A.query_url}/lock&islock=0&user_id={$item.user_id}{$_A.site_url}'">����</a>{/if} / <a href="{$_A.query_url}/del&user_id={$item.user_id}{$_A.site_url}">ɾ��</a></td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="8" class="page">
		<input type="submit" value="�޸�����" /
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
		<td width="" class="main_td">ҵ��Ա��</td>
		<th width="" class="main_td">���ʱ��</th>
		<th width="" class="main_td">״̬</th>
		<th width="" class="main_td">����</th>
		<td width="" class="main_td">����</td>
	</tr>
	<form action="" method="post">
	{foreach from=$_A.user_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.user_id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center" >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center" >{if $item.status==1}��ͨ{else}����{/if}</td>
		<td class="main_td1" align="center" >{$item.typename}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/edit&a=system&salesman=1&user_id={$item.user_id}{$_A.site_url}">�޸�</a> / <a href="#" onClick="javascript:if(confirm('ȡ�����û�����Ϊ��ͨ�û���ȷ��Ҫ����ô��')) location.href='{$_A.query_url}/del&user_id={$item.user_id}{$_A.site_url}'">ȡ��ҵ��Ա</a></td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="8" class="page">
		<a href="{$_A.query_url}/new&a=system&salesman=1">���ҵ��Ա</a>
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
	<div class="module_title"><strong>{if $_A.query_type == "edit"}�༭{else}���{/if}����Ա</strong></div>
	
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			{if $_A.query_type != "edit"}<input name="username" type="text" class="input_border" />{else}{ $_A.user_result.username}<input name="username" type="hidden"  class="input_border" value="{$_A.user_result.username}" />{/if} <font color="#FF0000">*�����</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��¼���룺</div>
		<div class="c">
			<input name="password" type="password" class="input_border" />{if $_A.query_type == "edit" } ���޸���Ϊ��{/if} <font color="#FF0000">*�����</font>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">ȷ�����룺</div>
		<div class="c">
			<input name="password1" type="password" class="input_border" />{if $_A.query_type == "edit" } ���޸���Ϊ��{/if} <font color="#FF0000">*�����</font>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��ʵ������</div>
		<div class="c">
			<input name="realname" type="text" value="{$_A.user_result.realname}" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�ԡ��� </div>
		<div class="c">
			<input type="radio" name="sex" value="0" {if $_A.user_result.sex==0 || $_A.user_result.sex==""} checked="checked" {/if}/>
		����&nbsp;&nbsp;
		<input type="radio" name="sex" value="1" {if $_A.user_result.sex==1} checked="checked" {/if} />
		��&nbsp;&nbsp;
		<input type="radio" name="sex" value="2"  {if $_A.user_result.sex==2} checked="checked" {/if}/>
	  Ů&nbsp;&nbsp; 
		</div>
	</div>
	
	  <div class="module_border">
		<div class="l">���գ�</div>
		<div class="c">
		<input type="text" name="birthday" class="input_border" value="{$_A.user_result.birthday}" size="20" onclick="change_picktime()"/>
		</div>
	</div>
	
	  <div class="module_border">
		<div class="l">���ͣ� </div>
		<div class="c">
			{if $_A.user_result.type==3 || $_A.salesman == 1}
			ҵ��Ա<input type="hidden" name="type_id" value="19" />
			{else}
			{html_options name="type_id" options=$list_type selected=$_A.user_result.type_id}
			{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			 <input name="status" type="radio" value="0"  {if $_A.user_result.status=="0"} checked="checked"{/if}/>�ر�<input name="status" type="radio" value="1" { if $_A.user_result.status==1 || $_A.user_result.status==""} checked="checked"{/if}/>��ͨ
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">���ڵأ�</div>
		<div class="c">
			<script src="./plugins/index.php?&q=area&area={$_A.user_result.area}" type='text/javascript' language="javascript"></script>
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">�����ʼ���ַ�� </div>
		<div class="c">
			<input name="email" value="{ $_A.user_result.email }" type="text"  class="input_border" /> <font color="#FF0000">*�����</font>
		</div>
	</div>
	<div class="module_border">
		<div class="l">QQ��</div>
		<div class="c">
			<input name="qq" type="text" value="{ $_A.user_result.qq }" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">������</div>
		<div class="c">
			<input name="wangwang" type="text" value="{ $_A.user_result.wangwang }" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��ͥ�绰��</div>
		<div class="c">
			<input name="tel" type="text" value="{ $_A.user_result.tel }" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�ֻ���</div>
		<div class="c">
			<input name="phone" type="text" value="{$_A.user_result.phone}" class="input_border" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��ϸ��ַ��</div>
		<div class="c">
			<input name="address" type="text" value="{$_A.user_result.address}" class="input_border" />
		</div>
	</div>
	<!--<div class="module_border">
		<div class="l">�������к�SN��</div>
		<div class="c">
			<input name="serial_id" type="text" value="{$_A.user_result.serial_id}" class="input_border" />
		</div>
	</div>-->
	<div class="module_submit border_b" >
	{if $_A.query_type == "edit"}<input type="hidden" name="user_id" value="{ $_A.user_result.user_id}" />{/if}
	<input type="submit" value="ȷ���ύ" />
	<input type="reset" name="reset" value="���ñ�" />
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
		errorMsg += '�û�������Ϊ��' + '\n';
	  }
	   if (username.length<2) {
		errorMsg += '�û������Ȳ�������2λ' + '\n';
	  }
	  if (password.length==0) {
		errorMsg += '���벻��Ϊ��' + '\n';
	  }
	  if (password.length<6) {
		errorMsg += '���볤�Ȳ���С��6λ' + '\n';
	  }
	   if (password.length!=password1.length) {
		errorMsg += '�������벻һ��' + '\n';
	  }
	   if (email.length==0) {
		errorMsg += '���䲻��Ϊ��' + '\n';
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
		<td class="main_td">��������</td>
		<td class="main_td">��Ҫ</td>
		<td class="main_td">��ע</td>
		<td class="main_td">����</td>
		<td class="main_td">״̬</td>
		<td class="main_td">����</td>
	</tr>
	<form action="{$_A.query_url}/type_order" method="post">
	{ foreach from = $_A.user_type_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td bgcolor="#ffffff" >{$item.name}</td>
		<td bgcolor="#ffffff" >{$item.summary}</td>
		<td bgcolor="#ffffff">{$item.remark}</td>
		<td bgcolor="#ffffff"><input name="order[]" size="2" value="{ $item.order}"type="text" ><input name="type_id[]" type="hidden" size="2" value="{ $item.type_id}" ></td>
		<td  bgcolor="#ffffff" >{ if $item.status==1}��ͨ{else}<font color=red>�ر�</font>{/if}</td>
		<td bgcolor="#ffffff"><a href="{$_A.query_url}/type_edit&a=system&type_id={$item.type_id}">�޸�</a>/<a href="#" onclick="javascript:if(confirm('ȷ��Ҫɾ����?������')) location.href='{$_A.query_url}/type_del&type_id={$item.type_id}'">ɾ��</a></td>
	</tr>
	{ /foreach}
	<tr>
		<td   colspan="6" class="action"><input type="button" onclick="javascript:location.href='{$_A.query_url}/type_new{$_A.site_url}'" value="�������" />  <input type="submit" value="�޸�����" /> </td>
	</tr>
	</form>
</table>
{ elseif $_A.query_type == "type_new" || $_A.query_type == "type_edit" }
<div class="module_add">
	
	<form enctype="multipart/form-data" name="form1" method="post" action="" onsubmit="return check_form();"  >
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }�༭{else}���{/if}����Ա����</strong></div>
	
	<div class="module_border">
		<div class="l">��������:</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{ $result.name}" size="30" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">����:</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{ $result.order|default:10}" size="10" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
			<input type="radio" name="status" value="0"  { if $result.status == 0 }checked="checked"{/if}/> �ر�<input type="radio" name="status" value="1"  { if $result.status ==1 ||$result.status ==""}checked="checked"{/if}/>��ͨ
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��Ҫ˵��:</div>
		<div class="c">
			<textarea name="summary" cols="55" rows="6" >{ $result.summary}</textarea>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��ע:</div>
		<div class="c">
			<textarea name="remark" cols="55" rows="6" >{ $result.remark}</textarea>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">Ȩ��:</div>
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
					 <input type="checkbox" title="ȫѡ" onclick="changeAll('{$key}','_{$key}')" id="_{$key}"/></div>
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
		<input type="submit" value="ȷ���ύ" />
		<input type="reset" name="reset" value="���ñ�" />
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
		errorMsg += '�������Ʊ�����д' + '\n';
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
<p style="text-align:center">�û�������</p>
{else}
<div class="module_title"><strong>�û���Ϣ</strong></div>
<div class="module_border">
	<div class="l">�û�����</div>
	<div class="c">
		{$_A.del_user.user_info.username}
	</div>
</div>
<div class="module_border">
	<div class="l">ע��ʱ�䣺</div>
	<div class="c">
		{$_A.del_user.user_info.addtime|date_format:"Y-m-d H:i:s"}
	</div>
</div>
<div class="module_border">
	<div class="l">��������</div>
	<div class="c">
		{$_A.del_user.user_info.logintime|default:0}��
	</div>
</div>
<div class="module_border">
	<div class="l">����¼ʱ�䣺</div>
	<div class="c">
		{$_A.del_user.user_info.lasttime|date_format:"Y-m-d H:i:s"}
	</div>
</div>
<div class="module_title"><strong>�˻���Ϣ</strong></div>
<div class="module_border">
	<div class="l">�˺��ܶ</div>
	<div class="c">
		{$_A.del_user.account.total|default:0}Ԫ
	</div>
</div>
<div class="module_border">
	<div class="l">������</div>
	<div class="c">
		{$_A.del_user.account.use_money|default:0}Ԫ
	</div>
</div>
<div class="module_border">
	<div class="l">�����ʽ�</div>
	<div class="c">
		{$_A.del_user.account.no_use_money|default:0}Ԫ
	</div>
</div>
<div class="module_border">
	<div class="l">�����ʽ�</div>
	<div class="c">
		{$_A.del_user.account.collection|default:0}Ԫ
	</div>
</div>
<div class="module_title"><strong>������Ϣ</strong></div>
{if $_A.del_user.borrow==''}
<p style="text-align:center">���û�δ������</p>
{else}
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
    <tr>
		<td width="" class="main_td">�����</td>
 		<td width="" class="main_td">������</td>
		<td width="" class="main_td">�����</td>
		<td width="" class="main_td">����</td>
		<td width="" class="main_td">�������</td>
		<td width="" class="main_td">����ʱ��</td>
		<td width="" class="main_td">״̬</td>
	</tr>
	{foreach from=$_A.del_user.borrow item=item}
    <tr>
		<td>{$item.id}</td>
 		<td><a href="/invest/a{$item.id}.html" target="_blank">{$item.name}</a></td>
		<td>{$item.account}</td>
		<td>{$item.apr}%</td>
		<td>{if $item.isday==1}{$item.time_limit_day}��{else}{$item.time_limit}����{/if}</td>
		<td>{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td>{if $item.status==0}�ȴ�����{elseif $item.status==1}�б���{elseif $item.status==3 && $item.account=repayment_yesaccount}�ѻ���{elseif $item.status==3 && $item.account>repayment_yesaccount}������{elseif $item.status==5}������{/if}</td>
	</tr>
    {/foreach}
    </table>
{/if}
<div class="module_title"><strong>Ͷ����Ϣ</strong></div>
{if $_A.del_user.borrow_tender==''}
<p style="text-align:center">���û�δͶ����</p>
{else}
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
    <tr>
		<td class="main_td">��id</td>
 		<td class="main_td">����</td>
		<td class="main_td">Ͷ����Ч���</td>
		<td class="main_td">Ͷ��ʱ��</td>
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
<div class="module_title"><strong>ɾ������</strong></div>
{if $_A.del_user.borrow_tender=='' && $_A.del_user.borrow==''}
<form action="" method="post">
<div class="module_border" >
	<div class="l">��֤��:</div>
	<div class="c">
		<input type="text" name="valicode" maxlength="4" /><img src="/plugins/index.php?q=imgcode" alt="���ˢ��" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" align="absmiddle" style="cursor:pointer;">
	</div>
</div>
<div class="module_border" >
	<div class="l"></div>
	<div class="c">
    	<input type="hidden" value="{$_A.del_user.user_info.user_id}" name="user_id" />
		<input type="submit" value="ȷ��ɾ���û�" />
	</div>
</div>
</form>
{/if}
<p style="color:#F00;text-align:center">���棺�û�һ��ɾ�������ɻָ���ɾ���û���ͬʱ��ɾ���йظ��û���������Ϣ���������ݳ���Ͷ���꣬��������û�һ���޷�ɾ��</p>
{/if}
{/if}