<?php

/**
 * @method Clean_Cms_Model_Data_Form setHtmlIdPrefix($val)
 */
class Clean_Cms_Model_Data_Form extends Varien_Data_Form
{
    public function addFieldset($elementId, $config, $after = false)
    {
        $element = new Clean_Cms_Model_Data_Form_Fieldset($config);
        $element->setId($elementId);
        $this->addElement($element, $after);

        return $element;
    }

    public function simpleFieldset($identifier, $label, $config = array())
    {
        return $this->addFieldset($identifier, array(
            'legend' => Mage::helper('cleancms')->__($label),
            'class' => isset($config['class']) ? $config['class'] : 'fieldset-wide',
        ));
    }
}