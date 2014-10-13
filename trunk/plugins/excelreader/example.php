<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'excel_reader2.php';
$data = new Spreadsheet_Excel_Reader("example1.xls");
?>
<html>
<head>
<style>
table.excel {
	border-style:ridge;
	border-width:1;
	border-collapse:collapse;
	font-family:sans-serif;
	font-size:12px;
}
table.excel thead th, table.excel tbody th {
	background:#CCCCCC;
	border-style:ridge;
	border-width:1;
	text-align: center;
	vertical-align:bottom;
}
table.excel tbody th {
	text-align:center;
	width:20px;
}
table.excel tbody td {
	vertical-align:bottom;
}
table.excel tbody td {
    padding: 0 3px;
	border: 1px solid #EEEEEE;
}
</style>
</head>

<body>
<?php
$field = '';
for($i=1; $i<=$data->sheets[0]['numCols']; $i++){
	$field .= $field==''?'`'.$data->sheets[0]['cells'][1][$i].'`':',`'.$data->sheets[0]['cells'][1][$i].'`';
}
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
	$value = '';
    for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
            $value .= $value==''?"'".$data->sheets[0]['cells'][$i][$j]."'":",'".$data->sheets[0]['cells'][$i][$j]."'";
    }
    echo "insert into account($field) values($value)";
    echo "<br>";
}

?>
<?php echo $data->dump(true,true); ?>
</body>
</html>
