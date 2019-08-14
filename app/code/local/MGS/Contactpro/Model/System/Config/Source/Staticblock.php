<?php
class MGS_Contactpro_Model_System_Config_Source_Staticblock
{
    public function toOptionArray(){
		
		$result 	= array();
		
		$result[] 	= array('value' => '', 
			'label' => Mage::helper('contactpro')->__(' --- Select static block --- ')
		);
		
		$blocks 	= Mage::getModel('cms/block')->getCollection()->load();
        foreach ($blocks as $block) {
			if($block && $block->getIsActive()){
				$result[] =	array('value' => $block->getIdentifier(),
					'label' => $block->getTitle()
				);
			}
        }
        
		return $result;
	}
}
