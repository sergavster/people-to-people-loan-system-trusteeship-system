<?php
/**
 * keke 用户API程序
 * 
 *  gbk转码
 * 
 * @author weego
 */

class goiconv {
        var $xml;
        function goiconv($array) {
           
                $this->xml=$this->_goiconv($array);
		
                
        }
        function geticonv() {
                return $this->xml;
        }
        function _goiconv($array) {
                foreach($array as $key=>$val) {
                        
						
						$xml[$key]=is_array($val)?$this->_goiconv($val):iconv("GBK","UTF-8",$val);
						 
                }
                return $xml;
        }
}

    
?>
