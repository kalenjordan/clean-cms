<?php

class Clean_Cms_Adminhtml_ContentblockController extends Mage_Adminhtml_Controller_Action
{
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

    protected function _getBlockType()
    {
        if (! $this->getRequest()->getParam('type')) {
            throw new Exception("Missing type");
        }

        return $this->getRequest()->getParam('type');
    }
}