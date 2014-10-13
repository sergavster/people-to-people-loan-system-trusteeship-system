<?php

function magic_modifier_truncate_cn($string, $strlen = '20'){
   $tmpstr = "";
    for($i = 0; $i < $strlen; $i++) {
        if(ord(substr($string, $i, 1)) > 0xa0) {
            $tmpstr .= substr($string, $i, 2);
            $i++;
        } else
            $tmpstr .= substr($string, $i, 1);
    }
    return $tmpstr;

}
?>
