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
            $values['fieldset_' . $fieldset->getId() . '_sort_order'] = $fieldset->getSortOrder();
            $values['fieldset_' . $fieldset->getId() . '_css_classes'] = $fieldset->getData('css_classes');

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
        return 'fieldset_' . $fieldset->getId() . '_' . $field->getFieldIdentifier();
    }

    public function saveFields($fields)
    {
        foreach ($fields as $fullFieldIdentifier => $value) {
            $fieldModel = $this->_getFieldModel($fullFieldIdentifier);

            if ($fieldModel->getId()) {
                $fieldModel->setData('value', $value)->save();
            } else {
                $fieldIdentifier = $this->_getFieldIdentifier($fullFieldIdentifier);
                $fieldsetModel = $this->_getFieldsetModel($fullFieldIdentifier);
                $fieldsetModel->setData($fieldIdentifier, $value)->save();
            }
        }
    }

    public function saveFiles($files)
    {
        foreach ($files['name'] as $fileFieldName => $uploadedFileName) {
            if ($uploadedFileName) {
                $uploadedFilePath = Mage::helper('cleancms/upload')->saveUploadedFile($fileFieldName);
                $fieldModel = $this->_getFieldModel($fileFieldName);
                $fieldModel->setData('value', $uploadedFilePath)->save();
            }
        }
    }

    protected function _removeFieldsetPrefix($fullFieldIdentifier)
    {
        $parts = explode('_', $fullFieldIdentifier);
        array_shift($parts);
        return implode('_', $parts);
    }

    protected function _getFieldsetId($fullFieldIdentifier)
    {
        $parts = explode('_', $fullFieldIdentifier);
        $fieldsetId = (int)$parts[1];

        return $fieldsetId;
    }

    protected function _getFieldIdentifier($fullFieldIdentifier)
    {
        $parts = explode('_', $fullFieldIdentifier);
        array_shift($parts);
        array_shift($parts);
        $fieldIdentifier = implode('_', $parts);

        return $fieldIdentifier;
    }

    /**
     * @param $fullFieldIdentifier
     */
    protected function _getFieldModel($fullFieldIdentifier)
    {
        $fieldset = $this->_getFieldsetModel($fullFieldIdentifier);
        $fieldIdentifier = $this->_getFieldIdentifier($fullFieldIdentifier);
        $field = $fieldset->loadFieldByIdentifier($fieldIdentifier);

        return $field;
    }

    protected function _getFieldsetModel($fullFieldIdentifier)
    {
        $fieldsetId = $this->_getFieldsetId($fullFieldIdentifier);
        $fieldset = Mage::getModel('cleancms/fieldset')->load($fieldsetId);

        return $fieldset;
    }

    // todo only if there are content blocks - or maybe add a dropdown even better.
    public function hasContentBlocks()
    {
        $fieldsets = $this->getFieldsets();
        if ($fieldsets->getSize()) {
            return true;
        } else {
            return false;
        }
    }
}