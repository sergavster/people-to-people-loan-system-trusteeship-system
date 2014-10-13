<?php

function magic_modifier_checked($string, $arr = ''){
    if (!isset($string) || !isset($arr) ){
        return "";
   } else{
   		if (is_array($arr) && $arr[$string]!=""){
			return "checked";
		}else{
        	$arr = explode(",",$arr);
			if (in_array($string,$arr)){
				return "checked";
			}
		}
	}
	return "";
}
?>
