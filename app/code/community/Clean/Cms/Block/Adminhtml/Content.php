<?php

/**
 * Class Clean_Cms_Block_Adminhtml_Content
 *
 * @method Clean_Cms_Model_Data_Form getForm()
 */
class Clean_Cms_Block_Adminhtml_Content
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_currentFieldNumber = 1;

    public function getTabLabel()
    {
        return Mage::helper('cleancms')->__('Content Blocks');
    }

    public function getTabTitle()
    {
        return Mage::helper('cleancms')->__('Content Blocks');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {
        $page = Mage::helper('cleancms')->currentCmsPage();

        $form = new Clean_Cms_Model_Data_Form();
        $form->setHtmlIdPrefix('cleancms_content_blocks');
        $this->setForm($form);

        $fieldsets = $page->getFieldsets();
        foreach ($fieldsets as $fieldset) {
            $this->_generateFieldset($fieldset);
        }

        $form->simpleFieldset('new_content_block', 'Create New Content Block')
            ->simpleField('new_block_type', '', array(
                'type'      => 'select',
                'note'      => "Select a content block type to add a new content block to this page",
                'values'    => Mage::helper('cleancms')->getContentBlockTypesOptions(),
            ));

        $form->setValues($page->getFormValues());
        return parent::_prepareForm();
    }

    /**
     * @param $fieldsetModel Clean_Cms_Model_Fieldset
     * @throws Exception
     */
    protected function _generateFieldset($fieldsetModel)
    {
        if (! $fieldsetModel->getType()) {
            throw new Exception("Missing 'type' in fieldset definition");
        }
        $type = $fieldsetModel->getType();

        $fieldTypeData = Mage::helper('cleancms')->getFieldsetTypeData($type);
        $fieldset = $this->getForm()->simpleFieldset($type, $fieldTypeData['name']);

        $fieldset->simpleField($fieldsetModel->fieldIdentifier('sort_order'), 'Sort Order');

        $fields = Mage::helper('cleancms')->getFieldsForType($type);
        foreach ($fields as $fieldIdentifier => $fieldConfig) {
            $fullFieldIdentifier = $fieldsetModel->fieldIdentifier($fieldIdentifier);
            $fieldset->simpleField($fullFieldIdentifier, null, $fieldConfig);
        }
    }
}