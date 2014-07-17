<?php

class Clean_Cms_Block_Page_Fieldset extends Mage_Core_Block_Template
{
    /** @var  Clean_Cms_Model_Fieldset */
    protected $_fieldset;

    public function setFieldValues($fieldValues)
    {
        $this->_fieldValues = $fieldValues;
    }

    public function getFieldValue($identifier)
    {
        $fieldValues = $this->getFieldset()->getFieldValues();
        $value = isset($fieldValues[$identifier]) ? $fieldValues[$identifier] : "";

        return $value;
    }

    public function markdownToHtml($markdown)
    {
        return Mage::helper('cleancms/markdown')->toHtml($markdown);
    }

    public function setFieldset($fieldset)
    {
        $this->_fieldset = $fieldset;
        return $this;
    }

    public function getFieldset()
    {
        return $this->_fieldset;
    }

    public function getCssClasses()
    {
        $fieldsetType = $this->getFieldsetType();
        $fieldsetTypeUsingDashes = str_replace('_', '-', $fieldsetType);
        $mainTypeClass = "cleancms-" . $fieldsetTypeUsingDashes;
        $allClasses = $mainTypeClass . ' ' . $this->getFieldset()->getData('css_classes');

        return $allClasses;
    }

    public function getFieldsetHtmlId()
    {
        return "cleancms-fieldset-" . $this->getFieldset()->getId();
    }

    public function getFieldsetType()
    {
        return $this->getFieldset()->getType();
    }
}