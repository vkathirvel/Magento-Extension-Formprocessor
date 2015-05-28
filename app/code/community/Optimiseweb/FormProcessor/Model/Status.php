<?php

/**
 * Optimiseweb FormProcessor Model Status
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (vkathirvel@gmail.com)
 * @copyright   Copyright (c) 2015 Kathir Vel
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Model_Status extends Varien_Object
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    static public function getOptionArray()
    {
        return array(
                self::STATUS_ENABLED => Mage::helper('formprocessor')->__('Enabled'),
                self::STATUS_DISABLED => Mage::helper('formprocessor')->__('Disabled')
        );
    }

}
