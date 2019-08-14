<?php
class MGS_Contactpro_IndexController extends Mage_Core_Controller_Front_Action
{
	protected $_data;
    public function indexAction()
    {
		$helper = Mage::helper('contactpro');
		if(!$helper->getEnable()){
			$this->_redirect('/');
			return;
		}
		
		// $emailSender	 	= (string)Mage::getStoreConfig('contactpro/settings/sender_email',$this->_getStore());
		// $emailSenderName 	= (string)Mage::getStoreConfig('trans_email/ident_'.$emailSender.'/name', $this->_getStore());
		// $emailSenderEmail 	= (string)Mage::getStoreConfig('trans_email/ident_'.$emailSender.'/email', $this->_getStore());
		
		// echo $emailSenderName . '<br/>' .$emailSenderEmail;die();
		
		$this->loadLayout();     
		$this->renderLayout();
    }
	
	protected function _getStore(){
		$store = Mage::app()->getStore();
		return $store;
	}
	
	public function postAction(){
		
		$session 			= Mage::getSingleton('core/session');
		$data 				= Mage::app()->getRequest()->getPost();
		$this->_data 		= $data;
		
		$validate = $this->validate();
		if($validate === true){
			$session->addSuccess($this->__('Contact has been accepted for moderation.'));	
			$this->sendEmail();
			$pageThanks = Mage::getStoreConfig('contactpro/settings/page_thanks',$this->_getStore());
			$this->_redirect($pageThanks);
		}else{
			if (is_array($validate)) {
				foreach ($validate as $errorMessage) {
					$session->addError($errorMessage);
				}
			} else {
				$session->addError($this->__('Unable to post the contact.'));
			}
			$this->_redirect('contactpro');
		}	
	}
	
	public function validate(){
		$errors = array();
		$helper = Mage::helper('contactpro');
		
		//Check validate Full Name
		if (!Zend_Validate::is($this->_data['full-name'], 'NotEmpty')) {
			$errors[] = $helper->__('Full Name can\'t be empty');
		}
		
		//Check validate Email
		if(!Zend_Validate::is((string)$this->_data['email'], 'EmailAddress'))
		{
			$errors[] = $helper->__('Email not validate');
		}
		
		//Check validate Option
		if(count($this->getFields())){
			foreach($this->getFields() as $field){
				if($field->getRequired() == 1){
					if(!Zend_Validate::is((string)$this->_data[$helper->getCode($field->getLabel())], 'NotEmpty'))
					{
						$errors[] = $helper->__($field->getLabel() . ' can\'t be empty');
					}
				}
			}
		}
		
		//Check validate Comment
		if (!Zend_Validate::is((string)$this->_data['comment'], 'NotEmpty')) {
			$errors[] = $helper->__('Comment can\'t be empty');
		}
		
		if(empty($errors)) {
            return true;
        }
        return $errors;
	}
	
	protected function getFields(){
		$collection = Mage::getModel('contactpro/contactprofield')
					->getCollection()
					->setOrder('position', 'asc')
					->addFilter('status', 1)
					->load();
		return $collection;
	}
	
	
	protected function sendEmail(){
		$helper	 			= Mage::helper('contactpro');
		$emailAdmin			= (string)Mage::getStoreConfig('contactpro/settings/send_email_to',$this->_getStore());
		$emailSender	 	= (string)Mage::getStoreConfig('contactpro/settings/sender_email',$this->_getStore());
		$emailSenderName 	= (string)Mage::getStoreConfig('trans_email/ident_'.$emailSender.'/name', $this->_getStore());
		$emailSenderEmail 	= (string)Mage::getStoreConfig('trans_email/ident_'.$emailSender.'/email', $this->_getStore());
		
		$options = '';
		if(count($this->getFields())){
			foreach($this->getFields() as $field){
				$options .= '<li style="list-style:none inside; padding:0 0 0 10px;"> '. $field->getLabel() . ': '. $this->_data[$helper->getCode($field->getLabel())] . '</li>';
			}
		}
		
		$emailTemplateVariables = array();
		$emailTemplateVariables['fullname'] 		= $this->_data['full-name'];
		$emailTemplateVariables['emailaddress'] 	= $this->_data['email'];
		$emailTemplateVariables['options'] 			= $options;
		$emailTemplateVariables['comment'] 			= $this->_data['comment'];
		$emailTemplateVariables['store'] 			= Mage::getUrl();
		$emailTemplateVariables['storename'] 		= Mage::getStoreConfig('design/head/default_title',$this->_getStore());
		$emailTemplateVariables['subjectmail'] 		= (string)Mage::getStoreConfig('contactpro/settings/subject_mail',$this->_getStore());
		$emailTemplateVariables['nameadmin'] 		= $emailSenderName;
		
		//send mail customer
		$emailTemplate  = Mage::getModel('core/email_template')->loadDefault('contact_pro');
		$emailTemplate->getProcessedTemplate($emailTemplateVariables);
		
		$emailTemplate->setSenderName($emailSenderName);
		$emailTemplate->setSenderEmail($emailSenderEmail);

		$emailTemplate->send($this->_data['email'],'hello',$emailTemplateVariables);
		
		//send mail admin
		$emailTemplate  = Mage::getModel('core/email_template')->loadDefault('contact_pro_admin');
		$emailTemplate->getProcessedTemplate($emailTemplateVariables);
		
		$emailTemplate->setSenderName($emailTemplateVariables['storename']);
		$emailTemplate->setSenderEmail($this->_data['email']);

		$emailAdmin = explode(";",$emailAdmin);
		foreach($emailAdmin as $email){
			$emailTemplate->send(trim($email),'',$emailTemplateVariables);
		}
	}
}