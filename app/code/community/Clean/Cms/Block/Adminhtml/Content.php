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

    /**
     * @return Mage_Cms_Model_Page
     */
    protected function _getPage()
    {
        $page = Mage::registry('cms_page');
        if (! $page || ! ($page instanceof Mage_Cms_Model_Page)) {
            throw new Exception("Wasn't able to get the cms_page off the registry");
        }

        return $page;
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
        $fieldsetLabel = $fieldTypeData['name'] . " (ID: " . $fieldsetModel->getId() . ")";
        $fieldset = $this->getForm()->simpleFieldset($type . '_' . $fieldsetModel->getId(), $fieldsetLabel, array(
            'class' => 'cleancms-draggable fieldset-wide',
        ));

        $fieldset->simpleField($fieldsetModel->fieldIdentifier('sort_order'), 'Sort Order', array(
            'type'          => 'hidden',
            'name_wrapper'  => 'cleancms',
            'class'         => 'sort-order',
        ));
        $fieldset->simpleField($fieldsetModel->fieldIdentifier('css_classes'), 'CSS Classes', array(
            'name_wrapper' => 'cleancms',
            'note'          => "
                <a href='" . $this->_getDeleteFieldsetUrl($fieldsetModel) . "'>Delete this fieldset</a>
                <a href='" . $this->_getDuplicateFieldsetUrl($fieldsetModel) . "'>Duplicate</a>
            ",
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
     * @param $fieldsetModel Clean_Cms_Model_Fieldset
     */
    protected function _getDeleteFieldsetUrl($fieldsetModel)
    {
        $params = array(
            'fieldset_id' => $fieldsetModel->getId(),
            'page_id' => $this->_getPage()->getId(),
        );
        return $this->getUrl('*/contentblock/deleteFieldset', $params);
    }

    /**
     * @param $fieldsetModel Clean_Cms_Model_Fieldset
     */
    protected function _getDuplicateFieldsetUrl($fieldsetModel)
    {
        $params = array(
            'fieldset_id' => $fieldsetModel->getId(),
            'page_id' => $this->_getPage()->getId(),
        );
        return $this->getUrl('*/contentblock/duplicateFieldset', $params);
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

    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        $html .= "
            <script>
                require(['cleancms/cleancms'], function(cleancms) {
                    cleancms.run();
                });
            </script>
        ";

        return $html;
    }
}