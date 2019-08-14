<?php
Zend_Session::start();
class MGS_Contactpro_CaptchaController extends Mage_Core_Controller_Front_Action
{
    public function renderAction()
    {
		$path 	= Mage::getBaseDir('media') . DS . 'contactpro' . DS ;
		$url 	= Mage::getUrl('media',array('_store' => (int)Mage::app()->getStore())) . 'contactpro/captcha/';
		
		$captcha = new Zend_Captcha_Image();
		$captcha->setImgDir($path . 'captcha' . DS);
		$captcha->setImgUrl($url);
		$captcha->setFont($path . 'fonts' . DS . 'captcha.ttf');
		$captcha->setWidth(255);
		$captcha->setHeight(100);
		$captcha->setWordlen(6);
		$captcha->setFontSize(60);
		$captcha->setLineNoiseLevel(3);
		$captcha->generate();
		
		Mage::getSingleton('core/session')->setCaptchaWord($captcha->getWord());
		echo $captcha->render($this, null); 
    }
	
	public function checkAction(){
		$word 	= (string)Mage::getSingleton('core/session')->getCaptchaWord();
		$data   = Mage::app()->getRequest()->getPost();
		if($data['captcha'] == $word){
			$result = array('result' => 1);
		}else{
			$result = array('result' => 0);
		}
		echo json_encode($result);
	}
}