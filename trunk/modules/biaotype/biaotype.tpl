{if $_A.query_type == "list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">ʶ����</td>
		<td width="" class="main_td">��������</td>
		<td width="" class="main_td">�Ƿ���Ч</td>
		<td width="" class="main_td">��С�����</td>
		<td width="" class="main_td">�������</td>
		<td width="" class="main_td">��С����</td>
		<td width="" class="main_td">�������</td>
		<td width="" class="main_td">�渶ʱ��</td>
		<td width="" class="main_td">��������</td>
		<td width="" class="main_td">������</td>
		<td width="" class="main_td">��Ϣ�����</td>
		<td width="" class="main_td">�������</td>
		<td width="" class="main_td">���ֱ���</td>
		<td width="" class="main_td">����</td>
	</tr>
	<form action="" method="post">
	{foreach from=$_A.biao_type_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.id}</td>
		<td class="main_td1" align="center">{$item.biao_type_name}</td>
		<td class="main_td1" align="center">{$item.show_name}</td>
		<td class="main_td1" align="center">{if $item.available==1}��Ч{else}��Ч{/if}</td>
		<td class="main_td1" align="center">{$item.min_amount}</td>
		<td class="main_td1" align="center">{$item.max_amount}</td>
		<td class="main_td1" align="center">{$item.min_interest_rate}</td>
		<td class="main_td1" align="center">{$item.max_interest_rate}</td>
		<td class="main_td1" align="center">{$item.advance_time}</td>
		<td class="main_td1" align="center">{$item.late_interest_rate}</td>
		<td class="main_td1" align="center">{$item.borrow_fee_rate}</td>
		<td class="main_td1" align="center">{$item.interest_fee_rate}</td>
		<td class="main_td1" align="center">{$item.frost_rate}</td>
		<td class="main_td1" align="center">{$item.extract_rate}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/edit&type_id={$item.id}{$_A.site_url}">�޸�</a></td>
	</tr>
	{/foreach}
	</form>
</table>

{elseif $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	<form name="form_user" method="post" action="" onsubmit="submit_fool()">
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }�༭{else}���{/if}</strong></div>
	<div class="module_border">
		<div class="l">��ʶ���룺</div>
		<div class="c">
			{$_A.biao_type_result.biao_type_name }<input type="hidden" name="biao_type_name" value="{ $_A.biao_type_result.biao_type_name }" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�������ƣ�</div>
		<div class="c">
			<input name="show_name" type="text" value="{ $_A.biao_type_result.show_name }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�Ƿ���Ч��</div>
		<div class="c">
			<input type="checkbox" name="available" value="1" {if $_A.biao_type_result.available==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">֧�ֶ����ģʽ��</div>
		<div class="c">
			<input type="checkbox" name="password_model" value="1" {if $_A.biao_type_result.password_model==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">֧�����ģʽ��</div>
		<div class="c">
			<input type="checkbox" name="day_model" value="1" {if $_A.biao_type_result.day_model==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�Զ�����</div>
		<div class="c">
			<input type="checkbox" name="auto_verify" value="1" {if $_A.biao_type_result.auto_verify==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�Զ����긴��</div>
		<div class="c">
			<input type="checkbox" name="auto_full_verify" value="1" {if $_A.biao_type_result.auto_full_verify==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��С����</div>
		<div class="c">
			<input name="min_amount" type="text" value="{ $_A.biao_type_result.min_amount }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">������</div>
		<div class="c">
			<input name="max_amount" type="text" value="{ $_A.biao_type_result.max_amount }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��С������ʣ�</div>
		<div class="c">
			<input name="min_interest_rate" type="text" value="{ $_A.biao_type_result.min_interest_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��������ʣ�</div>
		<div class="c">
			<input name="max_interest_rate" type="text" value="{ $_A.biao_type_result.max_interest_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ڵ渶ʱ�䣺</div>
		<div class="c">
			<input name="advance_time" type="text" value="{ $_A.biao_type_result.advance_time }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ڵ渶��Χ��</div>
		<div class="c">
			<input name="advance_scope" type="radio" value="0"  {if $_A.biao_type_result.advance_scope==0} checked="checked"{/if}/><label for="">���渶</label> 
			<input name="advance_scope" type="radio" value="1"  {if $_A.biao_type_result.advance_scope==1} checked="checked"{/if}/><label for="">�渶����</label> 
			<input name="advance_scope" type="radio" value="2"  {if $_A.biao_type_result.advance_scope==2} checked="checked"{/if}/><label for="">�渶��Ϣ</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ڵ渶��Χ��VIP����</div>
		<div class="c">
			<input name="advance_vip_scope" type="radio" value="0"  {if $_A.biao_type_result.advance_vip_scope==0} checked="checked"{/if}/><label for="">���渶</label> 
			<input name="advance_vip_scope" type="radio" value="1"  {if $_A.biao_type_result.advance_vip_scope==1} checked="checked"{/if}/><label for="">�渶����</label> 
			<input name="advance_vip_scope" type="radio" value="2"  {if $_A.biao_type_result.advance_vip_scope==2} checked="checked"{/if}/><label for="">�渶��Ϣ</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ڵ渶������</div>
		<div class="c">
			<input name="advance_rate" type="text" value="{ $_A.biao_type_result.advance_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ڵ渶������VIP����</div>
		<div class="c">
			<input name="advance_vip_rate" type="text" value="{ $_A.biao_type_result.advance_vip_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�������ʣ�</div>
		<div class="c">
			<input name="late_interest_rate" type="text" value="{ $_A.biao_type_result.late_interest_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ʼ�����ʣ�</div>
		<div class="c">
			<input name="borrow_fee_rate_start" type="text" value="{ $_A.biao_type_result.borrow_fee_rate_start }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ʼ�����ʰ����·�����</div>
		<div class="c">
			<input name="borrow_fee_rate_start_month_num" type="text" value="{ $_A.biao_type_result.borrow_fee_rate_start_month_num }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ʣ�</div>
		<div class="c">
			<input name="borrow_fee_rate" type="text" value="{$_A.biao_type_result.borrow_fee_rate}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���������ޣ�</div>
		<div class="c">
			<input name="borrow_fee_rate_max" type="text" value="{$_A.biao_type_result.borrow_fee_rate_max}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ʣ���꣩��</div>
		<div class="c">
			<input name="borrow_day_fee_rate" type="text" value="{$_A.biao_type_result.borrow_day_fee_rate}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ϣ����ѣ�</div>
		<div class="c">
			<input name="interest_fee_rate" type="text" value="{$_A.biao_type_result.interest_fee_rate}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ϣ�����(vip)��</div>
		<div class="c">
			<input name="interest_fee_rate_vip" type="text" value="{$_A.biao_type_result.interest_fee_rate_vip}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���������</div>
		<div class="c">
			<input name="frost_rate" type="text" value="{ $_A.biao_type_result.frost_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�������Ͷ���������������ʣ�</div>
		<div class="c">
			<input name="late_customer_interest_rate" type="text" value="{ $_A.biao_type_result.late_customer_interest_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">������Ϣ������</div>
		<div class="c">
			<input name="late_interest_scope" type="radio" value="0"  {if $_A.biao_type_result.late_interest_scope==0} checked="checked"{/if}/><label for="">���ڱ�����㷣Ϣ</label> 
			<input name="late_interest_scope" type="radio" value="1"  {if $_A.biao_type_result.late_interest_scope==1} checked="checked"{/if}/><label for="">���ڱ�Ϣ���㷣Ϣ</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">����Ͷ�������Ͷ�������</div>
		<div class="c">
			<input name="max_tender_times" type="text" value="{ $_A.biao_type_result.max_tender_times }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���Ͷ��������</div>
		<div class="c">
			<input name="max_tender_number" type="text" value="{$_A.biao_type_result.max_tender_number}" class="input_border" />0��ʾû������
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ҫvip�ſɷ��˱꣺</div>
		<div class="c">
			<input name="is_vip_borrow" type="radio" value="0"  {if $_A.biao_type_result.is_vip_borrow==0} checked="checked"{/if}/><label for="">��</label> 
			<input name="is_vip_borrow" type="radio" value="1"  {if $_A.biao_type_result.is_vip_borrow==1} checked="checked"{/if}/><label for="">��</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ҫvip�ſ�Ͷ�˱꣺</div>
		<div class="c">
			<input name="is_vip_render" type="radio" value="0"  {if $_A.biao_type_result.is_vip_render==0} checked="checked"{/if}/><label for="">��</label> 
			<input name="is_vip_render" type="radio" value="1"  {if $_A.biao_type_result.is_vip_render==1} checked="checked"{/if}/><label for="">��</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ֱ�����</div>
		<div class="c">
			<input name="extract_rate" type="text" value="{$_A.biao_type_result.extract_rate}" class="input_border" />0��ʾ�������֣�1��ʾȫ��������
		</div>
	</div>
	<div class="module_border">
		<div class="l">��������</div>
		<div class="c">
			����������<input name="gt_money_committee" type="text" value="{$_A.biao_type_result.gt_money_committee}" class="input_border" />ʱ��Ҫ������ĵ����
		</div>
	</div>
	<div class="module_submit border_b" >
	{if $_A.query_type == "edit"}<input type="hidden" name="type_id" value="{ $_A.biao_type_result.id }" />{/if}
	<input type="submit" value="ȷ���ύ" />
	<input type="reset" name="reset" value="���ñ�" />
	</div>
	</form>
</div>
{/if}