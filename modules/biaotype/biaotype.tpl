{if $_A.query_type == "list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">识别码</td>
		<td width="" class="main_td">标种名称</td>
		<td width="" class="main_td">是否有效</td>
		<td width="" class="main_td">最小借款金额</td>
		<td width="" class="main_td">最大借款金额</td>
		<td width="" class="main_td">最小利率</td>
		<td width="" class="main_td">最大利率</td>
		<td width="" class="main_td">垫付时间</td>
		<td width="" class="main_td">逾期利率</td>
		<td width="" class="main_td">借款费率</td>
		<td width="" class="main_td">利息管理费</td>
		<td width="" class="main_td">借款冻结比例</td>
		<td width="" class="main_td">提现比例</td>
		<td width="" class="main_td">操作</td>
	</tr>
	<form action="" method="post">
	{foreach from=$_A.biao_type_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.id}</td>
		<td class="main_td1" align="center">{$item.biao_type_name}</td>
		<td class="main_td1" align="center">{$item.show_name}</td>
		<td class="main_td1" align="center">{if $item.available==1}有效{else}无效{/if}</td>
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
		<td class="main_td1" align="center"><a href="{$_A.query_url}/edit&type_id={$item.id}{$_A.site_url}">修改</a></td>
	</tr>
	{/foreach}
	</form>
</table>

{elseif $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	<form name="form_user" method="post" action="" onsubmit="submit_fool()">
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }编辑{else}添加{/if}</strong></div>
	<div class="module_border">
		<div class="l">标识别码：</div>
		<div class="c">
			{$_A.biao_type_result.biao_type_name }<input type="hidden" name="biao_type_name" value="{ $_A.biao_type_result.biao_type_name }" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">标种名称：</div>
		<div class="c">
			<input name="show_name" type="text" value="{ $_A.biao_type_result.show_name }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">是否有效：</div>
		<div class="c">
			<input type="checkbox" name="available" value="1" {if $_A.biao_type_result.available==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">支持定向标模式：</div>
		<div class="c">
			<input type="checkbox" name="password_model" value="1" {if $_A.biao_type_result.password_model==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">支持天标模式：</div>
		<div class="c">
			<input type="checkbox" name="day_model" value="1" {if $_A.biao_type_result.day_model==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">自动初审：</div>
		<div class="c">
			<input type="checkbox" name="auto_verify" value="1" {if $_A.biao_type_result.auto_verify==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">自动满标复审：</div>
		<div class="c">
			<input type="checkbox" name="auto_full_verify" value="1" {if $_A.biao_type_result.auto_full_verify==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">最小借款金额：</div>
		<div class="c">
			<input name="min_amount" type="text" value="{ $_A.biao_type_result.min_amount }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">最大借款金额：</div>
		<div class="c">
			<input name="max_amount" type="text" value="{ $_A.biao_type_result.max_amount }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">最小借款利率：</div>
		<div class="c">
			<input name="min_interest_rate" type="text" value="{ $_A.biao_type_result.min_interest_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">最大借款利率：</div>
		<div class="c">
			<input name="max_interest_rate" type="text" value="{ $_A.biao_type_result.max_interest_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">逾期垫付时间：</div>
		<div class="c">
			<input name="advance_time" type="text" value="{ $_A.biao_type_result.advance_time }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">逾期垫付范围：</div>
		<div class="c">
			<input name="advance_scope" type="radio" value="0"  {if $_A.biao_type_result.advance_scope==0} checked="checked"{/if}/><label for="">不垫付</label> 
			<input name="advance_scope" type="radio" value="1"  {if $_A.biao_type_result.advance_scope==1} checked="checked"{/if}/><label for="">垫付本金</label> 
			<input name="advance_scope" type="radio" value="2"  {if $_A.biao_type_result.advance_scope==2} checked="checked"{/if}/><label for="">垫付本息</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">逾期垫付范围（VIP）：</div>
		<div class="c">
			<input name="advance_vip_scope" type="radio" value="0"  {if $_A.biao_type_result.advance_vip_scope==0} checked="checked"{/if}/><label for="">不垫付</label> 
			<input name="advance_vip_scope" type="radio" value="1"  {if $_A.biao_type_result.advance_vip_scope==1} checked="checked"{/if}/><label for="">垫付本金</label> 
			<input name="advance_vip_scope" type="radio" value="2"  {if $_A.biao_type_result.advance_vip_scope==2} checked="checked"{/if}/><label for="">垫付本息</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">逾期垫付比例：</div>
		<div class="c">
			<input name="advance_rate" type="text" value="{ $_A.biao_type_result.advance_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">逾期垫付比例（VIP）：</div>
		<div class="c">
			<input name="advance_vip_rate" type="text" value="{ $_A.biao_type_result.advance_vip_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">逾期利率：</div>
		<div class="c">
			<input name="late_interest_rate" type="text" value="{ $_A.biao_type_result.late_interest_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">起始借款费率：</div>
		<div class="c">
			<input name="borrow_fee_rate_start" type="text" value="{ $_A.biao_type_result.borrow_fee_rate_start }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">起始借款费率包含月份数：</div>
		<div class="c">
			<input name="borrow_fee_rate_start_month_num" type="text" value="{ $_A.biao_type_result.borrow_fee_rate_start_month_num }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款费率：</div>
		<div class="c">
			<input name="borrow_fee_rate" type="text" value="{$_A.biao_type_result.borrow_fee_rate}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款费率上限：</div>
		<div class="c">
			<input name="borrow_fee_rate_max" type="text" value="{$_A.biao_type_result.borrow_fee_rate_max}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款费率（天标）：</div>
		<div class="c">
			<input name="borrow_day_fee_rate" type="text" value="{$_A.biao_type_result.borrow_day_fee_rate}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">利息管理费：</div>
		<div class="c">
			<input name="interest_fee_rate" type="text" value="{$_A.biao_type_result.interest_fee_rate}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">利息管理费(vip)：</div>
		<div class="c">
			<input name="interest_fee_rate_vip" type="text" value="{$_A.biao_type_result.interest_fee_rate_vip}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款冻结比例：</div>
		<div class="c">
			<input name="frost_rate" type="text" value="{ $_A.biao_type_result.frost_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款逾期投资人所得逾期利率：</div>
		<div class="c">
			<input name="late_customer_interest_rate" type="text" value="{ $_A.biao_type_result.late_customer_interest_rate }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">逾期利息基础：</div>
		<div class="c">
			<input name="late_interest_scope" type="radio" value="0"  {if $_A.biao_type_result.late_interest_scope==0} checked="checked"{/if}/><label for="">基于本金计算罚息</label> 
			<input name="late_interest_scope" type="radio" value="1"  {if $_A.biao_type_result.late_interest_scope==1} checked="checked"{/if}/><label for="">基于本息计算罚息</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">单个投资人最大投标次数：</div>
		<div class="c">
			<input name="max_tender_times" type="text" value="{ $_A.biao_type_result.max_tender_times }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">最大投标人数：</div>
		<div class="c">
			<input name="max_tender_number" type="text" value="{$_A.biao_type_result.max_tender_number}" class="input_border" />0表示没有限制
		</div>
	</div>
	<div class="module_border">
		<div class="l">需要vip才可发此标：</div>
		<div class="c">
			<input name="is_vip_borrow" type="radio" value="0"  {if $_A.biao_type_result.is_vip_borrow==0} checked="checked"{/if}/><label for="">否</label> 
			<input name="is_vip_borrow" type="radio" value="1"  {if $_A.biao_type_result.is_vip_borrow==1} checked="checked"{/if}/><label for="">是</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">需要vip才可投此标：</div>
		<div class="c">
			<input name="is_vip_render" type="radio" value="0"  {if $_A.biao_type_result.is_vip_render==0} checked="checked"{/if}/><label for="">否</label> 
			<input name="is_vip_render" type="radio" value="1"  {if $_A.biao_type_result.is_vip_render==1} checked="checked"{/if}/><label for="">是</label> 
		</div>
	</div>
	<div class="module_border">
		<div class="l">可提现比例：</div>
		<div class="c">
			<input name="extract_rate" type="text" value="{$_A.biao_type_result.extract_rate}" class="input_border" />0表示不可提现，1表示全部可提现
		</div>
	</div>
	<div class="module_border">
		<div class="l">风控意见：</div>
		<div class="c">
			当借款金额大于<input name="gt_money_committee" type="text" value="{$_A.biao_type_result.gt_money_committee}" class="input_border" />时需要风控中心的意见
		</div>
	</div>
	<div class="module_submit border_b" >
	{if $_A.query_type == "edit"}<input type="hidden" name="type_id" value="{ $_A.biao_type_result.id }" />{/if}
	<input type="submit" value="确认提交" />
	<input type="reset" name="reset" value="重置表单" />
	</div>
	</form>
</div>
{/if}