<?php

class Clean_Cms_Model_Data_Form_Fieldset extends Varien_Data_Form_Element_Fieldset
{
    /**
     * @param $identifier
     * @param $label A label in the config will take precedence over this
     * @param array $config
     * @return Varien_Data_Form_Element_Abstract
     */
    public function simpleField($identifier, $label, $config = array())
    {
        $fieldLabel = isset($config['label']) ? $config['label'] : $label;

        $name = $identifier;
        if (isset($config['name_wrapper'])) {
            $name = $config['name_wrapper'] . '[' . $identifier . ']';
        }

        $fullConfig = array(
            'name'  => $name,
            'label' => $fieldLabel,
            'title' => $fieldLabel,
        );

        $type = isset($config['type']) ? $config['type'] : 'text';
        if (isset($config['type'])) {
            unset($config['type']);
        }

        $fullConfig = array_merge($fullConfig, $config);
        return $this->addField($identifier, $type, $fullConfig);
    }
}