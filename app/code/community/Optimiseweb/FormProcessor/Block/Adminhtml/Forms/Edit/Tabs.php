<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Forms Edit Tabs
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2013 Optimise Web Limited
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Forms_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('formprocessor_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('formprocessor')->__('Form Information'));
    }

    /**
     *
     * @return type
     */
    protected function _beforeToHtml()
    {
        $this->addTab('main_section', array(
                'label' => Mage::helper('formprocessor')->__('Form Information'),
                'title' => Mage::helper('formprocessor')->__('Form Information'),
                'content' => $this->getLayout()->createBlock('formprocessor/adminhtml_forms_edit_tab_main')->toHtml(),
        ));

        $this->addTab('emails_section', array(
                'label' => Mage::helper('formprocessor')->__('Emails / Entry Log'),
                'title' => Mage::helper('formprocessor')->__('Emails / Entry Log'),
                'content' => $this->getLayout()->createBlock('formprocessor/adminhtml_forms_edit_tab_emails')->toHtml(),
        ));

        $this->addTab('returns_section', array(
                'label' => Mage::helper('formprocessor')->__('Redirect / Messages'),
                'title' => Mage::helper('formprocessor')->__('Redirect / Messages'),
                'content' => $this->getLayout()->createBlock('formprocessor/adminhtml_forms_edit_tab_returns')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}