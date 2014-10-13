


var	JC_ScriptDir='.';
var JC_Scripts = document.getElementsByTagName('script');
	
	for(i=0;i<JC_Scripts.length;i++){
		if (JC_Scripts[i].getAttribute('src')){
			var q=JC_Scripts[i].getAttribute('src');
			
			if(q.match('jcupload.js')){
				var url = q.split('jcupload.js');
				
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
document.write('<link rel="stylesheet" type="text/css" href="'+JC_ScriptDir+'/jcupload.css" />');
document.write('<script type="text/javascript" src="'+JC_ScriptDir+'/jcuploadUI.config.js"></script>');
document.write('<script type="text/javascript" src="'+JC_ScriptDir+'/jcupload.core.js"></script>');
document.write('<script type="text/javascript" src="'+JC_ScriptDir+'/jcuploadUI.js"></script>');
document.write('<div id="jcupload_content" class="aa"></div>');

var jcu;
$(document).ready(function() {
	var conf= {
		url: "upload.php",
		auto_confirm_upload: 1,


		max_file_size: 50 * 1024, // =4MB
		max_queue_count: 50,
		max_queue_size: 50 * 1024 * 1024, // =50MB


		callback: {
			init: function(uo, jcu_version, flash_version) {
                                     
			},
			queue_upload_end: function(uo) {
					if (uo.is_upload_complete()) {
							alert("所有上传成功");
					}
			},
			error_file_size: function(uo, file_name, file_type, file_size) {
					alert("文件" +file_name +"太大");
			},
			error_queue_count: function(uo, file_name, file_type, file_size) {
					alert("文件" +file_name +"上传失败, 您每次最多只能上传50张");
			},
			error_queue_size: function(uo, file_name, file_type, file_size) {
					//alert("File " +file_name +" ignored, becouse the file queue size is too big!");
			},
			can_upload_file: function(uo, file_name, file_type, file_size) {
				/*
					if (file_name==="me.jpg") {
							alert("File me.jpg can't be added!");
							return false;
					}
					return true;
					*/
			}

		}

	};
	jcu= $.jcuploadUI(conf);
	jcu.append_to("#jcupload_content");
});
