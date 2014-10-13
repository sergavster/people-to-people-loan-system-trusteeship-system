{if $_A.query_type == "new"}
<div class="module_add">
	{if $magic.request.user_id==""}
	<form name="form1" method="post" action="" enctype="multipart/form-data">
	<div class="module_title"><strong>请输入此信息的用户名或ID</strong></div>
	<div class="module_border">
		<div class="l">用户ID：</div>
		<div class="c">
			<input type="text" name="user_id"  class="input_border"  size="20" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			<input type="text" name="username"  class="input_border"  size="20" />
		</div>
	</div>
	<div class="module_submit" >
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
	</form>
	{else}
	<div class="module_title"><strong>添加借款信息</strong></div>
	<form name="form1" method="post" action=""  enctype="multipart/form-data" onsubmit="return check_form();" >
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			{$_A.user_result.username|default:$_A.borrow_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">标种：</div>
		<div class="c">
		<select name="biao_type">
		{foreach from="$_G.biao_type" item="item"}
		{if $item.available==1}
		<option value="{$item.biao_type_name}">{$item.show_name}</option>
		{/if}
		{/foreach}
		</select>
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款用途：</div>
		<div class="c">
		{linkages nid="borrow_use" value="$_A.borrow_result.use" name="use"  }
			 <span></span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款期限：</div>
		<div class="c">
			<input type="radio" name="isday" value="0" checked="checked">月<input type="radio" name="isday" value="1">天
		</div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c">
			<div id="time_limit">
			<select name="time_limit" id="time_limit">  <option value="1">1个月</option>  <option value="3">3个月</option>  <option value="6">6个月</option>  <option value="12">12个月</option>  <option value="24">24个月</option>  <option value="36">36个月</option></select>
			</div>
			<div id="time_limit_day" style="display:none">
			<input type="text" value="" name="time_limit_day">天
			</div>
		</div>
	</div>
	<div class="module_border">
		<div class="l">还款方式：</div>
		<div class="c">
			{linkages nid="borrow_style" value="$_A.borrow_result.style" name="style" type="value" value="0"}
		<span ></span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">借贷总金额：</div>
		<div class="c"><input type="text" name="account" value="{$_A.borrow_result.account}" />
<span ></span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">年利率：</div>
		<div class="c">
			<input type="text" name="apr" value="{$_A.borrow_result.apr}" /> % <span ></span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">最低投标金额：</div>
		<div class="c">
			{linkages nid="borrow_lowest_account" value="$_A.borrow_result.lowest_account" name="lowest_account" type="value" }
		<span></span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">最多投标总额：</div>
		<div class="c">
			{linkages nid="borrow_most_account" value="$_A.borrow_result.most_account" name="most_account" type="value" }
			<span ></span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">有效时间：</div>
		<div class="c">
			{linkages nid="borrow_valid_time" value="$_A.borrow_result.valid_time" name="valid_time" type="value" }
			 <span></span>
		</div>
	</div>
	<div class="module_title"><strong>设置奖励</strong></div>
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="0" {if $_A.borrow_result.award==0 || $_A.borrow_result.award==""} checked="checked"{/if}>不设置奖励</div>
		<div class="c">
			 <span>如果您设置了奖励金额，将会冻结您帐户中相应的账户余额。如果要设置奖励，请确保您的帐户有足够 的账户余额。 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="1" {if $_A.borrow_result.award==1 } checked="checked"{/if}/>按固定金额分摊奖励：</div>
		<div class="c">
			<input type="text" name="part_account" value="{$_A.borrow_result.part_account}" size="5" /> 元 <span>保留到“元”为单位。这里设置本次标的要奖励给所有投标用户的总金额。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="2" {if $_A.borrow_result.award==2 } checked="checked"{/if}/>按投标金额比例奖励：</div>
		<div class="c">
			<input type="text" name="funds" value="{$_A.borrow_result.funds}" size="5" /> %  <span>这里设置本次标的要奖励给所有投标用户的奖励比例。  </span>
		</div>
	</div>
	<div class="module_title"><strong>详细信息</strong></div>

	<div class="module_border">
		<div class="l">标题：</div>
		<div class="c">
			<input type="text" name="name" value="{$_A.borrow_result.name}" size="50" /> 
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">信息：</div>
		<div class="c" style="width:70%">
			<script charset="gb2312" src="/plugins/editor/kindeditor/kindeditor-min.js"></script>
			<script charset="gb2312" src="/plugins/editor/kindeditor/lang/zh_CN.js"></script>
			<textarea name="content" id="content"  style="width:100%;height:400px;visibility:hidden;" >
		            {$_A.borrow_result.content}
			</textarea>
		</div>
	</div>
	<!--基本资料 结束-->

	<div class="module_submit" >
	{if $_A.query_type == "edit"}<input type="hidden"  name="id" value="{$magic.request.id}" />{/if}
		<input type="hidden" name="status" value="{$_A.borrow_result.status }" />
		<input type="hidden" name="user_id" value="{$magic.request.user_id}" />
		<input type="submit" name="submit" value="确认提交" />
		<input type="reset" name="reset" value="重置表单" />
	</div>
	</form>
	{/if}
</div>
{literal}
<script>

var editor;
KindEditor.ready(function(K) {
	editor = K.create('textarea[name="content"]', {
		allowFileManager : true
	});
});

$("input[name='isday']").click(function(){
	var a = $(this).val();
	if(a==1){
		$("#time_limit").hide();
		$("#time_limit_day").show();
	}else{
		$("#time_limit").show();
		$("#time_limit_day").hide();
	}
})
function check_form(){
	 var frm = document.forms['form1'];
	 var name = frm.elements['name'].value;
	 var account = frm.elements['account'].value;
	 var apr = frm.elements['apr'].value;
	 var errorMsg = '';
	  if (name.length == 0 ) {
		errorMsg += '标题必须填写' + '\n';
	  }
	  if(!/^[1-9]+\.?\d+$/.test(account)){
		errorMsg += '借款金额有误' + '\n';
	  }
	  if(!/^[1-9]+\.?\d+$/.test(account)){
		errorMsg += '借款利率有误' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{
		submit_fool();
		return true;
	  }
}

</script>
{/literal}

<!-- 修改 开始 -->
{elseif $_A.query_type == "edit"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return submit_fool();" enctype="multipart/form-data" >
	<div class="module_title"><strong>审核借款标</strong></div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
		<a href="javascript:void(0)" onclick='tipsWindown("用户详细信息查看","url:get?{$_A.admin_url}&q=module/user/view&user_id={$_A.borrow_result.user_id}&type=scene",500,230,"true","","true","text");'>{$_A.user_result.username|default:$_A.borrow_result.username}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			{if $_A.borrow_result.status==0}发布审批中{elseif $_A.borrow_result.status==1}正在募集{elseif $_A.borrow_result.status==2}审核失败{elseif $_A.borrow_result.status==3}已满标{elseif $_A.borrow_result.status==4}满标审核失败{elseif $_A.borrow_result.status==5}撤回{/if}
		</div>
	</div>
	{if $_A.borrow_result.biao_type=="vouch"}
	<div class="module_border">
		<div class="l">担保人用户名：</div>
		<div class="c">
		{foreach from=$_A.borrow_result.vouch_user item=vouch_user}
		<a href="/{$_A.admin_url}&q=module/userinfo/vouch_userinfo&vouch_userid={$vouch_user.user_id}" class="thickbox">{$vouch_user.username}</a>&nbsp;&nbsp;
		{/foreach}
		</div>
	</div>
	{/if}
	<div class="module_border">
		<div class="l">借款用途：</div>
		<div class="c">
			{$_A.borrow_result.use|linkage:"borrow_use"}
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款期限：</div>
		<div class="c">
		{if $_A.borrow_result.isday==1 } 
                {$_A.borrow_result.time_limit_day}天
                {else}
                {$_A.borrow_result.time_limit}个月
                {/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">还款方式：</div>
		<div class="c">
			{if $_A.borrow_result.isday==1 } 
                到期全额还款
            {else}
                {$_A.borrow_result.style|linkage:"borrow_style"}
            {/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">借贷总金额：</div>
		<div class="c">
			{$_A.borrow_result.account}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">年利率：</div>
		<div class="c">
			{$_A.borrow_result.apr} %
		</div>
	</div>
	<div class="module_border">
		<div class="l">最低投标金额：</div>
		<div class="c">
			{$_A.borrow_result.lowest_account}
		</div>
	</div>
	<div class="module_border">
		<div class="l">最多投标总额：</div>
		<div class="c">
			{if $_A.borrow_result.most_account==0}没有限制{else}{$_A.borrow_result.most_account}{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">有效时间：</div>
		<div class="c">
			{$_A.borrow_result.valid_time|linkage:"borrow_valid_time"}
		</div>
	</div>
	<div class="module_title"><strong>设置奖励</strong></div>
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="0" {if $_A.borrow_result.award==0 || $_A.borrow_result.award==""} checked="checked"{/if}>不设置奖励</div>
		<div class="c">
			 <span>如果您设置了奖励金额，将会冻结您帐户中相应的账户余额。如果要设置奖励，请确保您的帐户有足够 的账户余额。 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="1" {if $_A.borrow_result.award==1 } checked="checked"{/if}/>按固定金额分摊奖励：</div>
		<div class="c">
			<input type="text" name="part_account" value="{$_A.borrow_result.part_account}" size="5" /> 元 <span>保留到“元”为单位。这里设置本次标的要奖励给所有投标用户的总金额。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="2" {if $_A.borrow_result.award==2 } checked="checked"{/if}/>按投标金额比例奖励：</div>
		<div class="c">
			<input type="text" name="funds" value="{$_A.borrow_result.funds}" size="5" /> %  <span>这里设置本次标的要奖励给所有投标用户的奖励比例。  </span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">标题：</div>
		<div class="c">
			<input type="text" name="title" value="{$_A.borrow_result.name}" size="100" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">项目描述：</div>
		<div class="c" style="width:70%">
			<script charset="gb2312" src="/plugins/editor/kindeditor/kindeditor-min.js"></script>
			<script charset="gb2312" src="/plugins/editor/kindeditor/lang/zh_CN.js"></script>
			<textarea name="content" id="content"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.content}
			</textarea>
		</div>
	</div>
	<!--
	<div class="module_border">
		<div class="l">资金运转：</div>
		<div class="c" style="width:70%">
			<textarea name="zjyz" id="zjyz"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.zjyz}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">风险控制措施：</div>
		<div class="c" style="width:70%">
			<textarea name="fxkzcs" id="fxkzcs"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.fxkzcs}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">企业背景：</div>
		<div class="c" style="width:70%">
			<textarea name="qybj" id="qybj"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.qybj}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">企业信息：</div>
		<div class="c" style="width:70%">
			<textarea name="qyxx" id="qyxx"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.qyxx}
			</textarea>
		</div>
	</div>-->
	{literal}
	<script>
	var editor;
	var editor1;
	var editor2;
	var editor3;
	var editor4;
	var editor5;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true
		});
	});
/*
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="zjyz"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor2 = K.create('textarea[name="fxkzcs"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor4 = K.create('textarea[name="qybj"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor5 = K.create('textarea[name="qyxx"]', {
			allowFileManager : true
		});
	});*/
	</script>
	{/literal}
	<div class="module_title"><strong>相关项目资料</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=xgxmzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>

	<div class="module_title"><strong>抵押相关资料</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=dyzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>
	<div><input type="hidden" value="{$magic.get.id}" name="borrow_id"><input type="submit" name="submit" value="保存更改"></div>
	</form>
</div>
<!-- 初审 结束 -->

<!-- 初审 开始 -->
{elseif $_A.query_type == "view"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return submit_fool();" enctype="multipart/form-data" >
	<div class="module_title"><strong>审核借款标</strong></div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
		<a href="javascript:void(0)" onclick='tipsWindown("用户详细信息查看","url:get?{$_A.admin_url}&q=module/user/view&user_id={$_A.borrow_result.user_id}&type=scene",500,230,"true","","true","text");'>	{$_A.user_result.username|default:$_A.borrow_result.username}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			{if $_A.borrow_result.status==0}发布审批中{elseif $_A.borrow_result.status==1}正在募集{elseif $_A.borrow_result.status==2}审核失败{elseif $_A.borrow_result.status==3}已满标{elseif $_A.borrow_result.status==4}满标审核失败{elseif $_A.borrow_result.status==5}撤回{/if}
		</div>
	</div>
	{if $_A.borrow_result.biao_type=="vouch"}
	<div class="module_border">
		<div class="l">担保人用户名：</div>
		<div class="c">
		{foreach from=$_A.borrow_result.vouch_user item=vouch_user}
		<a href="/{$_A.admin_url}&q=module/userinfo/vouch_userinfo&vouch_userid={$vouch_user.user_id}" class="thickbox">{$vouch_user.username}</a>&nbsp;&nbsp;
		{/foreach}
		</div>
	</div>
	{/if}
	<div class="module_border">
		<div class="l">借款用途：</div>
		<div class="c">
			{$_A.borrow_result.use|linkage:"borrow_use"}
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款期限：</div>
		<div class="c">
		{if $_A.borrow_result.isday==1 } 
                {$_A.borrow_result.time_limit_day}天
                {else}
                {$_A.borrow_result.time_limit}个月
                {/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">还款方式：</div>
		<div class="c">
			{if $_A.borrow_result.isday==1 } 
                到期全额还款
            {else}
                {$_A.borrow_result.style|linkage:"borrow_style"}
            {/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">借贷总金额：</div>
		<div class="c">
			{$_A.borrow_result.account}<input type="hidden" name="account" value="{$_A.borrow_result.account}" /> 元
		</div>
	</div>
	<div class="module_border">
		<div class="l">年利率：</div>
		<div class="c">
			{$_A.borrow_result.apr} %
		</div>
	</div>
	<div class="module_border">
		<div class="l">最低投标金额：</div>
		<div class="c">
			{$_A.borrow_result.lowest_account}
		</div>
	</div>
	<div class="module_border">
		<div class="l">最多投标总额：</div>
		<div class="c">
			{if $_A.borrow_result.most_account==0}没有限制{else}{$_A.borrow_result.most_account}{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">有效时间：</div>
		<div class="c">
			{$_A.borrow_result.valid_time|linkage:"borrow_valid_time"}
		</div>
	</div>
	<div class="module_title"><strong>设置奖励</strong></div>
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="0" {if $_A.borrow_result.award==0 || $_A.borrow_result.award==""} checked="checked"{/if}>不设置奖励</div>
		<div class="c">
			 <span>如果您设置了奖励金额，将会冻结您帐户中相应的账户余额。如果要设置奖励，请确保您的帐户有足够 的账户余额。 </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="1" {if $_A.borrow_result.award==1 } checked="checked"{/if}/>按固定金额分摊奖励：</div>
		<div class="c">
			<input type="text" name="part_account" value="{$_A.borrow_result.part_account}" size="5" /> 元 <span>保留到“元”为单位。这里设置本次标的要奖励给所有投标用户的总金额。  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="2" {if $_A.borrow_result.award==2 } checked="checked"{/if}/>按投标金额比例奖励：</div>
		<div class="c">
			<input type="text" name="funds" value="{$_A.borrow_result.funds}" size="5" /> %  <span>这里设置本次标的要奖励给所有投标用户的奖励比例。  </span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">标题：</div>
		<div class="c">
			<input type="text" name="title" value="{$_A.borrow_result.name}" size="100" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款说明：</div>
		<div class="c" style="width:70%">
			<script charset="gb2312" src="/plugins/editor/kindeditor/kindeditor-min.js"></script>
			<script charset="gb2312" src="/plugins/editor/kindeditor/lang/zh_CN.js"></script>
			<textarea name="content" id="content"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.content}
			</textarea>
		</div>
	</div>
	<!--
	<div class="module_border">
		<div class="l">资金运转：</div>
		<div class="c" style="width:70%">
			<textarea name="zjyz" id="zjyz"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.zjyz}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">风险控制措施：</div>
		<div class="c" style="width:70%">
			<textarea name="fxkzcs" id="fxkzcs"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.fxkzcs}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">政策及市场分析：</div>
		<div class="c" style="width:70%">
			<textarea name="zcjscfx" id="zcjscfx"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.zcjscfx}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">企业背景：</div>
		<div class="c" style="width:70%">
			<textarea name="qybj" id="qybj"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.qybj}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">企业信息：</div>
		<div class="c" style="width:70%">
			<textarea name="qyxx" id="qyxx"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.qyxx}
			</textarea>
		</div>
	</div>-->
	{literal}
	<script>
	var editor;
	//var editor1;
	//var editor2;
	//var editor3;
	//var editor4;
	//var editor5;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true
		});
	});
	/*
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="zjyz"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor2 = K.create('textarea[name="fxkzcs"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor3 = K.create('textarea[name="zcjscfx"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor4 = K.create('textarea[name="qybj"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor5 = K.create('textarea[name="qyxx"]', {
			allowFileManager : true
		});
	});*/
	</script>
	{/literal}
	
	{if $_A.borrow_result.status!=0}
	<div class="module_title"><strong>审核状态</strong></div>
	<div class="module_border" >
		<div class="l">审核时间：</div>
		<div class="c">
			{$_A.borrow_result.verify_time|date_format:"Y-m-d H:i"}
		</div>
	</div>
	<div class="module_border">
		<div class="l">审核人：</div>
		<div class="c">
			{$_A.borrow_result.verify_username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">审核备注：</div>
		<div class="c">
			{$_A.borrow_result.verify_remark}
		</div>
	</div>
	{/if}
	<div class="module_title"><strong>用户上传的其他资料</strong></div>
	<div>
		{if $_A.borrow_shus_result==""}
		<div style="text-align:center;margin:10px">用户未上传任何对该借款标的补充资料</div>
		{else}
		<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%" algin="center">
		<tr height="30"><td class="main_td">资料名</td><td class="main_td">上传时间</td><td class="main_td">操作</td></tr>
		{foreach from=$_A.borrow_shus_result item=item key=key}
		<tr {if $key%2==1}class="tr"{/if}>
		<td>{$item.name}</td>
		<td>{$item.addtime|date_format:Y-m-d H:i:s}</td>
		<td><a href="{$item.litpic}" target="_blank">查看</a></td>
		</tr>
		{/foreach}
		</table>
		{/if}
	</div>
	<!--
	<div class="module_title"><strong>{if $_A.borrow_result.status==1}该标公开的证件{else}选择该标公开的证件{/if}</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<input type="hidden" name="show_attestation" id="show_attestation" show="{$_A.borrow_result.show_attestation}" value="">
		<input type="hidden" id="iframepath" value="/{$_A.query_url}/borrow_user_attestation&user_id={$_A.borrow_result.user_id}" />
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_user_attestation&user_id={$_A.borrow_result.user_id}{if $_A.borrow_result.status!=0}&borrow_status=1&attestation_id={$_A.borrow_result.show_attestation}{/if}"></iframe>
	</div>
	<div style="margin:10px 0 10px 10px">
	<a href='/{$_A.query_url}/user_attestation&user_type={$_A.borrow_result.user_type}&user_id={$_A.borrow_result.user_id}&width=800&height=600' class="thickbox" title='{$_A.borrow_result.username}的证明材料'>点击查看用户上传的资料</a>
	</div>
	-->
	<div class="module_title"><strong>基本资料</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=xgxmzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>
	<div class="module_title"><strong>资产信息</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=dyzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>
	<div class="module_title"><strong>还款保障</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=bxbzzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>
	<div style="margin:10px 0 10px 10px">
	<a href='/{$_A.query_url}/user_attestation&user_type={$_A.borrow_result.user_type}&user_id={$_A.borrow_result.user_id}&width=800&height=600' class="thickbox" title='{$_A.borrow_result.username}的证明材料'>点击查看用户上传的资料</a>
	</div>
	{if $_A.borrow_result.status==0}
	<div class="module_title"><strong>审核此借款</strong></div>
	
	<div class="module_border">
		<div class="l">信用等级:</div>
		<div class="c">
			<select name="credit_grade">
			<option value="2">A</option><option value="3">B</option>
			<option value="4">C</option><option value="5">D</option><option value="6">E</option>
			<option value="7">HR</option>
			</select>
		</div>
	</div>
	<!--
	<div class="module_border">
		<div class="l">标的类型:</div>
		<div class="c">
			<select name="borrow_rzlx">
			<option value="xyrz">信用认证标</option>
			<option value="sdrz">实地认证标</option>
			<option value="jgdb">机构担保标</option>
			<option value="znlc">智能理财标</option>
			</select>
		</div>
	</div>
	-->
	<div class="module_border">
		<div class="l">初审意见:</div>
		<div class="c">
			{if $_A.check_rank.centre_remark==1}
				{if $_A.borrow_result.centre_remark==""}
				<textarea name="centre_remark" cols="45" rows="5">{$_A.borrow_result.centre_remark}</textarea>
				{else}
				{$_A.borrow_result.centre_remark}
				{/if}
			{else}
				{$_A.borrow_result.centre_remark|default:暂无给出意见}
			{/if}
		</div>
	</div>
	<!-- 判断是否需要风险委员会意见 -->
	{if $_A.is_committee_remark==1}
	<div class="module_border" >
		<div class="l">风险委员会意见:</div>
		<div class="c">
			{if $_A.check_rank.committee_remark==1}
				{if $_A.borrow_result.committee_remark=="" && $_A.borrow_result.centre_remark!=""}
				<textarea name="committee_remark" cols="45" rows="5">{$_A.borrow_result.committee_remark}</textarea>
				{elseif $_A.borrow_result.centre_remark==""}
				<span style="color:red">等待初审后方可填写</span>
				{else}
				{$_A.borrow_result.committee_remark}
				{/if}
			{else}
				{$_A.borrow_result.committee_remark|default:暂无给出意见}
			{/if}
		</div>
	</div>
	{/if}
	{if $_A.check_rank.verify_remark==1}
	<div class="module_border">
		<div class="l">状态:</div>
		<div class="c">
		<input type="radio" name="status" value="1"/>审核通过 <input type="radio" name="status" value="2" />审核不通过<input type="radio" name="status" value="0"  checked="checked"/>审核中</div>
	</div>
	<div class="module_border" >
		<div class="l">综合意见:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5">{$_A.borrow_result.verify_remark}</textarea>
		</div>
	</div>
	{else}
	<div class="module_border">
		<div class="l">状态:</div>
		<div class="c">
		<input type="radio" disabled="disabled" {if $_A.borrow_result.status==1}checked="checked"{/if} />审核通过 <input type="radio" disabled="disabled" {if $_A.borrow_result.status==2}checked="checked"{/if} />审核不通过<input type="radio" disabled="disabled"  {if $_A.borrow_result.status==0}checked="checked"{/if} />审核中</div>
	</div>
	<div class="module_border" >
		<div class="l">综合意见:</div>
		<div class="c">
			{$_A.borrow_result.verify_remark|default:暂无给出意见}
		</div>
	</div>
	{/if}
	<div class="module_submit" >
		<input type="hidden" name="id" value="{$_A.borrow_result.id}" />
		<input type="hidden" name="user_id" value="{$_A.borrow_result.user_id}" />
		<input type="hidden" name="name" value="{$_A.borrow_result.name}" />
		{if $_A.check_rank.verify_remark==1}
			{if $_A.is_committee_remark==1 && $_A.borrow_result.committee_remark=="" && $_A.borrow_result.centre_remark!="" && $_A.check_rank.committee_remark!=1}
				<input type="button" disabled="disabled" value="等待风控中心的意见" />
			{else $_A.check_rank.committee_remark==1}
				<input type="submit" name="reset" value="审核此借款标" />
			{/if}
		{else}
			{if ($_A.check_rank.centre_remark==1 && $_A.borrow_result.centre_remark=="") || ($_A.is_committee_remark==1 && $_A.check_rank.committee_remark==1 && $_A.borrow_result.committee_remark=="") || ($_A.check_rank.verify_remark==1 && $_A.borrow_result.verify_remark=="")}
			<input type="submit" name="reset" value="提交意见" />
			{/if}
		{/if}
	</div>
	{/if}
	</form>
</div>
<!-- 初审 结束 -->

<!-- 所有借款 开始 -->
{elseif $_A.query_type=="list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">借款编号</td>
			<td width="" class="main_td">用户名</td>
 			<td width="" class="main_td">借款标题</td>
			<td width="" class="main_td">借款金额</td>
			<td width="" class="main_td">利率</td>
			<td width="" class="main_td">借款期限</td>
			<td width="" class="main_td">发布时间</td>
			<td width="" class="main_td">状态</td>
			{if $magic.get.status==3}<td width="" class="main_td">风险金扣除情况</td>{else}
			<td width="" class="main_td">操作</td>{/if}
		</tr>
		{foreach  from=$_A.borrow_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}<input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td class="main_td1" ><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene" class="thickbox" title="用户详细信息查看">	{$item.username}</a></td>
 			<td title="{$item.name}"  align="left">
			<span style="color:#FF0000">【{$item.show_name}】</span>
			<a href="/invest/a{$item.id}.html" target="_blank">{$item.name|truncate:10}</a>
			</td>
			<td>{$item.account}元</td>
			<td>{$item.apr}%</td>
			<td>{if $item.isday ==1}{$item.time_limit_day}天{ else}{$item.time_limit}个月{/if}</td>
			<td>{$item.addtime|date_format}</td>
			<td>
				{if $item.status ==1}
				{if $item.biao_type=='lz' && $item.is_liubiao<1}
				已停止流转
				{else}
				{if $item.account>$item.account_yes}
				正在招标..
				{else}
				满标审核中
				{/if}
				{/if}
			{elseif $item.status ==0}等待初审
			{elseif $item.status ==-1}<font color="#999999">尚未发布</font>
			{elseif $item.status ==3}满标借款成功
			{elseif $item.status ==4}复审未通过
			{elseif $item.status==5}被撤回
			{else}初审未通过{/if}
			
			</td>
			{if $magic.get.status==3}<td>{if $item.risk_fee==-1}未扣除{else}已扣除{$item.risk_fee}元{/if}&nbsp;&nbsp;&nbsp;<a href="{$_A.admin_url}&q=module/account/tgviewcash&view=2&order_id={$item.order_id}&a=cash">[查看]</a></td>{/if}
			<td>
				{if $item.status ==0}<a href="{$_A.query_url}/view{$_A.site_url}&user_id={$item.user_id}&id={$item.id}">初审</a>{/if}
				{if $item.status==1 && $item.biao_type!='lz'}
					{if $item.account>$item.account_yes}
					<a href="{$_A.query_url}/full_view{$_A.site_url}&id={$item.id}">撤回</a>
					{else}
					{if $item.status!=3}<a href="{$_A.query_url}/full_view{$_A.site_url}&user_id={$item.user_id}&id={$item.id}">满标审核</a>{/if}
					{/if}
				{/if}
				{if $item.biao_type=='lz' && $item.is_liubiao>0}
				<a href="javascript:if(confirm('确定要停止流转此标么')) location.href='{$_A.query_url}/stoplz{$_A.site_url}&id={$item.id}'">停止流转</a>
				{/if}
			</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="{if $magic.get.status==3}10{else}9{/if}" >
		<div class="action">
			<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="导出当前列表" />
			</div>
			<div class="floatr">
				用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> 
				标类型：<select name="biaoType" id="biaoType">
						<option value=""> 所有</option>
						<option value="fast" {if $magic.request.biaoType=='fast'}selected{/if} >抵押标</option>
						<option value="jin" {if $magic.request.biaoType=='jin'}selected{/if} >净值标</option>
						<option value="miao" {if $magic.request.biaoType=='miao'}selected{/if} >秒还标</option>
						<option value="xin" {if $magic.request.biaoType=='xin'}selected{/if} >信用标</option>
						<option value="lz" {if $magic.request.biaoType=='lz'}selected{/if} >流转标</option>
					</select>
				状态：<select id="status" ><option value="">全部</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>正在招标..</option><option value="3" {if $magic.request.status==3} selected="selected"{/if}>满标借款成功</option><option value="5" {if $magic.request.status=="5"} selected="selected"{/if}>初审未通过</option><option value="4" {if $magic.request.status=="4"} selected="selected"{/if}>满标复审失败</option></select>
				发布时间：<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()" /> 到 <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()" />
				<input type="button" value="搜索" onclick="sousuo()" />
			</div>
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
<!-- 所有借款 结束 -->

<!-- 满标列表 开始 -->
{elseif $_A.query_type=="full"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">借款编号</td>
			<td width="" class="main_td" >用户名</td>
			<td width="" class="main_td" >借款标题</td>
			<td width="" class="main_td" >借款金额</td>
			<td width="" class="main_td" >应付利息</td>
			<td width="" class="main_td" >年利率</td>
			<td width="" class="main_td" >投标次数</td>
			<td width="" class="main_td" >借款期限</td>
			<td width="" class="main_td" >发标时间</td>
			<td width="" class="main_td" >初审时间</td>
			<td width="" class="main_td" >状态</td>
			<td width="" class="main_td" >操作</td>
		</tr>
		{foreach  from=$_A.borrow_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td >{$item.id}</td>
			<td class="main_td1"  ><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="用户详细信息查看">	{$item.username}</a></td>
			<!--<td title="{$item.name}">{$item.name|truncate:10}</td>-->
            <td title="{$item.name}" align="left">
			<span style="color:#FF0000">【{$item.show_name}】</span>
				<a href="/invest/a{$item.id}.html" target="_blank">{$item.name|truncate:10}</a>
			</td>
			<td>{$item.account}元</td>
			<td>
				{if $item.status==3}
                <script type="text/javascript">
                var tempInterest = ({$item.repayment_account|default:0}-{$item.account|default:0});
					document.write(tempInterest.toFixed(2));
                </script>
                {else}--{/if}
			</td>
			<td >{$item.apr}%</td>
			<td >{$item.tender_times|default:0}</td>
			<td >{if $item.isday==1 } 
                {$item.time_limit_day}天
                {else}
                {$item.time_limit}个月
                {/if}
            </td>
			<td>{$item.addtime|date_format}</td>
			<td>{$item.verify_time|date_format}</td>
			<td>{if $item.status ==1} 
				{if $item.account>$item.account_yes}正在招标..{else}满标审核中{/if}
			{elseif $item.status ==0}等待初审{ elseif $item.status ==-1}<font color="#999999">尚未发布</font>{ elseif $item.status ==3}满标借款成功{ elseif $item.status ==4}复审未通过{else}初审未通过{/if}</td>
			<td >{if $item.status ==0 }<a href="{$_A.query_url}/view{$_A.site_url}&user_id={$item.user_id}&id={$item.id}">审核</a>{/if}  {if ($item.status == 0) || ($item.status==1 && ($item.biao_type!='lz' || ($item.biao_type=='lz' && $item.account_yes==0)))}
				{if $item.account>$item.account_yes}
				<a href="#" onClick="javascript:if(confirm('确定要撤回吗?')) location.href='{$_A.query_url}/cancel{$_A.site_url}&id={$item.id}'">撤回</a>
				{else}
				{if $item.status!=3}<a href="{$_A.query_url}/full_view{$_A.site_url}&user_id={$item.user_id}&id={$item.id}">审核</a>{/if}
				{/if}
			{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="12" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
			标类型：<select name="biaoType" id="biaoType">
					<option value="">所有</option>
					<option value="fast" {if $magic.request.biaoType=='fast'}selected{/if} >抵押标</option>
					<option value="jin" {if $magic.request.biaoType=='jin'}selected{/if} >净值标</option>
					<option value="miao" {if $magic.request.biaoType=='miao'}selected{/if} >秒还标</option>
					<option value="xin" {if $magic.request.biaoType=='xin'}selected{/if} >信用标</option>
					<option value="lz" {if $magic.request.biaoType=='lz'}selected{/if} >流转标</option>
				</select>
			状态：<select id="status"><option value="">全部</option><option value="3" {if $magic.request.status==3}selected="selected"{/if}>复审通过</option><option value="4" {if $magic.request.status==4}selected="selected"{/if}>复审失败</option></select>
			<input type="button" value="搜索" onclick="sousuo()" />
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="12" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>	
</table>
<!-- 满标列表 结束 -->

<!-- 满标复审和撤回 开始 -->
{elseif $_A.query_type == "full_view" }
<div class="module_add">
	<div class="module_title"><strong>已满额借款标审核</strong></div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="c">
			<a href="javascript:void(0)" onclick='tipsWindown("用户详细信息查看","url:get?{$_A.admin_url}&q=module/user/view&user_id={$_A.borrow_result.user_id}&type=scene",500,230,"true","","true","text");'>	{$_A.borrow_result.username}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">标题：</div>
		<div class="c">
			{$_A.borrow_result.name}
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款金额：</div>
		<div class="h">
			￥{$_A.borrow_result.account}
		</div>
		<div class="l">年利率：</div>
		<div class="h">
			{$_A.borrow_result.apr} %
		</div>
	</div>
	<div class="module_border">
		<div class="l">已借到款：</div>
		<div class="h">￥{$_A.borrow_result.account_yes}</div>
		<div class="l">借款期限：</div>
		<div class="h">
			{if $_A.borrow_result.isday==1 } 
                {$_A.borrow_result.time_limit_day}天
                {else}
                {$_A.borrow_result.time_limit}个月
                {/if}
		</div>
		<div class="l">借款用途：</div>
		<div class="h">
			{$_A.borrow_result.use|linkage:"borrow_use"}
		</div>
	</div>
	<div class="module_border">
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">用户名称</td>
			<td width="" class="main_td">投资金额</td>
			<td width="" class="main_td">有效金额</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">投标时间</td>
		</tr>
		{foreach from=$_A.borrow_tender_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{ $item.id}</td>
			<td class="main_td1"><a href="javascript:void(0)" onclick='tipsWindown("用户详细信息查看","url:get?{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene",500,230,"true","","true","text");'>	{$item.username}</a></td>
			<td >{$item.money}元</td>
			<td ><font color="#FF0000">{$item.tender_account}元</font></td>
			<td >{if $item.money == $item.tender_account}全部通过{else}部分通过{/if}</td>
			<td >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="9" class="page">
			{$_A.showpage}
			</td>
		</tr>
	</table>
	</div>
	{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}
	<div class="module_border">
	<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">计划还款日</td>
			<td width="" class="main_td">预还金额</td>
			<td width="" class="main_td">本金</td>
			<td width="" class="main_td">利息</td>
		</tr>
		{foreach from=$_A.borrow_repayment key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$key+1}</td>
			<td>{$item.repayment_time|date_format:"Y-m-d"}</td>
			<td>￥{$item.repayment_account}</td>
			<td>￥{$item.capital}</td>
			<td>￥{$item.interest}元</td>
		</tr>
		{/foreach}
	</table>
	</div>
	{/if}
	<div class="module_title"><strong>其他详细内容</strong></div>
	<div class="module_border">
		<div class="l">投标奖励：</div>
		<div class="h">
			{if $_A.borrow_result.award==0}无奖励{elseif $_A.borrow_result.award==2}比例：{$_A.borrow_result.funds}%{else}{$_A.borrow_result.part_account}{/if}
		</div>
		<div class="l">投标失败是否奖励：</div>
		<div class="h">
			{if $_A.borrow_result.is_false==0}是{else}否{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">添加时间：</div>
		<div class="h">
			{$_A.borrow_result.addtime|date_format:"Y-m-d H:i:s"}
		</div>
		<div class="l">招标时间：</div>
		<div class="h">
			{$_A.borrow_result.verify_time|date_format:"Y-m-d H:i:s"}
		</div>
	</div>
	<div class="module_title"><strong>用户确认协议</strong></div>
	<div class="module_border">
		<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td class="main_td">名称</td>
			<td class="main_td">状态</td>
			<td class="main_td">提交时间</td>
			<td class="main_td">操作</td>
		</tr>
		{foreach from=$_A.borrow_protocol key=key item=item}
		<tr>
			<td>{$item.name}</td>
			<td>{if $item.litpic==""}<img src="/themes/soonmes/images/no.gif" />{else}<img src="/themes/soonmes/images/ico_yes.gif" />{/if}</td>
			<td>{$item.addtime|date_format:Y-m-d H:i:s}</td>
			<td>{if $item.litpic==""}<a href="javascript:sendsmsprotocol('{$_A.query_url}/sendsmsprotocol','{$_A.borrow_result.id}')" name="sendsmsprotocol">发送短信通知</a>{else}<a href="{$item.litpic}" class="thickbox" title="{$item.name}">查看</a>{/if}</td>
		</tr>
		{/foreach}
	</table>
	</div>
	{if $_A.borrow_result.status==1}
	<div class="module_title"><strong>审核此借款</strong></div>
	<form name="form1" method="post"{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}action=""{else}action="{$_A.query_url}/cancel{$_A.site_url}"{/if} >
	<div class="module_border">
		<div class="l">状态:</div>
		<div class="c">
		{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}
		<input type="radio" name="status" value="3" />复审通过 <input type="radio" name="status" value="4" checked="checked" />复审不通过 </div>
		{else}
		<input type="radio" name="status" value="5" checked="checked" />撤回
		{/if}
	</div>
	{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}
	<div class="module_border" >
		<div class="l">审核备注:</div>
		<div class="c">
			<textarea name="repayment_remark" cols="45" rows="5">{ $_A.borrow_result.repayment_remark}</textarea>
		</div>
	</div>
	{/if}
	<div class="module_border" >
		<div class="l">验证码:</div>
		<div class="c">
			<input name="valicode" type="text" size="11" maxlength="4"  tabindex="3"/>&nbsp;<img src="/plugins/index.php?q=imgcode" alt="点击刷新" onClick="this.src='/plugins/index.php?q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="id" value="{$_A.borrow_result.id }" />
		{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}
		<input type="button" name="reset" value="审核此借款标" onclick="document.forms['form1'].submit();this.disabled=true;submit_fool()" />
		{else}
		<input type="button" name="reset" value="撤回此借款标" onclick="document.forms['form1'].submit();this.disabled=true;submit_fool()" />
		{/if}
	</div>
	</form>
	{/if}
</div>
<!-- 满标复审和撤回 结束 -->
<!-- 已还款 开始 -->
{elseif $_A.query_type=="repayment"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="70px" class="main_td">借款编号</td>
			<td width="" class="main_td">用户名</td>
			<td width="" class="main_td">借款标题</td>
			<td width="" class="main_td">期数</td>
			<td width="" class="main_td">到期时间</td>
			<td width="" class="main_td">还款金额</td>
			<td width="" class="main_td">还款利息</td>
			<td width="" class="main_td">还款时间</td>
			<td width="" class="main_td">第三方状态</td>
			<td width="" class="main_td">管理费状态</td>
			<td width="" class="main_td">状态</td>
		</tr>
		{foreach from=$_A.borrow_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="用户详细信息查看">	{$item.username}</a></td>
			<td title="{$item.borrow_name}" align="left">
			<span style="color:#FF0000">【{$item.show_name}】</span>
			<a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name|truncate:10}</a></td>
			<td >{$item.order+1 }/{$item.time_limit }</td>
			<td >{$item.repayment_time|date_format:"Y-m-d"}</td>
			<td >{$item.repayment_account}元</td>
			<td >{$item.interest}元</td>
			<td >{$item.repayment_yestime|date_format:"Y-m-d"|default:-}</td>
			<td >{if $item.tg_status==1}已还{else}未还{/if}</td>
			<td ><a href="{$_A.query_url}/tgreturn&repayment_id={$item.id}&a=borrow">{if $item.interest_fee_status==1}已全部扣除{else}部分扣除{/if}</a></td>
			<td >{if $item.status==1}<font color="#006600">已还</font>{elseif $item.status==2}<font color="#006600">网站代还</font>{else}<font color="#FF0000">未还</font>{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="导出列表" />
		</div>
		<div class="floatr">
		还款时间：<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()"/> 到 <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()"/>
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
				标类型：<select id="biaoType" name="biaoType">
				<option value="">全部</option>
				<option value="1" {if $magic.request.biaoType==1}selected="selected"{/if}>抵押标</option>
				<option value="2" {if $magic.request.biaoType==2}selected="selected"{/if}>净值标</option>
				<option value="3" {if $magic.request.biaoType==3}selected="selected"{/if}>秒还标</option>
				<option value="4" {if $magic.request.biaoType==4}selected="selected"{/if}>信用标</option>
			</select>
			<select id="status" >
			<option value="">全部</option>
			<option value="1" {if $magic.request.status==1} selected="selected"{/if}>已还</option>
			<option value="2" {if $magic.request.status==2} selected="selected"{/if}>网站代还</option>
			<option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>未还</option>
			</select><input type="button" value="搜索" onclick="sousuo()" />
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
<!-- 已还款 结束 -->
<!-- 托管返回 结束 -->
{elseif $_A.query_type=="tgreturn"}
<div class="module_add">
	<div class="module_title"><strong>托管返回</strong></div>
	{foreach from=$_A.tgreturn.tg_return key=key item=item}
	<div class="module_border">
		<div class="l">{$key}</div>
		<div class="c">
			{$item}
		</div>
	</div>
	{/foreach}
	<div class="module_title"><strong>利息管理费扣除状况</strong></div>
	<div class="module_border">
		<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td width="" class="main_td">用户名称</td>
			<td width="" class="main_td">本期应收本金</td>
			<td width="" class="main_td">本期应收利息</td>
			<td width="" class="main_td">应收合计</td>
			<td width="" class="main_td">来源</td>
			<td width="" class="main_td">已收合计</td>
			<td width="" class="main_td">已收逾期利息</td>
			<td width="" class="main_td">应扣利息管理费</td>
			<td width="" class="main_td">扣除状态</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{foreach from=$_A.tgreturn.interest_fee key=key item=item}
		<tr>
			<td>{$item.username}</td>
			<td>{$item.capital}元</td>
			<td>{$item.interest}元</td>
			<td>{$item.repay_account}元</td>
			<td>{if $item.is_sell==1}被转让{elseif $item.is_buy==1}债权购买所得{else}正常{/if}</td>
			<td>{$item.repay_yesaccount}</td>
			<td>{$item.late_interest}元</td>
			<td>{$item.interest_fee}元</td>
			<td id="status_{$item.id}">{if $item.interest_fee_status>0}已扣除{$item.interest_fee_status}次{else}未扣除{/if}</td>
			<td id="cz_{$item.id}">{if $item.is_sell!=1 && $item.interest_fee_status==0}<a href="javascript:kc_interest_fee({$item.id})">扣除</a>{else}--{/if}</td>
		</tr>
		{/foreach}
		</table>
	</div>
</div>
<script type="text/javascript">
var query_url = "{$_A.query_url}";
{literal}
function kc_interest_fee(id){
	$.jBox.tip('操作中','loading');
	$.get('/'+query_url+'/kcinterestfee','collection_id='+id,function(re){
		if(re==1){
			$.jBox.tip('操作成功','success');
			$("#status_"+id).html("已扣除");
			$("#cz_"+id).html("--");
		}else{
			$.jBox.tip(re,'error');
		}
	})
}
{/literal}
</script>


<!-- 流标 开始 -->
{elseif $_A.query_type=="liubiao"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="70px" class="main_td">借款编号</td>
			<td width="*" class="main_td" >用户名</td>
			<td width="" class="main_td" >借款标题</td>
			<td width="" class="main_td" >借款期限</td>
			<td width="" class="main_td" >借款金额</td>
			<td width="" class="main_td" >已投金额</td>
			<td width="" class="main_td" >开始时间</td>
			<td width="" class="main_td" >结束时间</td>
			<td width="" class="main_td" >状态</td>
		</tr>
		{foreach from=$_A.borrow_list key=key item=item}
		<tr {if $key%2==1}class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="用户详细信息查看">	{$item.username}</a></td>
			<td title="{$item.borrow_name}" align="left">
			<span style="color:#FF0000">【{$item.show_name}】</span>
			<a href="/invest/a{$item.id}.html" target="_blank">{$item.name|truncate:10}</a></td>
			<td>{$item.time_limit }个月</td>
			<td>{$item.account }元</td>
			<td>{$item.account_yes }元</td>
			<td>{$item.verify_time|date_format:"Y-m-d"}</td>
			<td>{$item.verify_time+$item.valid_time*24*60*60|date_format:"Y-m-d"}</td>
			<td><a href="{$_A.query_url}/liubiao_edit&id={$item.id}{$_A.site_url}">修改</a></td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" />
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
<!-- 流标 结束 -->
<!-- 流标修改 开始 -->
{elseif $_A.query_type=="liubiao_edit"}
<div class="module_title"><strong>流标管理</strong></div>
	<div class="module_border">
		<div class="l">用户名：</div>
		<div class="h">
			{$_A.borrow_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">标题：</div>
		<div >
			<a href="/invest/a{$_A.borrow_result.id}.html" target="_blank">{$_A.borrow_result.name}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">借款额度：</div>
		<div class="h">
			{$_A.borrow_result.account}
		</div>
	</div>
	<div class="module_border">
		<div class="l">已借额度：</div>
		<div class="h">
			{$_A.borrow_result.account_yes}
		</div>
	</div>
	<div class="module_border">
		<div class="l">申请时间：</div>
		<div class="h">
			{$_A.borrow_result.verify_time|date_format}
		</div>
	</div>
	<div class="module_border">
		<div class="l">结束时间：</div>
		<div class="h">
			{$_A.borrow_result.verify_time+$_A.borrow_result.valid_time*24*60*60|date_format}
		</div>
	</div>
	<div class="module_title"><strong>审核</strong></div>
	<form name="form1" method="post" action="">
	<div class="module_border">
		<div class="l">审核状态：</div>
		<div>
			{if $_A.borrow_result.biao_type=='lz'}{else}
			<input type="radio" name="status" value="1" />流标返回金额{/if}<input type="radio" name="status" value="2" checked="checked" />延长借款期限
		</div>
	</div>
	<div class="module_border">
		<div class="l">延长天数：</div>
		<div >
			<input type="text" name="days" value="{$_A.borrow_amount_result.account}" size="5" value="0" />天
		</div>
	</div>
	<div class="module_border">
		<div class="l">验证码：</div>
		<div >
			<input type="hidden" name="id" value="{$_A.borrow_result.id}">
			<input type="text" name="valicode" size="5" maxlength="4" /><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="点击刷新" src="/plugins/index.php?q=imgcode">
		</div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="h">
			<input type="button" value="确定审核" onclick="document.forms['form1'].submit();this.disabled=true;submit_fool()" />
		</div>
	</div>
	</form>
<!-- 流标修改 结束 -->
<!--额度管理 开始-->
{elseif $_A.query_type=="amount"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td" >用户名称</td>
			<td width="" class="main_td" >申请类型</td>
			<td width="" class="main_td" >原来额度</td>
			<td width="" class="main_td" >申请额度</td>
			<td width="" class="main_td" >新额度</td>
			<td width="" class="main_td" >申请时间</td>
			<td width="" class="main_td" >内容</td>
			<td width="" class="main_td" >备注</td>
			<td width="" class="main_td" >状态</td>
			<td width="" class="main_td" >操作</td>
		</tr>
		{foreach from=$_A.borrow_amount_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="用户详细信息查看">	{$item.username}</a></td>
			<td width="80" >{if $item.type =="tender_vouch"}<a href="{$_A.query_url}/amount&type=tender_vouch&a=borrow">投资担保额度</a>{elseif $item.type =="borrow_vouch"}<a href="{$_A.query_url}/amount&type=borrow_vouch&a=borrow">借款担保额度</a>{else}<a href="{$_A.query_url}/amount&type=credit&a=borrow">借款信用额度</a>{/if}</td>
			<td width="70" >{$item.account_old}元</td>
			<td width="70"  >{$item.account}元</td>
			<td>{$item.account_new}元</td>
			<td>{ $item.addtime|date_format}</td>
			<td>{ $item.content}</td>
			<td>{ $item.remark}</td>
			<td width="50">{if $item.status==2}<font color="#6699CC">审核中</font>{elseif $item.status==1}成功 {else}<font color="#FF0000">失败</font>{/if}</td>
			<td width="70">{if $item.status==2}<a href="{$_A.query_url}/amount_view{$_A.site_url}&id={$item.id}">审核/查看</a>{else}--{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="11" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
			状态：<select id="status" ><option value="">全部</option><option value="2" {if $magic.request.status==2} selected="selected"{/if}>等待审核</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>已通过</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>未通过</option></select> <input type="button" value="搜索" onclick="sousuo('{$_A.query_url}/amount{$_A.site_url}')" />
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="11" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>	
</table>
<!--额度管理 结束-->
<!--当前已经逾期借款 开始-->
{elseif $_A.query_type=="late"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">借款编号</td>
			<td width="" class="main_td" >借款人</td>
			<td width="" class="main_td" >借款标题</td>
			<td width="" class="main_td" >期数</td>
			<td width="" class="main_td" >到期时间</td>
			<td width="" class="main_td" >应还本息</td>
			<td width="" class="main_td" >逾期天数</td>
			<td width="" class="main_td" >罚金</td>
			<td width="" class="main_td" >操作</td>
		</tr>
		{foreach from=$_A.borrow_repayment_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{ $item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="用户详细信息查看">	{$item.username}</a></td>
			<td align="left">
			<span style="color:#FF0000">【{$item.show_name}】</span>
			<a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name}</a></td>
			<td>{$item.order+1 }/{$item.time_limit}</td>
			<td>{$item.repayment_time|date_format:"Y-m-d"}</td>
			<td>{$item.repayment_account }元</td>
			<td>{$item.late_days}天</td>
			<td>{$item.late_interest}</td>
			<td>{if $item.status==2}<font color="#FF0000">已代还</font>{else}{if $item.late_days>0}<a href="{$_A.query_url}/late_repay{$_A.site_url}&id={$item.id}">还款</a>{else}-{/if}{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="11" class="action">
		<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="导出列表" />
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username}"/> 
			状态：<select id="status"><option value="">全部</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>已还</option><option value="2" {if $magic.request.status==2} selected="selected"{/if}>网站代还</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>未还</option></select> 
			<input type="button" value="搜索" onclick="sousuo()" />
		</div>
		</td>
	</tr>
		<tr>
			<td colspan="11" class="page">
			{$_A.showpage}
			</td>
		</tr>
	</form>
</table>
<!-- 当前已经逾期借款 结束 -->

<!-- 网站代还 开始 -->
{elseif $_A.query_type=="late_repay"}
<div class="module_title"><strong>逾期网站代还</strong></div>
<div class="module_border">
	<div class="l">标题：</div>
	<div>
		<span style="color:#FF0000">【{$_A.borrow_result.show_name}】</span>
		<a href="/invest/a{$_A.borrow_result.borrow_id}.html" target="_blank">{$_A.borrow_result.borrow_name}</a>
	</div>
</div>
<div class="module_border">
	<div class="l">借款人：</div>
	<div>{$_A.borrow_result.username}</div>
</div>
<div class="module_border">
	<div class="l">期数：</div>
	<div>{$_A.borrow_result.order+1 }/{$_A.borrow_result.time_limit}</div>
</div>
<div class="module_border">
	<div class="l">应还时间：</div>
	<div>{$_A.borrow_result.repayment_time|date_format:"Y-m-d"}</div>
</div>
<div class="module_border">
	<div class="l">逾期天数：</div>
	<div>{$_A.borrow_result.late_days}天</div>
</div>
<div class="module_border">
	<div class="l">逾期罚金：</div>
	<div>{$_A.borrow_result.late_interest}元</div>
</div>
<div class="module_border">
	<div class="l">应还金额：</div>
	<div>{$_A.borrow_result.repayment_account}元</div>
</div>
<div class="module_title"><strong>投资人信息</strong></div>
<div class="module_border">
	<table  border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td" >用户名称</td>
			<td width="" class="main_td" >vip状态</td>
			<td width="" class="main_td" >本期应收本金</td>
			<td width="" class="main_td" >本期应收利息</td>
			<td width="" class="main_td" >应收合计</td>
			<td width="" class="main_td" >网站代还比例</td>
			<td width="" class="main_td" >网站代还本金</td>
			<td width="" class="main_td" >网站代还利息</td>
			<td width="" class="main_td" >网站代还合计</td>
		</tr>
		{foreach  from=$_A.borrow_tender_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1"><a href="javascript:void(0)" onclick='tipsWindown("用户详细信息查看","url:get?{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene",500,230,"true","","true","text");'>{$item.username}</a></td>
			<td>{if $item.vip_status==1}是{else}否{/if}</td>
			<td>{$item.capital}元</td>
			<td>{$item.interest}元</td>
			<td>{$item.repay_account}元</td>
			<td>{$item.bili*100}%</td>
			<td>{$item.webrepay_capital}元</td>
			<td>{$item.webrepay_interest}元</td>
			<td><font style="color:red">{$item.webrepay_account}元</font></td>
		</tr>
		{/foreach}
	</table>
</div>
<!--
{if $_A.borrow_result.status==0}
<div class="module_title"><strong>网站代还</strong></div>
{if $_A.borrow_result.advance_time<=$_A.borrow_result.late_days}
<form name="form1" method="post" action="">
<div class="module_border">
	<div class="l">验证码：</div>
	<input type="hidden" name="id" value="{$_A.borrow_result.id}">
	<div><input type="text" name="valicode" maxlength="4" size="5"><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="点击刷新" src="/plugins/index.php?q=imgcode"></div>
</div>
<div style="text-align:center"><input type="button" value="确认网站代还" onclick="document.forms['form1'].submit();this.disabled=true;submit_fool()" /></div>
{else}
<h2 style="text-align:center">此借款标尚未逾期{$_A.borrow_result.advance_time}天，不能代还</h2>
{/if}
</form>
{/if}-->
<!--
{if $_A.borrow_result.status==0}
<div class="module_title"><strong>操作</strong></div>
<form name="form1" method="post" action="">
<div class="module_border">
	<div class="l">用户已还款：</div>
	<div class="c">
	<input type="radio" name="status" value="1" checked="checked" />是
	<input type="hidden" name="id" value="{$_A.borrow_result.id}" />
	<input type="hidden" name="is_user_repay" value="1" id="is_user_repay" />
	<input type="hidden" name="user_id" value="{$_A.borrow_result.user_id}" />
	</div>
</div>
<div class="module_border">
	<div class="l">验证码：</div>
	<div class="c"><input type="text" name="valicode" maxlength="4" size="5" /><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="点击刷新" src="/plugins/index.php?q=imgcode"></div>
</div>
<div class="module_border">
	<div class="l"></div>
	<div class="c" style="color:red">注：请确认第三方托管已到账再执行该操作</div>
</div>
<div style="text-align:center">
<input type="button" value="确认使用风险储备金代还款" onclick="repay_dz(2)" />
<input type="button" value="确认用户已还款（当宝付已还款网站未还款时使用）" onclick="repay_dz(1)" />
</div>
</form>
{literal}
<script type="text/javascript">
function repay_dz(u){
	$("#is_user_repay").val(u);
	document.forms['form1'].submit();
	submit_fool();
}
</script>
{/literal}
{/if}-->
<!-- 网站代还 结束 -->
<!-- 抵押标到期 开始 -->
{elseif $_A.query_type=="lateFast"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">借款编号</td>
			<td width="" class="main_td">用户名</td>
			<td width="" class="main_td">借款标题</td>
			<td width="" class="main_td">期数</td>
			<td width="" class="main_td">到期时间</td>
			<td width="" class="main_td">应还本息</td>
			<td width="" class="main_td">应还利息</td>
			<td width="" class="main_td">逾期天数</td>
			<td width="" class="main_td">罚金</td>
			<td width="" class="main_td">操作</td>
		</tr>
                <?php  $showtime=date("y-m-d");?>
		{foreach from=$_A.borrow_repayment_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td >{$item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="用户详细信息查看">	{$item.username}</a></td>
			<td align="left">
			<span style="color:#FF0000">【{$item.show_name}】</span>
			<a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name}</a></td>
			<td>{$item.order+1 }/{$item.time_limit}</td>
			<td >{$item.repayment_time|date_format:"Y-m-d"}</td>
			<td >{$item.repayment_account }元</td>
			<td >{$item.interest}元</td>
			<td >{$item.late_days}天</td>
			<td >{$item.late_interest}</td>
			<td >{if $item.status==2}<font color="#FF0000">已代还</font>{else}{if $item.late_days>=0}<a href="{$_A.query_url}/late_repay{$_A.site_url}&id={$item.id}">还款</a>{else}--{/if}{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="11" class="action">
		<div class="floatl">
		<input type="button" onclick="sousuo('excel')" value="导出列表" />
		</div>
		<div class="floatr">
			用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" />
			状态：<select id="status" ><option value="">全部</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>已还</option><option value="2" {if $magic.request.status==2} selected="selected"{/if}>网站代还</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>未还</option></select> 
			<input type="button" value="搜索" onclick="sousuo('{$_A.query_url}/amount{$_A.site_url}')" />
		</div>
		</td>
	</tr>
		<tr>
			<td colspan="11" class="page">
			{$_A.showpage}
			</td>
		</tr>
	</form>
</table>
<!--抵押标到期 结束-->
<!--额度审核 开始-->
{elseif $_A.query_type=="amount_view"}
<div class="module_title"><strong>额度审核</strong></div>
<div class="module_border">
	<div class="l">用户名：</div>
	<div class="h">
		{$_A.borrow_amount_result.username}
	</div>
</div>
<div class="module_border">
	<div class="l">借款类型：</div>
	<div class="h">
		{if $_A.borrow_amount_result.type=="tender_vouch"}<font color="#FF0000">投资担保额度</font>{elseif $_A.borrow_amount_result.type=="borrow_vouch"}<font color="#FF0000">借款担保额度</font>{else}信用额度{/if}
	</div>
</div>
<div class="module_border">
	<div class="l">原来金额：</div>
	<div class="h">
		{$_A.borrow_amount_result.account_old|default:0}
	</div>
</div>
<div class="module_border">
	<div class="l">申请额度：</div>
	<div class="h">
		{$_A.borrow_amount_result.account}
	</div>
</div>
<div class="module_border">
	<div class="l">内容：</div>
	<div class="h">
		{$_A.borrow_amount_result.content}
	</div>
</div>
<div class="module_border">
	<div class="l">备注：</div>
	<div class="h">
		{$_A.borrow_amount_result.remark}
	</div>
</div>
<div class="module_border">
	<div class="l">申请时间：</div>
	<div class="h">
		{$_A.borrow_amount_result.addtime|date_format}
	</div>
</div>
{if $_A.borrow_amount_result.status==2}
<div class="module_title"><strong>附件</strong></div>
{if $_A.borrow_amount_result.credit_file==''}
用户未上传附件
{else}
{foreach from=$_A.borrow_amount_result.credit_file item=item key=key}
<a href="{$item}" target="_blank">附件{$key+1}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
{/foreach}
{/if}
<div class="module_title"><strong>审核</strong></div>
<form method="post" action="" name="form1">
<div class="module_border">
	<div class="l">审核状态：</div>
	<div class="h">
		<input type="radio" name="status" value="1" />通过  <input type="radio" name="status" value="0" checked="checked" />不通过
	</div>
</div>
<div class="module_border">
	<div class="l">通过额度：</div>
	<div class="h">
		<input type="text" name="account" value="{$_A.borrow_amount_result.account}" />
		<input type="hidden" name="type" value="{ $_A.borrow_amount_result.type}" />
	</div>
</div>
<div class="module_border">
	<div class="l">审核备注：</div>
	<div class="h" style="width:305px">
		<textarea name="verify_remark" rows="5" cols="40" >{$_A.borrow_amount_result.verify_remark}</textarea>
	</div>
</div>
<div class="module_border">
	<div class="l">验证码：</div>
	<div class="h">
		<input type="text" name="valicode" size="5" maxlength="4"><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="点击刷新" src="/plugins/index.php?q=imgcode">
	</div>
</div>
<div class="module_border">
	<div class="l"></div>
	<div class="h">
		<input type="button" name="tijiao" value="确定审核" onclick="sub_form()" />
	</div>
</div>
{/if}
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var form=document.forms['form1'];
	var verify_remark=form.elements['verify_remark'].value;
	var account=form.elements['account'].value;
	var valicode=form.elements['valicode'].value;
	account=parseFloat(account);
	var err="";
	if(verify_remark.length==0){
		err += "--备注不能为空\n";
	}
	if(account<=0 || isNaN(account)){
		err += "--通过额度输入有误\n";
	}
	if(valicode.length!=4){
		err += "--验证码输入有误";
	}
	if(err.length>0){
		alert(err);
	}else{
		form.elements['tijiao'].disabled=true;
		form.elements['tijiao'].value="提交中..";
		form.submit();
		submit_fool();
	}
}
</script>
{/literal}
<!--额度审核 结束-->
		
<!--添加额度 开始-->
{elseif $_A.query_type=="addamount"}
<form method="post" action="" name="form1">
<div class="module_title"><strong>添加额度</strong></div>
<div class="module_border">
	<div class="l">用户名：</div>
	<div class="h">
		<input type="text" name="username" />
	</div>
</div>
<div class="module_border">
	<div class="l">借款类型：</div>
	<div class="h">
		<select name="type"><option value="credit">借款信用额度</option></select>
	</div>
</div>
<div class="module_border">
	<div class="l">添加额度：</div>
	<div class="h">
		<input type="text" name="account" value="">
	</div>
</div>
<div class="module_border">
	<div class="l">详细说明：</div>
	<div class="h" style="width:500px">
		<textarea rows="5" cols="40" name="content"></textarea>
	</div>
</div>
<div class="module_border">
	<div class="l">其它地方借款详细说明：</div>
	<div class="h" style="width:500px">
		<textarea rows="5" cols="40" name="remark"></textarea>
	</div>
</div>
<div class="module_border">
	<div class="l">审核状态：</div>
	<div class="h">
		<input type="radio" name="status" value="1" checked="checked" />立即通过  <input type="radio" name="status" value="2"/>还需审核
	</div>
</div>
<div class="module_border">
	<div class="l">审核备注：</div>
	<div class="h" style="width:305px">
		<textarea name="verify_remark" rows="5" cols="40" >管理员添加</textarea>
	</div>
</div>
<div class="module_border">
	<div class="l">验证码：</div>
	<div class="h">
		<input type="text" name="valicode" size="5" maxlength="4"><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="点击刷新" src="/plugins/index.php?q=imgcode">
	</div>
</div>
<div class="module_border">
	<div class="l"></div>
	<div class="h">
		<input type="button" name="tijiao" value="确定审核" onclick="sub_form()" />
	</div>
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var form=document.forms['form1'];
	var username = form.elements['username'].value;
	var verify_remark=form.elements['verify_remark'].value;
	var account=form.elements['account'].value;
	var valicode=form.elements['valicode'].value;
	account=parseFloat(account);
	var err="";
	if(username.length==0){
		err += "-- 用户名不能为空<br/>";
	}
	if(verify_remark.length==0){
		err += "-- 备注不能为空<br/>";
	}
	if(account<=0 || isNaN(account)){
		err += "-- 添加额度输入有误<br/>";
	}
	if(valicode.length!=4){
		err += "-- 验证码输入有误<br/>";
	}
	if(err.length>0){
		jQuery.jBox.info(err,'提示');
		return false;
	}else{
		form.elements['tijiao'].disabled=true;
		form.elements['tijiao'].value="提交中..";
		form.submit();
		submit_fool();
	}
}
</script>
{/literal}
<!--添加额度 结束-->

<!--统计 开始-->
{elseif $_A.query_type=="tongji"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="*" class="main_td">类型</td>
			<td width="*" class="main_td">总额</td>
		</tr>
		<tr  class="tr2">
			<td >成功借出总额</td>
			<td >￥{$_A.borrow_tongji.success_num}</td>
		</tr>
		<tr  >
			<td >己还款总额</td>
			<td >￥{$_A.borrow_tongji.success_num1}</td>
		</tr>
		<tr  class="tr2">
			<td >未还款总额</td>
			<td >￥{$_A.borrow_tongji.success_num0}</td>
		</tr>
		<tr  >
			<td >逾期总额</td>
			<td >{$_A.borrow_tongji.laterepay}</td>
		</tr>
		<tr  class="tr2">
			<td >逾期己还款总额</td>
			<td >￥{$_A.borrow_tongji.success_laterepay}</td>
		</tr>
		<tr >
			<td >逾期未还款总额</td>
			<td >￥{$_A.borrow_tongji.false_laterepay}</td>
			
		</tr>
		
	</form>	
</table>
<!--统计 结束-->

<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
	  {foreach from="$_A.account_tongji" key=key  item="item"}
		<tr >
			<td width="*" class="main_td">类型名称</td>
			<td width="*" class="main_td">{$key}</td>
			<td width="" class="main_td">金额</td>
		</tr>
		{foreach from="$item" key="_key" item="_item"}
		<tr  class="tr2">
			<td >{$_item.type_name}</td>
			<td >{$_item.type}</td>
			<td >￥{$_item.num}</td>
		</tr>
		{/foreach}
	{/foreach}
	</form>	
</table>
<!--统计 结束-->

{elseif $_A.query_type=="borrowtongji"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td">类型</td>
		<td class="main_td">结果</td>
		<td class="main_td">金额合计</td>
	</tr>
	{foreach from=$_A.borrowtongji item=item key=key}
	<tr>
	<td>{$item.name}</td>
	<td>{$item.value}</td>
	<td>{$item.account_yes}</td>
	</tr>
	{/foreach}
	<td colspan="3" class="action">
	发标地区：<script src="/plugins/index.php?q=area&area={$magic.request.province}&type=p|c" type="text/javascript" ></script>
	用户类型：<select name="user_type" id="user_type"><option value="">请选择</option><option value="-2" {if $magic.get.user_type==-2}selected="selected"{/if}>分类显示</option><option value=1 {if $magic.get.user_type==1}selected="selected"{/if}>私营业主</option><option value=2 {if $magic.get.user_type==2}selected="selected"{/if}>工薪族</option></select>
	业务员：<select name="salesman_user" id="salesman_user">
	<option value=-1>请选择</option>
	<option value="-2" {if $magic.get.salesman_user==-2}selected="selected"{/if}>分类显示</option>
	<option value=0 {if $magic.get.salesman_user==0}selected="selected"{/if}>无业务员</option>
	{loop module="userinfo" function="GetSalesmanList" var="salesman"}
		<option value="{$salesman.user_id}" {if $magic.get.salesman_user==$salesman.user_id}selected="selected"{/if}>{$salesman.username}</option>
	{/loop}
	</select>
	推荐机构：{linkages nid="recommend_organ" value="$magic.get.recommend_organ" name="recommend_organ" default="请选择" default2="分类显示"}
	所属机构：{linkages nid="recommend_organ" value="$magic.get.belong_organ" name="belong_organ" default="请选择" default2="分类显示"}
	注册时间：<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()" /> 到 <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()" />
	<input type="button" value="导出EXCEL报表" onclick="sousuo_tongji('excel')" />
	<input type="button" value="搜索" onclick="sousuo_tongji()" />
	</td>
</table>

<!-- 全能贷申请用户 -->
{elseif $_A.query_type == "quickborrow"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">借款类型</td>
		<td width="" class="main_td">企业名</td>
		<td width="" class="main_td">法人/负责人</td>
		<td width="" class="main_td">联系电话</td>
		<th width="" class="main_td">借款金额</th>
		<th width="" class="main_td">周期</th>
		<th width="" class="main_td">提交时间</th>
		<th width="" class="main_td">状态</th>
		<td width="" class="main_td">操作</td>
	</tr>
	{foreach from=$_A.quickborrow key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{if $item.type==1}企业融资{else}个人融资{/if}</td>
		<td class="main_td1" align="center">{if $item.type==1}{$item.company_name}{else}--{/if}</td>
		<td class="main_td1" align="center">{$item.legal_person}</td>
		<td class="main_td1" align="center">{$item.phone}</td>
		<td class="main_td1" align="center">{$item.borrow_account}</td>
		<td class="main_td1" align="center">{$item.borrow_cycle}</td>
		<td class="main_td1" align="center">{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center">{if $item.status==1}通过{elseif $item.status==1}未通过{else}等待处理{/if}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/quickborrow_view&id={$item.id}&a=borrow" class="thickbox"  title="详细信息查看">查看</a></td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="10" class="action">
		<div class="floatl"></div>
		<div class="floatr">
			法人/负责人：<input type="text" name="company_name" id="company_name" value="{$magic.request.name|urldecode}"/>
			联系电话：<input type="text" name="phone" id="phone" value="{$magic.request.phone}"/>
			状态：<select name="status" id="status"><option value=-1>全部</option><option value=1 {if $magic.get.status==1}selected="selected"{/if}>通过</option><option value=2 {if $magic.get.status==2}selected="selected"{/if}>未通过</option><option value=0 {if $magic.get.status==0}selected="selected"{/if}>等待处理</option></select>
			<input type="button" value="搜索" onclick="sousuo_quickborrow()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="10" class="page">
		{$_A.showpage}
		</td>
	</tr>
<script type="text/javascript">
var url = '{$_A.query_url}/{$_A.query_type}{$_A.site_url}';
{literal}
function sousuo_quickborrow(){
	var name = $("#company_name").val();
	var status = $("#status").val();
	var phone = $("#phone").val();
	var sousuo = '';
	if(name!=''){
		sousuo += '&company_name='+name;
	}
	if(status!=''){
		sousuo += '&status='+status;
	}
	if(phone!=''){
		sousuo += '&phone='+phone;
	}
	location.href = url+sousuo;
}
</script>
{/literal}

{/if}


<script>
var urls = '{$_A.query_url}/{$_A.query_type}{$_A.site_url}';
{literal}
function sousuo(){
	var sou = "";
	if(arguments[0]=='excel'){
		sou += "&type=excel";
	}
	var username = $("#username").val() || "";
	var status = $("#status").val() || "";
	var dotime1 = $("#dotime1").val() || "";
	var dotime2 = $("#dotime2").val() || "";
	var biaoType = $("#biaoType").val() || "";
	if (username!=""){
		sou += "&username="+username;
	}
	if (status!=""){
		sou += "&status="+status;
	}
	if (dotime1!=""){
		sou += "&dotime1="+dotime1;
	}
	if (dotime2!=""){
		sou += "&dotime2="+dotime2;
	}
	if (biaoType!=""){
		sou += "&biaoType="+biaoType;
	}
	location.href=urls+sou;
}

function sousuoExcel(url){
	var sou = "";
	var username = $("#username").val();
	if (username!=""){
		sou += "&username="+username;
	}
	var keywords = $("#keywords").val();
	if (keywords!=""){
		sou += "&keywords="+keywords;
	}
	var status = $("#status").val();
	if (status!=""){
		sou += "&status="+status;
	}
	
	var dotime1 = $("#dotime1").val();
	if (dotime1!=""){
		sou += "&dotime1="+dotime1;
	}
	var dotime2 = $("#dotime2").val();
	if (dotime2!=""){
		sou += "&dotime2="+dotime2;
	}
	
	var biaoType = $("#biaoType").val();
	if (biaoType!=""){
		sou += "&biaoType="+biaoType;
	}
 
 
	if (sou!=""){
		
		location.href=url+sou;
	}
}

function sousuoFull(url){
	var sou = "";
	var username = $("#username").val();
	if (username!=""){
		sou += "&username="+username;
	}
	var biaoType = $("#biaoType").val();
	if (biaoType!=""){
		sou += "&biaoType="+biaoType;
	}
	var is_vouch = $("#is_vouch").val();
	if (is_vouch!=""){
		sou += "&is_vouch="+is_vouch;
	}
	if (sou!=""){
		
		location.href=url+sou;
	}
}

function sousuoBiaoOrder(url){
	var sou = "";

	var dotime1 = $("#dotime1").val();
	if (dotime1!=""){
		sou += "&dotime1="+dotime1;
	}
	var dotime2 = $("#dotime2").val();
	if (dotime2!=""){
		sou += "&dotime2="+dotime2;
	}
	
	if (sou!=""){
		
		location.href=url+sou;
	}
}
function sendsmsprotocol(url,id){
	$("[name='sendsmsprotocol']").html("短信发送中....");
	$.post(url,"borrow_id="+id,function(re){
		alert(re);
		$("[name='sendsmsprotocol']").html("发送短信通知");
	})
}
	var selectCtl = document.getElementById("province");
	addAt(selectCtl,'分类显示',-2,1);
	function addAt(selectCtl,optionValue,optionText,position){
		var userAgent = window.navigator.userAgent;
		if (userAgent.indexOf("MSIE") > 0) {
			var option = document.createElement("option");
			option.value = optionValue;
			option.innerText = optionText;
			selectCtl.insertBefore(option, selectCtl.options[position]);
		}else{
			selectCtl.insertBefore(new Option(optionValue, optionText), selectCtl.options[position]);
		}
	}
	function sousuo_tongji(){
		var sou = "";
		if(arguments[0]=='excel'){
			sou += '&type=excel';
		}
		var user_type = $("#user_type").val();
		var salesman_user = $("#salesman_user").val();
		var recommend_organ = $("#recommend_organ").val();
		var belong_organ = $("#belong_organ").val();
		var dotime1 = $("#dotime1").val();
		var dotime2 = $("#dotime2").val();
		var province = $("#province").val();
		var city = $("#city").val();
		if(belong_organ!=''){
			sou += "&belong_organ="+belong_organ;
		}
		if(recommend_organ!=''){
			sou += "&recommend_organ="+recommend_organ;
		}
		if(salesman_user!=''){
			sou += "&salesman_user="+salesman_user;
		}
		if(user_type!=''){
			sou += "&user_type="+user_type;
		}
		if(dotime1!=''){
			sou += "&dotime1="+dotime1;
		}
		if(dotime2!=''){
			sou += "&dotime2="+dotime2;
		}
		if(province!=''){
			sou += "&province="+province;
		}
		if(city!=''){
			sou += "&city="+city;
		}
		location.href=urls+sou;
	}
</script>
{/literal}