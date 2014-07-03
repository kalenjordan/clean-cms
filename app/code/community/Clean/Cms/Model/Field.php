<?php

/**
 * @method getCreatedAt()
 * @method getFieldIdentifier()
 * @method getValue()
 */
class Clean_Cms_Model_Field extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('cleancms/field');
    }
}