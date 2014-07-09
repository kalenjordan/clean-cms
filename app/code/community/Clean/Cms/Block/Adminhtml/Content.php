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
        $form->setData('enctype', 'multipart/form-data');
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
                'onchange'  => $this->_getNewBlockTypeOnChange(),
            ));

        $form->setValues($page->getFormValues());
        return parent::_prepareForm();
    }

    protected function _getNewBlockTypeOnChange()
    {
        $url = $this->getUrl('*/contentblock/new', array('page_id' => $this->getRequest()->getParam('page_id')));
        $html = "window.location.href = '$url" . "type/' + $('cleancms_content_blocksnew_block_type').value;";
        return $html;
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
        $fieldset = $this->getForm()->simpleFieldset($type . rand(), $fieldTypeData['name'] . " (ID: " . $fieldsetModel->getId() . ")");
        $fieldset->simpleField($fieldsetModel->fieldIdentifier('sort_order'), 'Sort Order', array(
            'name_wrapper' => 'cleancms'
        ));
        $fieldset->simpleField($fieldsetModel->fieldIdentifier('css_classes'), 'CSS Classes', array(
            'name_wrapper' => 'cleancms'
        ));

        $fields = Mage::helper('cleancms')->getFieldsForType($type);
        foreach ($fields as $fieldIdentifier => $fieldConfig) {
            $fieldModel = $fieldsetModel->loadFieldByIdentifier($fieldIdentifier);
            $fieldConfig = $this->_prepareFieldConfig($fieldModel, $fieldConfig);
            $fullFieldIdentifier = $fieldsetModel->fieldIdentifier($fieldIdentifier);
            $fieldset->simpleField($fullFieldIdentifier, null, $fieldConfig);
        }
    }

    /**
     * @param $fieldModel Clean_Cms_Model_Field
     * @param $fieldConfig
     */
    protected function _prepareFieldConfig($fieldModel, $fieldConfig)
    {
        $fieldConfig['name_wrapper'] = 'cleancms';
        if ($fieldConfig['type'] == 'file') {
            $fieldConfig['note'] = $fieldModel->getValue();
        }

        return $fieldConfig;
    }
}