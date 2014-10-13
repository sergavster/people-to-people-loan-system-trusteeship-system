<?php

/*
 *调用接口代码
 *
 **/
require_once("qq/qqConnectAPI.php");
$qc = new QC('EEC44FFDEE4552C538AD4BB7EE28A3E9','8D3F5F418505027F17B87A0DC5632BF6');
$arr = $qc->get_user_info();


echo '<meta charset="UTF-8">';
echo "<p>";
echo "Gender:".$arr["gender"];
echo "</p>";
echo "<p>";
echo "NickName:".$arr["nickname"];
echo "</p>";
echo "<p>";
echo "<img src=\"".$arr['figureurl']."\">";
echo "<p>";
echo "<p>";
echo "<img src=\"".$arr['figureurl_1']."\">";
echo "<p>";
echo "<p>";
echo "<img src=\"".$arr['figureurl_2']."\">";
echo "<p>";
echo "vip:".$arr["vip"];
echo "</p>";
echo "level:".$arr["level"];
echo "</p>";
echo "is_yellow_year_vip:".$arr["is_yellow_year_vip"];
echo "</p>";

?>
