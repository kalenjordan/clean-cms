<?php

class Clean_Cms_Block_Adminhtml_Page_Edit_Form extends Mage_Adminhtml_Block_Cms_Page_Edit_Form
{
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $form = $this->getForm();
        $form->setData('enctype', 'multipart/form-data');

        return $this;
    }
}
