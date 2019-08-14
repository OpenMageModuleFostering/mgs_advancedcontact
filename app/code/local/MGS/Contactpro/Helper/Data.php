<?php

class MGS_Contactpro_Helper_Data extends MGS_Mgscore_Helper_Data
{
	public function getCode($str){
		$str = strtolower($str);
		$str = str_ireplace(" ", "-", $str);
		return $str;
	}	
	
	public function getEnable(){
		$enable = Mage::getStoreConfig('contactpro/settings/enable',Mage::app()->getStore());
		
		if($enable == 1){
			return true;
		}else{
			return false;
		}
	}
        
        public function useCaptcha() {
            return Mage::getStoreConfig('contactpro/settings/use_captcha');
        }
}