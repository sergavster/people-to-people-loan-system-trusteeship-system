{if $_A.query_type == "list"}
<table  border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">ʶ����</td>
		<td width="" class="main_td">��Ա����</td>
		<td width="" class="main_td">�Ƿ���Ч</td>
		<td width="" class="main_td">�����ѱ���</td>
		<td width="" class="main_td">������ַ���</td>
		<td width="" class="main_td">������߽��</td>
		<td width="" class="main_td">������ͽ��</td>
		<td width="" class="main_td">ÿ������ۼƽ��</td>
		<!--<td width="" class="main_td">����ʱ��</td>
		<td width="" class="main_td">����������С���</td>
		<td width="" class="main_td">�������ַ�</td>-->
		<td width="" class="main_td">����</td>
	</tr>
	<form action="" method="post">
	{foreach from=$_A.cash_rule key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.id}</td>
		<td class="main_td1" align="center">{$item.nid}</td>
		<td class="main_td1" align="center">{$item.name}</td>
		<td class="main_td1" align="center">{if $item.status==1}��Ч{else}��Ч{/if}</td>
		<td class="main_td1" align="center">{$item.cash_scale}</td>
		<td class="main_td1" align="center">{$item.min_scale}Ԫ</td>
		<td class="main_td1" align="center">{$item.max_money}Ԫ</td>
		<td class="main_td1" align="center">{$item.min_money}Ԫ</td>
		<td class="main_td1" align="center">{$item.max_day_money}Ԫ</td>
		<!--<td class="main_td1" align="center">{$item.arrival_time}Сʱ</td>
		<td class="main_td1" align="center">{$item.fast_min_money}Ԫ</td>
		<td class="main_td1" align="center">{$item.fast_cash_scale}</td>-->
		<td class="main_td1" align="center"><a href="{$_A.query_url}/edit&id={$item.id}{$_A.site_url}">�޸�</a></td>
	</tr>
	{/foreach}
	</form>
</table>

{elseif $_A.query_type == "edit" || $_A.query_type == "new"}
<div class="module_add">
	<form name="form_user" method="post" action="">
	<div class="module_title"><strong>{if $_A.query_type == "edit"}�༭{else}���{/if}</strong></div>
	<div class="module_border">
		<div class="l">ʶ���룺</div>
		<div class="c">
			{if $_A.query_type == "edit"}
			{$_A.cash_rule.nid}<input type="hidden" name="nid" value="{$_A.cash_rule.nid}" />
			{else}
			<input type="text" name="nid" />
			{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ա���</div>
		<div class="c">
			<input name="name" type="text" value="{$_A.cash_rule.name }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�Ƿ���Ч��</div>
		<div class="c">
			<input type="checkbox" name="status" value="1" {if $_A.cash_rule.status==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ѱ�����</div>
		<div class="c">
			<input name="cash_scale" type="text" value="{$_A.cash_rule.cash_scale}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">������ַ��ã�</div>
		<div class="c">
			<input name="min_scale" type="text" value="{$_A.cash_rule.min_scale}" class="input_border" />Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">������߽�</div>
		<div class="c">
			<input name="max_money" type="text" value="{$_A.cash_rule.max_money}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">������ͽ�</div>
		<div class="c">
			<input name="min_money" type="text" value="{$_A.cash_rule.min_money}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">ÿ������ۼƽ�</div>
		<div class="c">
			<input name="max_day_money" type="text" value="{$_A.cash_rule.max_day_money}" class="input_border" />
		</div>
	</div>
    <!--
	<div class="module_border">
		<div class="l">����ʱ�䣺</div>
		<div class="c">
			<input name="arrival_time" type="text" value="{$_A.cash_rule.arrival_time}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">����������С��</div>
		<div class="c">
			<input name="fast_min_money" type="text" value="{$_A.cash_rule.fast_min_money}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�������ַѣ�</div>
		<div class="c">
			<input name="fast_cash_scale" type="text" value="{$_A.cash_rule.fast_cash_scale}" class="input_border" />
		</div>
	</div>-->
	<div class="module_submit border_b" >
	{if $_A.query_type == "edit"}<input type="hidden" name="id" value="{$_A.cash_rule.id }" />{/if}
	<input type="submit" value="ȷ���ύ" />
	<input type="reset" name="reset" value="���ñ�" />
	</div>
	</form>
</div>
{/if}