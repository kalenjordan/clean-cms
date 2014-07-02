<?php

class Clean_Cms_Block_Adminhtml_Content
    extends Mage_Adminhtml_Block_Template
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
}