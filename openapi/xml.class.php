<?php
/**
 * ilau 用户API程序
 * 
 *  数组转为xml输出
 * 
 * @author weego
 */

class array2xml {
        var $xml;
        function array2xml($array,$encoding='gb2312') {
                $this->xml='<?xml version="1.0" encoding="'.$encoding.'"' . "?>\r\n";
				$this->xml.="<erongdu>\r\n";
                $this->xml.=$this->_array2xml($array);
				$this->xml.="</erongdu>";
                
        }
        function getXml() {
                return $this->xml;
        }
        function _array2xml($array) {
                foreach($array as $key=>$val) {
                        is_numeric($key)&&$key="item id=\"$key\"";
						$needcdata = array("title", "description", "content");
						//如果需要加保护标签
						if($key=='addtime'){
							$val=date('Y-m-d H:i:s',$val);
						}
						if(in_array($key,$needcdata)){
							$xml.="<$key><![CDATA[";
							$xml.=is_array($val)?$this->_array2xml($val):$val;
							list($key,)=explode(' ',$key);
							$xml.="]]></$key>"."\r\n";
						}else{
							$xml.="<$key>";
							$xml.=is_array($val)?$this->_array2xml($val):$val;
							list($key,)=explode(' ',$key);
							$xml.="</$key>"."\r\n";
						}

                }
                return $xml;
        }
}

    
?>
