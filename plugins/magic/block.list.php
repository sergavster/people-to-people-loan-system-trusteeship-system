<?php

function magic_block_list($tag_command,$parse_var,$magic_vars){
if  ($tag_command == "list" ) {
    $module = "";
    $function = "";
	$_data = "";
	$city_id = "";
	$site_id = "";
	$user_id = "";
    foreach($parse_var as $_key => $_val) {
        switch($_key) {
			//选择模块
            case 'module':
                $$_key = (string)$_val;
                break;
            
			//执行操作
			case 'function':
                $$_key = (string)$_val;
                break;
			 default:
			 	$$_key = $_val;
                break;
        }
		if ($_key !="module" && $_key!="function" && $_key!="city_id" && $_key!="site_id"){
			if ($_val == "request"){
				$_data[] = "'{$_key}'=>isset(\$_REQUEST['{$_key}'])?\$_REQUEST['{$_key}']:''";
			}elseif ($_val{0}=="$"){
				$_data[] = "'{$_key}'=>{$_val}";
			}else{
				$_data[] = "'{$_key}'=>'{$_val}'";
			}
		}
    }
	
	if ($city_id!=""){
		if ($city_id=="0"){
			$_data[] = "'city_id'=>\$this->magic_vars['_G']['city_result']['id']";
		}else{
			$_data[] = "'city_id'=>'{$city_id}'";
		}
	}
	
	if ($site_id!=""){
		if ($site_id=="0"){
			$_data[] = "'site_id'=>\$this->magic_vars['_G']['site_result']['site_id']";
		}else{
			$_data[]  = "'site_id'=>'{$site_id}'";
		}
	}
	if ($user_id!=""){
		if ($user_id=="0"){
			$_data[] = "'user_id'=>\$this->magic_vars['_G']['user_id']";
		}
	}
	//传入数据
	if ($_data!=""){
		$data = "array(".join(",",$_data).")";
	}
	
		$var = empty($var)?"var":$var;
		//模块不能为空
		if ($module == ""){
			trigger_error("list: extra attribute 'module' cannot be not empty", E_USER_NOTICE);exit;
		}
		//操作类型不能为空
		if ($function == ""){
			trigger_error("list: extra attribute 'function' cannot be not empty", E_USER_NOTICE);exit;
		}else{
			$display = "<? \$this->magic_vars['query_type']='{$function}';\$data = {$data};\$data['page'] = isset(\$_REQUEST['page'])?\$_REQUEST['page']:'';";
		}
		if($module=="user"){
			$display .= "  include_once(ROOT_PATH.'core/user.class.php');\$this->magic_vars['magic_result'] = userClass::{$function}(\$data);";
		}else{
			if (file_exists(ROOT_PATH."modules/{$module}/{$module}.class.php")){
				$display .= "  include_once(ROOT_PATH.'modules/{$module}/{$module}.class.php');\$this->magic_vars['magic_result'] = {$module}Class::{$function}(\$data);";
			}else{
				trigger_error("loop: extra attribute 'module' cannot be not exist", E_USER_NOTICE);exit;
			}
		}
		
		$display .=" \$this->magic_vars['$var']['list'] =  \$this->magic_vars['magic_result']['list'];";
		$display .=" \$this->magic_vars['$var']['page'] =  \$this->magic_vars['magic_result']['page'];";
		$display .=" \$this->magic_vars['$var']['epage'] =  \$this->magic_vars['magic_result']['epage'];";
		$display .=" \$this->magic_vars['$var']['total'] =  \$this->magic_vars['magic_result']['total'];";
		if(isset($showpage)){
			$display .=" \$this->magic_vars['_G']['class_pages']->set_data(\$this->magic_vars['magic_result']);";
			$display .=" \$this->magic_vars['$var']['showpage'] =  \$this->magic_vars['_G']['class_pages']->show($showpage);";
		}else{
			$display .=" \$this->magic_vars['$var']['showpage'] =  show_pages(\$this->magic_vars['magic_result']);";
		}

		$display .= '?>';
		return $display;
	}else if ($tag_command == "/list"){
		return "<? unset(\$_magic_vars); ?>";
	}
}
?>
