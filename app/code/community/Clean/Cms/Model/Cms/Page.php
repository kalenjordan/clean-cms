<?php

class Clean_Cms_Model_Cms_Page extends Mage_Cms_Model_Page
{
    public function getSchemaArray()
    {
        $schemaJson = $this->getData('cleancms_schema');
        if (! $schemaJson) {
            return array();
        }

        $schema = json_decode($schemaJson, true);
        if (! $schema) {
            throw new Exception("Problem decoding the page schema: " . print_r($schemaJson));
        }

        return $schema;
    }
}