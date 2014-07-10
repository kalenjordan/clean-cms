<?php

/**
 * @method getCreatedAt()
 * @method getSortOrder()
 * @method getType()
 * @method Clean_Cms_Model_Fieldset load($id, $field = null)
 * @method Clean_Cms_Model_Fieldset setData($key, $val = null)
 */
class Clean_Cms_Model_Fieldset extends Mage_Core_Model_Abstract
{
    protected $_fields;

    public function _construct()
    {
        parent::_construct();
        $this->_init('cleancms/fieldset');
    }

    public function getFields()
    {
        if (isset($this->_fields)) {
            return $this->_fields;
        }

        $this->_fields = $this->fetchFields();
        return $this->_fields;
    }

    public function fetchFields()
    {
        $fields = Mage::getResourceModel('cleancms/field_collection');
        $fields->addFieldToFilter('fieldset_id', $this->getId());

        return $fields;
    }

    public function getFieldValues()
    {
        $values = array();

        /** @var $field Clean_Cms_Model_Field */
        foreach ($this->getFields() as $field) {
            $values[$field->getFieldIdentifier()] = $field->getValue();
        }

        return $values;
    }

    public function fieldIdentifier($fieldName)
    {
        return 'fieldset_' . $this->getId() . '_' . $fieldName;
    }

    /**
     * @param $fieldIdentifier
     * @return Clean_Cms_Model_Field
     */
    public function loadFieldByIdentifier($fieldIdentifier)
    {
        $field = $this->fetchFields()
            ->addFieldToFilter('field_identifier', $fieldIdentifier)
            ->getFirstItem();

        return $field;
    }

    public function toHtml()
    {
        $fieldsetConfig = Mage::helper('cleancms')->getFieldsetTypeData($this->getType());
        $block = $this->_getBlock($fieldsetConfig);

        return $block->toHtml();
    }

    protected function _getBlock($fieldsetConfig)
    {
        if (!isset($fieldsetConfig['template'])) {
            throw new Exception("Fieldset is missing template in fieldset config");
        }

        if (!isset($fieldsetConfig['block'])) {
            throw new Exception("Fieldset is missing block in fieldset config");
        }

        $blockClassAlias = $fieldsetConfig['block'];
        $template = $fieldsetConfig['template'];

        /** @var Clean_Cms_Block_Page_Fieldset $block */
        $block = Mage::app()->getLayout()->createBlock($blockClassAlias);
        $block->setTemplate($template);
        $block->setFieldset($this);

        return $block;
    }

    protected function _afterDelete()
    {
        /** @var $field Clean_Cms_Model_Field */
        foreach ($this->getFields() as $field) {
            $field->delete();
        }

        return parent::_afterDelete();
    }
}