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
        $options = array();

        $options = array(array(
            'value' => 0,
            'label' => "- Create New Field Set -",
        ));
        foreach ($types as $typeIdentifier => $type) {
            $options[] = array(
                'value' => $typeIdentifier,
                'label' => isset($type['name']) ? $type['name'] : "(Missing Name)",
            );
        }
        usort($options, function($a, $b) {
            return ($a['label'] > $b['label']);
        });


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

    public function getFieldsForType($typeIdentifier)
    {
        $type = $this->getFieldsetTypeData($typeIdentifier);
        if (! isset($type['fields'])) {
            throw new Exception("Missing fields definition for type: $typeIdentifier");
        }

        $fields = $type['fields'];
        return $fields;
    }

    public function getFieldsetTypeData($typeIdentifier)
    {
        $types = $this->getContentBlockTypesXmlAsArray();
        if (! $types || ! isset($types[$typeIdentifier])) {
            throw new Exception("Couldn't find definition for content block type: $typeIdentifier");
        }

        $type = $types[$typeIdentifier];
        return $type;
    }

}