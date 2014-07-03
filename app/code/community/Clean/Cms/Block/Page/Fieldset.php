<?php

class Clean_Cms_Block_Page_Fieldset extends Mage_Core_Block_Template
{
    protected $_fieldValues = array();

    public function setFieldValues($fieldValues)
    {
        $this->_fieldValues = $fieldValues;
    }

    public function getFieldValue($fieldIdentifier)
    {
        return isset($this->_fieldValues[$fieldIdentifier]) ? $this->_fieldValues[$fieldIdentifier] : "(No value for $fieldIdentifier found)";
    }

    public function markdownToHtml($markdown)
    {
        return Mage::helper('cleancms/markdown')->toHtml($markdown);
    }
}