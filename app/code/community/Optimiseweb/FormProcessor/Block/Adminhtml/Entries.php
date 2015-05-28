<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Entries
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (vkathirvel@gmail.com)
 * @copyright   Copyright (c) 2015 Kathir Vel
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Entries extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     *
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_entries';
        $this->_blockGroup = 'formprocessor';
        $this->_headerText = Mage::helper('formprocessor')->__('Entries Manager');
        $this->_addButtonLabel = Mage::helper('formprocessor')->__('Add Entry');

        parent::__construct();
    }

}
