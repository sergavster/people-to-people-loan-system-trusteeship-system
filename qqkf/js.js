/*根据ID获取元素*/
function $(id){return document.getElementById(id);}
/*根据ID以及标签名来获取元素数组*/
function $S(obj,d){return obj.getElementsByTagName(d);}
/*根据ID、标签名、样式获取元素数组*/
function $C(id,d,c){var a=new Array();var l=$S($(id),d);for(var i=0;i<l.length;i++)if(l[i].className.indexOf(c)>-1)a[a.length]=l[i];return a;}

var isIE = navigator.appName == 'Microsoft Internet Explorer';

/*提示：此处菜单栏专用*/
/*以下为关键部分*/
function SwapFun(id,l,className,defaultId,act)
{
	var lc=$C(id,'div','hide');
	var tem;

	/* 初始化*/
	defaultId=defaultId?defaultId:0;
	l[defaultId].className=className;
	if(lc[defaultId])
	{
		lc[defaultId].style.display='inline';
		tem=defaultId*90;
		if(lc[defaultId].offsetWidth+tem>720)
			tem=720-lc[defaultId].offsetWidth;
		lc[defaultId].style.marginLeft=tem+'px';
		lc[defaultId].style.display='inline';
	}
	
	if(defaultId>0)
		l[defaultId-1].className='pli';
	function swapdivre(n)
	{
		return function()
		{
			menuswapflag=true;
			for(var i=0;i<l.length;i++)
			{
				if(l[i].className==className)
				{
					l[i].className='';
					if(lc[i])lc[i].style.display='none';
					if(i>0)
						l[i-1].className='';
					l[l.length-1].className='pli';
				}
			}
			l[n].className=className;
			lc[n].style.display='inline';
			
			tem=n*90;
			if(lc[n].offsetWidth+tem>720)
				tem=720-lc[n].offsetWidth;
			lc[n].style.marginLeft=tem+'px';
			if(n>0)
				l[n-1].className='pli';
		}
	}
	return function(l,i){
		if(isIE)
			l[i].attachEvent('on'+act, swapdivre.call(l,i));
		else
			l[i].addEventListener(act, swapdivre.call(l,i),false);
	}
}

/*此函数实现菜单的实例*/
function NewSwap(id,className,defaultId,act)
{
	var l=$S($S($(id),'ul')[0],'li');
	l[l.length-1].className='pli';
	var a=new SwapFun(id,l,className,defaultId,act);
	for(i=0;i<l.length;i++)
		a(l,i);
}
/*菜单功能完成*/

var menuswapflag=false;
var MenuIntervel;

NewSwap('menu','liover',defaultmenu,'mouseover');
function MenuBack()
{
	menuswapflag=false;
	if(MenuIntervel)clearInterval(MenuIntervel);
	var l=$S($S($('menu'),'ul')[0],'li');
	var lc=$C('menu','div','hide');
	for(var i=0;i<l.length;i++)
	{
		if(l[i].className=='liover')
		{
			l[i].className='';
			if(lc[i])lc[i].style.display='none';
			if(i>0)
				l[i-1].className='';
		}
	}
	l[l.length-1].className='pli';
	if(lc[defaultmenu])
		lc[defaultmenu].style.display='block';
	if(defaultmenu>0)
		l[defaultmenu-1].className='pli';
	l[defaultmenu].className='liover';
	if(lc[defaultmenu])
	{
		lc[defaultmenu].style.display='inline';
		var tem=defaultmenu*90;
		if(lc[defaultmenu].offsetWidth+tem>720)
			tem=720-lc[defaultmenu].offsetWidth;
		lc[defaultmenu].style.marginLeft=tem+'px';
		lc[defaultmenu].style.display='inline';
	}
}
//MenuBack();
$('menu').onmouseout=function(){if(menuswapflag)MenuIntervel=setInterval('MenuBack()',500);};
$('menu').onmouseover=function(){if(MenuIntervel)clearInterval(MenuIntervel)};

/*230宽的双门滑动*/
function SwapJoin(f,p)
{
	var l=$(p);
	var ls=$S(l,'li');
	
	with(l.style)
		if(f)
			backgroundPosition='bottom';
		else
			backgroundPosition='top';
	for(var i=0;i<ls.length;i++)
	{
		ls[i].className='';
		$(p+'-'+i).style.display='none';
	}
	ls[f].className='sli';
	$(p+'-'+f).style.display='block';
}

function GetMenuPos()
{
	var a=$S($('MenuContent'),'a');
	var url=unescape(this.location.href);
	var au;
	url=url.substr(url.lastIndexOf('/')+1,url.length).toLowerCase();
	if(url=='')return;
	for(var i=0;i<a.length;i++)
	{
		au=a[i].href;
		au=au.substr(au.lastIndexOf('/')+1,au.length).toLowerCase();
		au=unescape(au);
		if(au==url)
		{
			a[i].style.color='#000000';
			break;
		}
	}
}
GetMenuPos();