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

        $fields = Mage::getResourceModel('cleancms/field_collection');
        $fields->addFieldToFilter('fieldset_id', $this->getId());

        $this->_fields = $fields;
        return $this->_fields;
    }

    public function fieldIdentifier($fieldName)
    {
        return 'fieldset' . $this->getId() . '_' . $fieldName;
    }

    /**
     * @param $fieldIdentifier
     * @return Clean_Cms_Model_Field
     */
    public function loadFieldByIdentifier($fieldIdentifier)
    {
        $field = $this->getFields()
            ->addFieldToFilter('field_identifier', $fieldIdentifier)
            ->getFirstItem();

        return $field;
    }
}