<?php

class MGS_Contactpro_Model_Observers extends Mage_Core_Model_Abstract
{
	protected $_data ;
	
    public function saveconfig()
    {
        $collection = Mage::getModel('contactpro/contactprofield')->getCollection()->load();	
		$data = Mage::app()->getRequest()->getPost();
		$this->_data = $data;
		//echo "<pre>";print_r($data); die('dsfds');
		foreach($this->_data['contactprofieldid'] as $index => $item){
			if($index == 0 ) continue;
			
			$this->saveItem($index);
		}
		foreach($collection as $field){
			$delete = true;
			foreach($data['contactprofieldid'] as $id){
				if($field->getContactProFieldId() == $id){
					$delete = false;
					break;
				}
			}
			if($delete){
				$this->deleteField($field->getContactProFieldId());
			}
		}
    }
	
	public function saveItem($index){
		$model = Mage::getModel('contactpro/contactprofield');	
		$id 							= (int)$this->_data['contactprofieldid'][$index];
		$data							= array(); 
		$data['label']					= (string)$this->_data['label'][$index];
		$data['required']				= (int)$this->_data['required'][$index];
		$data['position']				= (int)$this->_data['position'][$index];
		$data['status']					= (int)$this->_data['status'][$index];
		if($data['label']== ''){
			if($id>0){
				$this->deleteField($id);
			}else{
				return;
			}
		}
		if($id!=0){
			$model->load($id)->addData($data);
			try{
				$model->setId($id)->save();
			} catch (Exception $e){
			}
		}else{
			$model->setData($data);
			try{
				$model->save();
			} catch (Exception $e){
				
			}
		}
	}
	
	public function deleteField($id){
		$model = Mage::getModel('contactpro/contactprofield');	
		try {
			$model->setId($id)->delete();
		} catch 	(Exception $e){
			//echo $e->getMessage();
		}
	}
}