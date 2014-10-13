<?
include ("../core/config.inc.php");
//add by weego for ÈÚ¶Ü°²È«¼ì²â 20120606
$hackManArray=array('epage','keywords','code','id','type','area','q');
foreach($hackManArray as $hackKey){
	$_REQUEST[$hackKey]=htmlgl($_REQUEST[$hackKey],'1');
	$_REQUEST[$hackKey]=safegl($_REQUEST[$hackKey]);
}
//add by weego for ÈÚ¶Ü°²È«¼ì²â 20120606
$q = !isset($_REQUEST['q'])?"":$_REQUEST['q'];
$file = "html/".$q.".inc.php";
if (file_exists($file)){
	include_once ($file);exit;
}

?>
