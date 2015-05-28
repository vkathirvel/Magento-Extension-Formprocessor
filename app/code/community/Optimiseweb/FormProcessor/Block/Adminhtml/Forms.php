<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Forms
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (vkathirvel@gmail.com)
 * @copyright   Copyright (c) 2015 Kathir Vel
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Forms extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     *
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_forms';
        $this->_blockGroup = 'formprocessor';
        $this->_headerText = Mage::helper('formprocessor')->__('Forms Manager');
        $this->_addButtonLabel = Mage::helper('formprocessor')->__('Add Form');

        parent::__construct();
    }

}
