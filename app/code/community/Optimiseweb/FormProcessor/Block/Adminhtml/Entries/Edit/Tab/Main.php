<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Entries Edit Tab Main
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (vkathirvel@gmail.com)
 * @copyright   Copyright (c) 2015 Kathir Vel
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Entries_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     *
     * @return type
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('formprocessor_entries_data');

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('main-1', array('legend' => Mage::helper('formprocessor')->__('General'), 'class' => 'fieldset-wide'));

        $fieldset->addField('name', 'text', array(
                'name' => 'name',
                'label' => Mage::helper('formprocessor')->__('Name'),
                'title' => Mage::helper('formprocessor')->__('Name'),
                'required' => false,
        ));

        $fieldset->addField('email', 'text', array(
                'name' => 'email',
                'label' => Mage::helper('formprocessor')->__('Email'),
                'title' => Mage::helper('formprocessor')->__('Email'),
                'required' => false,
        ));

        $fieldset->addField('form_id', 'select', array(
                'name' => 'form_id',
                'label' => Mage::helper('formprocessor')->__('Form'),
                'title' => Mage::helper('formprocessor')->__('Form'),
                'required' => false,
                'values' => Mage::getModel('formprocessor/forms')->getFormsAsOptionsArray(),
        ));

        $fieldset->addField('status', 'select', array(
                'name' => 'status',
                'label' => Mage::helper('formprocessor')->__('Status'),
                'title' => Mage::helper('formprocessor')->__('Status'),
                'values' => array(
                        array(
                                'value' => 1,
                                'label' => Mage::helper('formprocessor')->__('Enabled'),
                        ),
                        array(
                                'value' => 2,
                                'label' => Mage::helper('formprocessor')->__('Disabled'),
                        ),
                ),
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'select', array(
                    'name' => 'store_id',
                    'label' => Mage::helper('formprocessor')->__('Store View'),
                    'title' => Mage::helper('formprocessor')->__('Store View'),
                    'required' => true,
                    'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                    'name' => 'store_id',
                    'value' => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('created_time', 'date', array(
                'name' => 'created_time',
                'label' => Mage::helper('formprocessor')->__('Created Date'),
                'title' => Mage::helper('formprocessor')->__('Created Date'),
                'required' => false,
                'format' => $dateFormatIso,
                'disabled' => true,
                'readonly' => true,
        ));

        $fieldset->addField('update_time', 'date', array(
                'name' => 'update_time',
                'label' => Mage::helper('formprocessor')->__('Updated Date'),
                'title' => Mage::helper('formprocessor')->__('Updated Date'),
                'required' => false,
                'format' => $dateFormatIso,
                'disabled' => true,
                'readonly' => true,
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
