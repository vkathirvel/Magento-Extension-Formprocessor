<?php

/**
 * Optimiseweb FormProcessor Block System Config Backend Sample
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_System_Config_Backend_Sample extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    /**
     * Get the system config field and insert a HTML link
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $url = Mage::getUrl() . 'formprocessor/form/sample/';
        $html = "<a href='" . $url . "' target='_blank'>Click here to visit the sample page.</a>";
        return $html;
    }

}
