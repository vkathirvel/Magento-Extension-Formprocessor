<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Entries Edit Tab Log
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Entries_Edit_Tab_Log extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     *
     * @return type
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('formprocessor_entries_data');

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('log-1', array('legend' => Mage::helper('formprocessor')->__('All Data'), 'class' => 'fieldset-wide'));

        $fieldset->addField('all_data', 'textarea', array(
                'name' => 'all_data',
                'label' => Mage::helper('formprocessor')->__('All Data'),
                'title' => Mage::helper('formprocessor')->__('All Data'),
                'required' => false,
        ));

        if (Mage::getSingleton('adminhtml/session')->getFormData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFormData());
            Mage::getSingleton('adminhtml/session')->setFormData(null);
        } elseif ($model) {
            $form->setValues(Mage::registry('formprocessor_entries_data')->getData());
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
