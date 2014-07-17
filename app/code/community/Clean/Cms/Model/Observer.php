<?php

class Clean_Cms_Model_Observer extends Varien_Object
{
    /**
     * @param $observer Varien_Event_Observer
     */
    public function pageSaveBefore($observer)
    {
        $page = $observer->getData('data_object');
        if ($page && $page instanceof Mage_Cms_Model_Page) {
            $this->_beforeSavePage($page);
        }
    }

    /**
     * @param $page Mage_Cms_Model_Page
     */
    protected function _beforeSavePage($page)
    {
        $params = Mage::app()->getRequest()->getParam('cleancms');
        $cleanPage = new Clean_Cms_Model_Cms_Page();
        $cleanPage->setData($page->getData());

        if (! $params) {
            return $this;
        }

        $cleanPage->saveFields($params);
        if (isset($_FILES['cleancms'])) {
            $cleanPage->saveFiles($_FILES['cleancms']);
        }

        return $this;
    }
}