<?php

class Clean_Cms_Model_Mysql4_Field_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('cleancms/field');
    }
}