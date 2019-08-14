<?php
class MGS_Contactpro_Block_Contactpro extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		$headBlock = $this->getLayout()->getBlock('head');
		$headBlock->setTitle($this->__('Contact us'));
		return parent::_prepareLayout();		
    }
	public function getFields(){
		$collection = Mage::getModel('contactpro/contactprofield')
					->getCollection()
					->setOrder('position', 'asc')
					->addFilter('status', 1)
					->load();
		return $collection;
	}
	
	public function getFullName(){
		if($this->helper('customer')->isLoggedIn()){
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			return $customer->getName();
		}
		return '';
	}
	
	public function getEmail(){
		if($this->helper('customer')->isLoggedIn()){
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			return $customer->getEmail();
		}
		return '';
	}
	
	public function getAction(){
		$url 	= Mage::getUrl('contactpro/index/post',array('_store' => (int)Mage::app()->getStore()));
		return $url;
	}
	
	public function getStaticBlock(){
		$staticBlockIdentifier = Mage::getStoreConfig('contactpro/settings/static_block',Mage::app()->getStore());
		if($staticBlockIdentifier == ''){
			return '';
		}else{
			return $this->getLayout()->createBlock('cms/block')->setBlockId($staticBlockIdentifier)->toHtml();
		}
	}
	public function getGmap(){
		$gmap = Mage::getStoreConfig('contactpro/settings/gmap',Mage::app()->getStore());
		if($gmap == ''){
			return '';
		}else{
			return $gmap;
		}
	}
}