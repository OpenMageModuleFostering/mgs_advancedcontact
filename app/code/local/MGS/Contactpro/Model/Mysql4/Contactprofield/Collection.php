<?php

class MGS_Contactpro_Model_Mysql4_Contactprofield_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('contactpro/contactprofield');
    }
}