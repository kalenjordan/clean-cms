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
        $schema = $page->getSchemaArray();

        $form = new Clean_Cms_Model_Data_Form();
        $form->setHtmlIdPrefix('cleancms_content_blocks');
        $this->setForm($form);

        foreach ($schema as $fieldsetDefinition) {
            $this->_generateFieldset($fieldsetDefinition);
        }

        $form->simpleFieldset('new_content_block', 'Create New Content Block')
            ->simpleField('new_block_type', '', array(
                'type'      => 'select',
                'note'      => "Select a content block type to add a new content block to this page",
                'values'    => Mage::helper('cleancms')->getContentBlockTypesOptions(),
            ));

        $form->setValues($page->getData());
        return parent::_prepareForm();
    }

    protected function _addCounterToFieldName($fieldName)
    {
        $fullFieldName = $fieldName . '_' . $this->_currentFieldNumber;
        $this->_currentFieldNumber++;
        return $fullFieldName;
    }

    protected function _generateFieldset($definition)
    {
        if (!isset($definition['type'])) {
            throw new Exception("Missing 'type' in fieldset definition");
        }

        $fieldType = Mage::helper('cleancms')->getType($definition['type']);
        $fieldset = $this->getForm()->simpleFieldset($definition['type'], $fieldType['name']);
        $fieldset->simpleField($this->_addCounterToFieldName('sort_order'), 'Sort Order');

        $fields = Mage::helper('cleancms')->getFieldsForType($definition['type']);
        foreach ($fields as $fieldIdentifier => $fieldConfig) {
            $fieldset->simpleField($this->_addCounterToFieldName($fieldIdentifier), null, $fieldConfig);
        }
    }
}