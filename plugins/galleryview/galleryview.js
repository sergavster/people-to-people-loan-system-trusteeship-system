


var	JC_ScriptDir='.';
var JC_Scripts = document.getElementsByTagName('script');
	
	for(i=0;i<JC_Scripts.length;i++){
		if (JC_Scripts[i].getAttribute('src')){
			var q=JC_Scripts[i].getAttribute('src');
			
			if(q.match('galleryview.js')){
				var url = q.split('galleryview.js');
				
				var path = url[0];
				var query = url[1].substring(1);
				
				var pars = query.split('&');
				for(j=0; j<pars.length; j++) {
					par = pars[j].split('=');
					switch(par[0]) {
						case 'config': {
							JC_Config = par[1];
							break;
						}
						case 'dir': {
							JC_ScriptDir = par[1];
							break;
						}
						case 'lng': {
							JC_Language = par[1];
							break;
						}
					}
				}
			}
		}
	}
document.write('<link rel="stylesheet" type="text/css" href="'+JC_ScriptDir+'/galleryview.css" />');
document.write('<script type="text/javascript" src="'+JC_ScriptDir+'/jquery.easing.1.3.js"></script>');
document.write('<script type="text/javascript" src="'+JC_ScriptDir+'/jquery.galleryview-1.1.js"></script>');
document.write('<script type="text/javascript" src="'+JC_ScriptDir+'/jquery.timers-1.1.2.js"></script>');




	$(document).ready(function(){
		$('#photos').galleryView({
			panel_width: 715,
			panel_height: 400,
			frame_width: 100,
			frame_height: 100,
			border:"1px dashed #cccccc",
			background_color:"#cccccc",
			overlay_color:"#fff"
		});
	});