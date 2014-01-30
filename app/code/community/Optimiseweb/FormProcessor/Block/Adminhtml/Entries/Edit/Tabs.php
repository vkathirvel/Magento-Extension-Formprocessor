<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Entries Edit Tabs
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2013 Optimise Web Limited
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Entries_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('formprocessor_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('formprocessor')->__('Entry Information'));
    }

    /**
     *
     * @return type
     */
    protected function _beforeToHtml()
    {
        $this->addTab('main_section', array(
                'label' => Mage::helper('formprocessor')->__('Entry Information'),
                'title' => Mage::helper('formprocessor')->__('Entry Information'),
                'content' => $this->getLayout()->createBlock('formprocessor/adminhtml_entries_edit_tab_main')->toHtml(),
        ));

        $this->addTab('log_section', array(
                'label' => Mage::helper('formprocessor')->__('Logged Data'),
                'title' => Mage::helper('formprocessor')->__('Logged Data'),
                'content' => $this->getLayout()->createBlock('formprocessor/adminhtml_entries_edit_tab_log')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}