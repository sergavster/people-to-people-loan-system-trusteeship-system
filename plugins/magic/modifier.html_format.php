<?php

function magic_modifier_html_format($string, $type = ''){
  $string = preg_replace("/<.+?>/i","",$string); //替换所有HTML的标识
   return $string;

}
?>
