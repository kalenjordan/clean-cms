<?php

class Clean_Cms_Model_Data_Form_Fieldset extends Varien_Data_Form_Element_Fieldset
{
    public function simpleField($identifier, $label, $config = array())
    {
        $fullConfig = array(
            'name'  => $identifier,
            'label' => $label,
            'title' => $label,
        );

        $type = isset($config['type']) ? $config['type'] : 'text';
        if (isset($config['type'])) {
            unset($config['type']);
        }

        $fullConfig = array_merge($fullConfig, $config);
        return $this->addField($identifier, $type, $fullConfig);
    }
}