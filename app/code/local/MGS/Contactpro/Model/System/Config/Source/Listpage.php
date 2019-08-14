<?php
class MGS_Contactpro_Model_System_Config_Source_Listpage
{
    public function toOptionArray()
    {
		$pages = Mage::getModel('cms/page')->getCollection()->load();
		
		$pageSelect = array();
		foreach($pages as $page){
			$pageSelect[] = array('value'=>$page->getIdentifier(), 'label'=>$page->getTitle());
		}
		return $pageSelect;
    }
}
