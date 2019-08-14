<?php

class MGS_Contactpro_Model_Contactprofield extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('contactpro/contactprofield');
    }
}