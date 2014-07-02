<?php

class Clean_Cms_Model_Data_Form_Fieldset extends Varien_Data_Form_Element_Fieldset
{
    public function simpleField($identifier, $config = array())
    {
        $fullConfig = array(
            'name'  => $identifier,
            'label' => isset($config['label']) ? $config['label'] : $identifier,
            'title' => isset($config['label']) ? $config['label'] : $identifier,
        );

        $type = isset($config['type']) ? $config['type'] : 'text';
        return $this->addField($identifier, $type, $fullConfig);
    }
}