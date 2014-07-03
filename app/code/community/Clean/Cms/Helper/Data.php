<?php

class Clean_Cms_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return Clean_Cms_Model_Cms_Page
     */
    public function currentCmsPage()
    {
        $page = Mage::registry('cms_page');
        if (! $page || ! ($page instanceof Mage_Cms_Model_Page)) {
            return new Clean_Cms_Model_Cms_Page();
        }

        $cleanPage = new Clean_Cms_Model_Cms_Page();
        $cleanPage->setData($page->getData());

        return $cleanPage;
    }

    public function getContentBlockTypes()
    {
        $types = $this->getContentBlockTypesXmlAsArray();
    }

    public function getContentBlockTypesOptions()
    {
        $types = $this->getContentBlockTypesXmlAsArray();
        $options = array(array(
            'value' => 0,
            'label' => "Create New Field Set",
        ));
        foreach ($types as $typeIdentifier => $type) {
            $options[] = array(
                'value' => $typeIdentifier,
                'label' => isset($type['name']) ? $type['name'] : "(Missing Name)",
            );
        }

        return $options;
    }

    public function getContentBlockTypesXmlAsArray()
    {
        $xml = Mage::getConfig()->getNode('cleancms/block_types');
        if (! $xml) {
            return array();
        }

        return $xml->asArray();
    }
}