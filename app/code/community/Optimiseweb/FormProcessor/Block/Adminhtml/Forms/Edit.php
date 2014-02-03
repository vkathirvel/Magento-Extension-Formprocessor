<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Forms Edit
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Forms_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     *
     */
    public function __construct()
    {
        $this->_objectId = 'form_id';
        $this->_blockGroup = 'formprocessor';
        $this->_controller = 'adminhtml_forms';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('formprocessor')->__('Save Form'));
        $this->_updateButton('delete', 'label', Mage::helper('formprocessor')->__('Delete Form'));

        $this->_addButton('saveandcontinue', array(
                'label' => Mage::helper('formprocessor')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class' => 'save',
            ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     *
     * @return type
     */
    public function getHeaderText()
    {
        if (Mage::registry('formprocessor_form_data') && Mage::registry('formprocessor_form_data')->getId()) {
            return Mage::helper('formprocessor')->__('Edit Form');
        } else {
            return Mage::helper('formprocessor')->__('Add Form');
        }
    }

    /**
     *
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

}
