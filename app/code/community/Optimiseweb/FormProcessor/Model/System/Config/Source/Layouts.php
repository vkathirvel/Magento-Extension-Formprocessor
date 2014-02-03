<?php

/**
 * Optimiseweb FormProcessor Model System Config Source Layouts
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Model_System_Config_Source_Layouts
{

    protected $_options;

    /**
     *
     * @return type
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = Mage::getSingleton('page/source_layout')->toOptionArray();
        }
        return $this->_options;
    }

}
