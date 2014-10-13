// JavaScript Document
/*¿Í·þ*/
var isIE = navigator.appName == 'Microsoft Internet Explorer';
function heartBeat(id,t){$(id).style.left=(window.screen.width-$(id).offsetWidth-(isIE?21:17))+'px';var offpx=(IeTrueBody().clientHeight)+IeTrueBody().scrollTop-$(id).offsetTop-t;if(offpx!=0)$(id).style.top=($(id).offsetTop+offpx*0.3)+'px';}
function IeTrueBody(){return (document.compatMode && document.compatMode!="BackCompat") ? document.documentElement : document.body;}
function GetScrollTop(){return isIE?IeTrueBody().scrollTop:window.pageYOffset;}
var QQTop=(IeTrueBody().clientHeight)-80;

function AppKeFu(){with($('KeFuMain').style){width='136px';left=(window.screen.width-136-(isIE?21:17))+'px';};$('KeFuContent').style.display='block';$('KeFuIcon').style.backgroundPosition='bottom left';}
function HideKeFu(){with($('KeFuMain').style){width='20px';left=(window.screen.width-21-(isIE?21:17))+'px';};$('KeFuContent').style.display='none';$('KeFuIcon').style.backgroundPosition='top left';}



setInterval('heartBeat("KeFuMain",'+QQTop+')',1);
//$('KeFuAction').onclick=function()
//{
//	if($('KeFuContent').style.display=='none')
//		AppKeFu();
//	else
//		HideKeFu();
//}
HideKeFu();
$('KeFuMain').onmousemove=AppKeFu;
$('KeFuMain').onmouseout=HideKeFu;