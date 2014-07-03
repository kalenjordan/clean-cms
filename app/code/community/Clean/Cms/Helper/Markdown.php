<?php

require_once(Mage::getBaseDir('lib') . '/Michelf/MarkdownExtra.inc.php');
use \Michelf\MarkdownExtra;

class Clean_Cms_Helper_Markdown extends Mage_Core_Helper_Abstract
{
    public function toHtml($markdown)
    {
        return MarkdownExtra::defaultTransform($markdown);
    }
}