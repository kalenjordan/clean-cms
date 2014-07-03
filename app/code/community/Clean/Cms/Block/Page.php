<?php

class Clean_Cms_Block_Page extends Mage_Cms_Block_Page
{
    /** @var Clean_Cms_Model_Cms_Page */
    protected $_cleanPage;

    protected function _getCleanPage()
    {
        if (isset($this->_cleanPage)) {
            return $this->_cleanPage;
        }

        $this->_cleanPage = new Clean_Cms_Model_Cms_Page();
        $this->_cleanPage->setData($this->getPage()->getData());

        return $this->_cleanPage;
    }

    protected function _toHtml()
    {
        if ($this->_getCleanPage()->hasContentBlocks()) {
            return $this->_contentBlocksHtml();
        } else {
            return parent::_toHtml();
        }
    }

    protected function _contentBlocksHtml()
    {
        $cleanPage = $this->_getCleanPage();
        $fieldsets = $cleanPage->getFieldsets();

        // todo move this to a template file (should be ashamed of myself)
        $html = "<div class='clean-v2'>";

        /** @var $fieldset Clean_Cms_Model_Fieldset */
        foreach ($fieldsets as $fieldset) {
            $html .= $fieldset->toHtml();
        }

        $html .= "</div>";

        return $html;
    }
}