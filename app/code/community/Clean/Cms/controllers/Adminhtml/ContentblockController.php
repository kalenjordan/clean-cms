<?php

class Clean_Cms_Adminhtml_ContentblockController extends Mage_Adminhtml_Controller_Action
{
    /** @var Clean_Cms_Model_Fieldset */
    protected $_fieldset;

    public function newAction()
    {
        try {
            $this->_newAction();
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->getResponse()->setRedirect($this->_getRefererUrl());
        }

        return $this;
    }

    protected function _newAction()
    {
        $pageId = $this->_getPageId();
        $type = $this->_getBlockType();
        $fieldset = Mage::getModel('cleancms/fieldset');
        $fieldset->setData('page_id', $pageId)
            ->setData('type', $type)
            ->setData('created_at', now())
            ->save();

        $this->_createFields($fieldset);

        Mage::getSingleton('adminhtml/session')->addSuccess("Created new fieldset");
        $this->getResponse()->setRedirect($this->_getRefererUrl());

        return $this;
    }

    public function deleteFieldsetAction()
    {
        try {
            $this->_deleteFieldset();
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->getResponse()->setRedirect($this->_getRefererUrl());
        }

        return $this;
    }

    protected function _deleteFieldset()
    {
        $fieldset = $this->_getFieldset();
        $fieldsetType = $fieldset->getType();
        $fieldset->delete();

        Mage::getSingleton('adminhtml/session')->addSuccess("Deleted fieldset: " . $fieldsetType);
        $this->getResponse()->setRedirect($this->_getRefererUrl());

        return $this;
    }

    public function duplicateFieldsetAction()
    {
        try {
            $this->_duplicateFieldset();
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->getResponse()->setRedirect($this->_getRefererUrl());
        }

        return $this;
    }

    protected function _duplicateFieldset()
    {
        $fieldset = $this->_getFieldset();
        $fieldsetType = $fieldset->getType();
        $fields = $fieldset->getFields();

        $fieldset->unsetData('fieldset_id');
        $fieldset->setData('sort_order', $fieldset->getSortOrder() + 1);
        $fieldset->save();
        $newFieldsetId = $fieldset->getId();

        /** @var $field Clean_Cms_Model_Field */
        foreach ($fields as $field) {
            $field->unsetData('field_id')
                ->setData('fieldset_id', $newFieldsetId);
            $field->save();
        }

        Mage::getSingleton('adminhtml/session')->addSuccess("Duplicated fieldset: " . $fieldsetType);
        $this->getResponse()->setRedirect($this->_getRefererUrl());

        return $this;
    }

    /**
     * @param $fieldset Clean_Cms_Model_Fieldset
     */
    protected function _createFields($fieldset)
    {
        $fieldTypes = Mage::helper('cleancms')->getFieldsForType($fieldset->getType());
        foreach ($fieldTypes as $fieldIdentifier => $fieldTypeData) {
            $field = Mage::getModel('cleancms/field')
                ->setData('page_id', $this->_getPageId())
                ->setData('fieldset_id', $fieldset->getId())
                ->setData('field_identifier', $fieldIdentifier)
                ->setData('created_at', now())
                ->save();
        }

        return $this;
    }

    protected function _getPageId()
    {
        $pageId = $this->getRequest()->getParam('page_id');
        if (! $pageId) {
            throw new Exception("Missing page_id");
        }

        return $pageId;
    }

    protected function _getFieldset()
    {
        if (isset($this->_fieldset)) {
            return $this->_fieldset;
        }

        $fieldsetId = $this->getRequest()->getParam('fieldset_id');
        if (! $fieldsetId) {
            throw new Exception("Missing fieldset_id");
        }

        $fieldset = Mage::getModel('cleancms/fieldset')->load($fieldsetId);
        if (! $fieldset->getId()) {
            throw new Exception("Wasn't able to load fieldset by id: " . $fieldset->getId());
        }

        $this->_fieldset = $fieldset;
        return $this->_fieldset;
    }

    protected function _getBlockType()
    {
        if (! $this->getRequest()->getParam('type')) {
            throw new Exception("Missing type");
        }

        return $this->getRequest()->getParam('type');
    }
}