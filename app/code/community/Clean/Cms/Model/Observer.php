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
        $this->_saveCleanCmsData($page);
        $this->_clearCache($page);
    }

    /**
     * @param $page Mage_Cms_Model_Page
     */
    protected  function _saveCleanCmsData($page)
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

    /**
     * @param $page Mage_Cms_Model_Page
     */
    protected function _clearCache($page)
    {
        $session = Mage::getSingleton('adminhtml/session');

        /** @var Varien_Simplexml_Element $varnishModelConfig */
        $varnishModelConfig = Mage::getConfig()->getModuleConfig('MageStack_Varnish');
        if ((string)$varnishModelConfig->active == 'true') {
            Mage::helper('varnish')->purge(array($this->_getRelativeUrl($page)));
            $session->addSuccess("Cleared Varnish for URL: " . $this->_getRelativeUrl($page));
        }

        $cacheInstance = Enterprise_PageCache_Model_Cache::getCacheInstance();
        $cacheInstance->remove($this->_getFullPageCacheId($page));
        $session->addSuccess("Cleared FPC for: " . $this->_getUrl($page));

        return $this;
    }

    /**
     * @param $page Mage_Cms_Model_Page
     * @return string
     */
    protected function _getRelativeUrl($page)
    {
        return '/' . $page->getIdentifier();
    }

    /**
     * The parameter that FPC accepts isn't the full url but only the domain + relative url
     *
     * @param $page Mage_Cms_Model_Page
     */
    protected function _getUrl($page)
    {
        $urlModel = Mage::getSingleton('core/url')->parseUrl(Mage::helper('core/url')->getCurrentUrl());
        $host = $urlModel->getData('host');
        $url = $host . $this->_getRelativeUrl($page);

        return $url;
    }

    /**
     * @param $page Mage_Cms_Model_Page
     */
    protected function _getFullPageCacheId($page)
    {
        $queryParams = array();
        $urlId = $this->_getUrl($page)  . '_' . md5(serialize($queryParams));

        $processor = new Enterprise_PageCache_Model_Processor();
        $cacheId = $processor->prepareCacheId($urlId);

        return $cacheId;
    }
}