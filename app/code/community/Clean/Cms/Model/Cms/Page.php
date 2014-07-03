<?php

class Clean_Cms_Model_Cms_Page extends Mage_Cms_Model_Page
{
    public function getFieldsets()
    {
        $fieldsets = Mage::getResourceModel('cleancms/fieldset_collection');
        $fieldsets->addFieldToFilter('page_id', $this->getId())
            ->setOrder('sort_order', 'ASC');

        return $fieldsets;
    }

    public function getFormValues()
    {
        $values = array();
        $fieldsets = $this->getFieldsets();

        /** @var $fieldset Clean_Cms_Model_Fieldset */
        foreach ($fieldsets as $fieldset) {
            $fields = $fieldset->getFields();
            $values['fieldset' . $fieldset->getId() . '_sort_order'] = $fieldset->getSortOrder();

            /** @var $field Clean_Cms_Model_Field */
            foreach ($fields as $field) {
                $values[$this->_getFieldKey($fieldset, $field)] = $field->getValue();
            }
        }

        return $values;
    }

    /**
     * @param $fieldset Clean_Cms_Model_Fieldset
     * @param $field Clean_Cms_Model_Field
     */
    protected function _getFieldKey($fieldset, $field)
    {
        return 'fieldset' . $fieldset->getId() . '_' . $field->getFieldIdentifier();
    }
}