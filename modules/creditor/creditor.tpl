{if $_A.query_type=="list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">ԭʼ����</td>
			<td width="" class="main_td">������</td>
 			<td width="" class="main_td">����ծȨ</td>
			<td width="" class="main_td">���۱���</td>
			<td width="" class="main_td">���۽��</td>
			<td width="" class="main_td">��Ч��</td>
			<td width="" class="main_td">ծȨʣ��ʱ��</td>
			<td width="" class="main_td">�ύʱ��</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">����</td>
		</tr>
		{foreach  from=$_A.zqzr_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1"><a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name}</a></td>
 			<td>{$item.username}</td>
			<td>{$item.y_account}Ԫ</td>
			<td>100%</td>
			<td>{$item.account}Ԫ</td>
			<td>{$item.valid_time}��</td>
			<td>{$item.show_y_timelimit}</td>
			<td>{$item.addtime|date_format}</td>
			<td>
				{if $item.status ==0}
				�ȴ�����
				{elseif $item.status ==2}
				����ʧ��
				{elseif $item.status ==4}
				����ʧ��
				{elseif $item.status ==5}
				�û�ȡ��
                {elseif $item.status ==3}
                ���
				{elseif $item.account>$item.account_yes}
				����ת����..
				{else}
				���������
				{/if}
			</td>
			<td>
				{if $item.status ==0}
				<a href="{$_A.query_url}/view{$_A.site_url}&id={$item.id}">����</a>
				{elseif $item.status ==2}
				--
				{elseif $item.status ==4}
				--
				{elseif $item.status ==5}
				--
                {elseif $item.status ==3}
				--
				{elseif $item.account>$item.account_yes}
				<a href="{$_A.query_url}/repeal{$_A.site_url}&id={$item.id}">����</a>
				{else}
				<a href="{$_A.query_url}/full{$_A.site_url}&id={$item.id}">����</a>
				{/if}
			</td>
		</tr>
		{/foreach}
		<!--<tr>
		<td colspan="9" >
		<div class="action">
			<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="������ǰ�б�" />
			</div>
			<div class="floatr">
				�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> 
				�����ͣ�<select name="biaoType" id="biaoType">
						<option value=""> ����</option>
						<option value="fast" {if $magic.request.biaoType=='fast'}selected{/if} >��Ѻ��</option>
						<option value="jin" {if $magic.request.biaoType=='jin'}selected{/if} >��ֵ��</option>
						<option value="miao" {if $magic.request.biaoType=='miao'}selected{/if} >�뻹��</option>
						<option value="xin" {if $magic.request.biaoType=='xin'}selected{/if} >���ñ�</option>
						<option value="lz" {if $magic.request.biaoType=='lz'}selected{/if} >��ת��</option>
					</select>
				״̬��<select id="status" ><option value="">ȫ��</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>�����б�..</option><option value="3" {if $magic.request.status==3} selected="selected"{/if}>������ɹ�</option><option value="5" {if $magic.request.status=="5"} selected="selected"{/if}>����δͨ��</option><option value="4" {if $magic.request.status=="4"} selected="selected"{/if}>���긴��ʧ��</option></select>
				����ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()" /> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()" />
				<input type="button" value="����" onclick="sousuo()" />
			</div>
		</div>
		</td>
		</tr>-->
		<tr>
			<td colspan="10" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table>

<!--����-->
{elseif $_A.query_type=="view"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return submit_fool();" enctype="multipart/form-data" >
	<div class="module_title"><strong>ծȨת����Ϣ</strong></div>
	<div class="module_border">
		<div class="l">ԭʼ���꣺</div>
		<div class="c">
		<a href="/invest/a{$_A.zqzr_result.borrow_id}.html" target="_blank">{$_A.zqzr_result.borrow_name}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ˣ�</div>
		<div class="c">
		{$_A.zqzr_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����ծȨ��</div>
		<div class="c">
		{$_A.zqzr_result.y_account}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ۼ۸�</div>
		<div class="c">
		{$_A.zqzr_result.account}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">ÿ�ݼ۸�</div>
		<div class="c">
		{$_A.zqzr_result.every_account}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">������</div>
		<div class="c">
		{$_A.zqzr_result.account/$_A.zqzr_result.every_account}��
		</div>
	</div>
	<div class="module_border">
		<div class="l">ÿ�ݴ��գ�</div>
		<div class="c">
		{$_A.zqzr_result.every_collection}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ч�ڣ�</div>
		<div class="c">
		{$_A.zqzr_result.valid_time}
		</div>
	</div>
	<div class="module_border">
		<div class="l">ծȨʣ��ʱ�䣺</div>
		<div class="c">
		{$_A.zqzr_result.show_y_timelimit}
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ύʱ�䣺</div>
		<div class="c">
		{$_A.zqzr_result.addtime|date_format}
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			{if $_A.zqzr_result.status ==0}
			�ȴ�����
			{elseif $_A.zqzr_result.status ==2}
			����ʧ��
			{elseif $_A.zqzr_result.status ==4}
			����ʧ��
			{elseif $_A.zqzr_result.status ==5}
			�û�ȡ��
			{elseif $_A.zqzr_result.account>$_A.zqzr_result.account_yes}
			����ת����..
			{else}
			���������
			{/if}
		</div>
	</div>
	<div class="module_title"><strong>ծȨת�ó���</strong></div>
	{if $_A.zqzr_result.status==0}
	<div class="module_border">
		<div class="l">��������</div>
		<div class="c">
		<label><input type="radio" value="2" name="status" checked="checked" />��ͨ��</label>
		<label><input type="radio" value="1" name="status" />ͨ��</label>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ע��</div>
		<div class="c">
			<textarea style="width:300px;height:100px" name="verify_remark"></textarea>
		</div>
	</div>
    <div class="module_border">
		<div class="l">ע����</div>
		<div class="c" style="color:red">
			һ�����ͨ���ñ�ծȨ��ֱ�ӽ��׸���վ
		</div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c">
			<input type="hidden" value="{$_A.zqzr_result.id}" name="id" />
			<input type="submit" value="ȷ���ύ" />
		</div>
	</div>
	{/if}
	
	</form>
</div>
{elseif $_A.query_type=="fulllist"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">ԭʼ����</td>
			<td width="" class="main_td">������</td>
 			<td width="" class="main_td">����ծȨ</td>
			<td width="" class="main_td">���۱���</td>
			<td width="" class="main_td">���۽��</td>
			<td width="" class="main_td">��Ч��</td>
			<td width="" class="main_td">ծȨʣ��ʱ��</td>
			<td width="" class="main_td">�ύʱ��</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">����</td>
		</tr>
		{foreach  from=$_A.zqzr_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1"><a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name}</a></td>
 			<td>{$item.username}</td>
			<td>{$item.y_account}Ԫ</td>
			<td>100%</td>
			<td>{$item.account}Ԫ</td>
			<td>{$item.valid_time}��</td>
			<td>{$item.show_y_timelimit}</td>
			<td>{$item.addtime|date_format}</td>
			<td>
				{if $item.status ==0}
				�ȴ�����
				{elseif $item.status ==2}
				����ʧ��
				{elseif $item.status ==4}
				����ʧ��
				{elseif $item.status ==5}
				�û�ȡ��
				{elseif $item.account>$item.account_yes}
				����ת����..
				{else}
				���������
				{/if}
			</td>
			<td>
				{if $item.status ==0}
				<a href="{$_A.query_url}/view{$_A.site_url}&id={$item.id}">����</a>
				{elseif $item.status ==2}
				--
				{elseif $item.status ==4}
				--
				{elseif $item.status ==5}
				--
				{elseif $item.account>$item.account_yes}
				<a href="{$_A.query_url}/repeal{$_A.site_url}&id={$item.id}">����</a>
				{else}
				<a href="{$_A.query_url}/full{$_A.site_url}&id={$item.id}">����</a>
				{/if}
			</td>
		</tr>
		{/foreach}
		<!--<tr>
		<td colspan="9" >
		<div class="action">
			<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="������ǰ�б�" />
			</div>
			<div class="floatr">
				�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> 
				�����ͣ�<select name="biaoType" id="biaoType">
						<option value=""> ����</option>
						<option value="fast" {if $magic.request.biaoType=='fast'}selected{/if} >��Ѻ��</option>
						<option value="jin" {if $magic.request.biaoType=='jin'}selected{/if} >��ֵ��</option>
						<option value="miao" {if $magic.request.biaoType=='miao'}selected{/if} >�뻹��</option>
						<option value="xin" {if $magic.request.biaoType=='xin'}selected{/if} >���ñ�</option>
						<option value="lz" {if $magic.request.biaoType=='lz'}selected{/if} >��ת��</option>
					</select>
				״̬��<select id="status" ><option value="">ȫ��</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>�����б�..</option><option value="3" {if $magic.request.status==3} selected="selected"{/if}>������ɹ�</option><option value="5" {if $magic.request.status=="5"} selected="selected"{/if}>����δͨ��</option><option value="4" {if $magic.request.status=="4"} selected="selected"{/if}>���긴��ʧ��</option></select>
				����ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()" /> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()" />
				<input type="button" value="����" onclick="sousuo()" />
			</div>
		</div>
		</td>
		</tr>-->
		<tr>
			<td colspan="10" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table>

{elseif $_A.query_type=="full"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return submit_fool();" enctype="multipart/form-data" >
	<div class="module_title"><strong>ծȨת����Ϣ</strong></div>
	<div class="module_border">
		<div class="l">ԭʼ���꣺</div>
		<div class="c">
		<a href="/invest/a{$_A.zqzr_result.borrow_id}.html" target="_blank">{$_A.zqzr_result.borrow_name}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ˣ�</div>
		<div class="c">
		{$_A.zqzr_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����ծȨ��</div>
		<div class="c">
		{$_A.zqzr_result.y_account}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ۼ۸�</div>
		<div class="c">
		{$_A.zqzr_result.account}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">ÿ�ݼ۸�</div>
		<div class="c">
		{$_A.zqzr_result.every_account}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">������</div>
		<div class="c">
		{$_A.zqzr_result.account/$_A.zqzr_result.every_account}��
		</div>
	</div>
	<div class="module_border">
		<div class="l">ÿ�ݴ��գ�</div>
		<div class="c">
		{$_A.zqzr_result.every_collection}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ч�ڣ�</div>
		<div class="c">
		{$_A.zqzr_result.valid_time}
		</div>
	</div>
	<div class="module_border">
		<div class="l">ծȨʣ��ʱ�䣺</div>
		<div class="c">
		{$_A.zqzr_result.show_y_timelimit}
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ύʱ�䣺</div>
		<div class="c">
		{$_A.zqzr_result.addtime|date_format}
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			{if $_A.zqzr_result.status ==0}
			�ȴ�����
			{elseif $_A.zqzr_result.status ==2}
			����ʧ��
			{elseif $_A.zqzr_result.status ==4}
			����ʧ��
			{elseif $_A.zqzr_result.status ==5}
			�û�ȡ��
			{elseif $_A.zqzr_result.account>$_A.zqzr_result.account_yes}
			����ת����..
			{elseif $_A.zqzr_result.status ==3}
			����ͨ��
			{else}
			���������
			{/if}
		</div>
	</div>
	<div class="module_title"><strong>ծȨת�ø���</strong></div>
	{if $_A.zqzr_result.status==1}
	<div class="module_border">
		<div class="l">��������</div>
		<div class="c">
		<label><input type="radio" value="4" name="status" checked="checked" />��ͨ��</label>
		<label><input type="radio" value="3" name="status" />ͨ��</label>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ע��</div>
		<div class="c">
			<textarea style="width:300px;height:100px" name="success_remark"></textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c">
			<input type="hidden" value="{$_A.zqzr_result.id}" name="id" />
			<input type="submit" value="ȷ���ύ" />
		</div>
	</div>
	{/if}
	</form>
</div>
{/if}