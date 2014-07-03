<?php

class Clean_Cms_Model_Mysql4_Field extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('cleancms/field', 'field_id');
    }
}