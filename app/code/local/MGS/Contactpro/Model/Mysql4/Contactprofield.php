<?php

class MGS_Contactpro_Model_Mysql4_Contactprofield extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the contactpro_id refers to the key field in your database table.
        $this->_init('contactpro/contactprofield', 'contact_pro_field_id');
    }
}