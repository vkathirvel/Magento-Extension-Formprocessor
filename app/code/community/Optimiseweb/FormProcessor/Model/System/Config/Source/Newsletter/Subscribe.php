<?php

/**
 * Optimiseweb FormProcessor Model System Config Source Newsletter Subscribe
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2013 Optimise Web Limited
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Model_System_Config_Source_Newsletter_Subscribe extends Varien_Object
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    static public function getOptionArray()
    {
        return array(
                self::STATUS_DISABLED => Mage::helper('formprocessor')->__('No'),
                self::STATUS_ENABLED => Mage::helper('formprocessor')->__('Yes'),
        );
    }

    /**
     *
     * @return type
     */
    public function toOptionArray()
    {
        return self::getOptionArray();
    }

}