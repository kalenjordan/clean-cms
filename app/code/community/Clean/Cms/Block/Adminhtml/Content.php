<?php

class Clean_Cms_Block_Adminhtml_Content
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
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
        $model = new Varien_Object();
        $form = new Clean_Cms_Model_Data_Form();
        $this->setForm($form);

        $form->setHtmlIdPrefix('content_blocks');
        $form->simpleFieldset('markdown', 'Markdown Content')
            ->simpleField('heading');

        $form->simpleFieldset('new_content_block', 'New')
            ->simpleField('new_block_type', array(
                'type' => 'select',
                'label' => 'New',
            ));

        $form->setValues($model->getData());
        return parent::_prepareForm();
    }
}