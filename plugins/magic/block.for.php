<?php

function magic_block_for($tag_command,$parse_var,$magic_vars){
if  ($tag_command == "for" ) {
    $table = null;
    $where = null;
	$_data = "";
    foreach($parse_var as $_key => $_val) {
        switch($_key) {
			//Ñ¡ÔñÄ£¿é
            
			 default:
			 	$$_key = (string)$_val;
                break;
        }
		
    }
	$start =
	
	
	
		$display .="<? for($i=$start;$i<=$end){";
		
		

		$display .= '?>';
		return $display;
	}else if ($tag_command == "/for"){
		return "<? endforeach; endif; unset(\$_from);unset(\$_magic_vars); ?>";
	}
}
?>
