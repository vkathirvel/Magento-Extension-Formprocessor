<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Forms Edit Tab Main
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2013 Optimise Web Limited
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Forms_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     *
     * @return type
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('formprocessor_forms_data');

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('main-1', array('legend' => Mage::helper('formprocessor')->__('General'), 'class' => 'fieldset-wide'));

        $fieldset->addField('description', 'text', array(
                'name' => 'description',
                'label' => Mage::helper('formprocessor')->__('Friendly Description'),
                'title' => Mage::helper('formprocessor')->__('Friendly Description'),
                'required' => true,
                'after_element_html' => '<p class="note" style="width:90%;">A friendly name to describe the Form.</p>',
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
                'after_element_html' => '<p class="note" style="width:90%;">Notice message will be returned if the status is set to disabled.</p>',
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'multiselect', array(
                    'name' => 'store_ids[]',
                    'label' => Mage::helper('formprocessor')->__('Store View'),
                    'title' => Mage::helper('formprocessor')->__('Store View'),
                    'required' => true,
                    'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        } else {
            $fieldset->addField('store_ids', 'hidden', array(
                    'name' => 'store_ids[]',
                    'value' => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreIds(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('custom_processor_filename', 'text', array(
                'name' => 'custom_processor_filename',
                'label' => Mage::helper('formprocessor')->__('Additional Process Filename'),
                'title' => Mage::helper('formprocessor')->__('Additional Process Filename'),
                'required' => false,
                'after_element_html' => '<p class="note" style="width:90%;">e.g. ChangeData.php. Save this file under FormProcessor/controllers/Includes.</p>',
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('start_date', 'date', array(
                'name' => 'start_date',
                'label' => Mage::helper('formprocessor')->__('Start Date'),
                'title' => Mage::helper('formprocessor')->__('Start Date'),
                'required' => false,
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'input_format' => $dateFormatIso,
                'format' => $dateFormatIso,
                'after_element_html' => '<p class="note" style="width:90%;">Notice message will be returned if the current date falls before the start date.</p>',
        ));

        $fieldset->addField('end_date', 'date', array(
                'name' => 'end_date',
                'label' => Mage::helper('formprocessor')->__('End Date'),
                'title' => Mage::helper('formprocessor')->__('End Date'),
                'required' => false,
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'input_format' => $dateFormatIso,
                'format' => $dateFormatIso,
                'after_element_html' => '<p class="note" style="width:90%;">Notice message will be returned if the current date falls after the end date.</p>',
        ));

        $fieldset = $form->addFieldset('main-2', array('legend' => Mage::helper('formprocessor')->__('File Attachments'), 'class' => 'fieldset-wide'));

        $fieldset->addField('attachment_names', 'text', array(
                'name' => 'attachment_names',
                'label' => Mage::helper('formprocessor')->__('Names'),
                'title' => Mage::helper('formprocessor')->__('Names'),
                'required' => false,
                'after_element_html' => '<p class="note" style="width:90%;">Names should match the file names on the form. Comma separated values. Example: cv,prescription,photo</p>',
        ));

        $fieldset->addField('attachment_allowed_extensions', 'text', array(
                'name' => 'attachment_allowed_extensions',
                'label' => Mage::helper('formprocessor')->__('Allowed File Type Extensions'),
                'title' => Mage::helper('formprocessor')->__('Allowed File Type Extensions'),
                'required' => false,
                'after_element_html' => '<p class="note" style="width:90%;">Comma separated values. Example: jpg,jpeg,gif,png. Defaults to jpg,jpeg,gif,png,pdf,xls,xlsx,txt,rtf,doc,docx.</p>',
        ));

        $fieldset->addField('attachment_prepend_name', 'text', array(
                'name' => 'attachment_prepend_name',
                'label' => Mage::helper('formprocessor')->__('Prepend name to attachments'),
                'title' => Mage::helper('formprocessor')->__('Prepend name to attachments'),
                'required' => false,
                'after_element_html' => '<p class="note" style="width:90%;">Example: Prescription- / CV- / Design-</p>',
        ));

        $fieldset->addField('attachment_save', 'select', array(
                'name' => 'attachment_save',
                'label' => Mage::helper('formprocessor')->__('Save the file?'),
                'title' => Mage::helper('formprocessor')->__('Save the file?'),
                'values' => array(
                        array(
                                'value' => 0,
                                'label' => Mage::helper('formprocessor')->__('No'),
                        ),
                        array(
                                'value' => 1,
                                'label' => Mage::helper('formprocessor')->__('Yes'),
                        ),
                ),
                'after_element_html' => '<p class="note" style="width:90%;">Saved files can be accessed using a public URL. Leave the option as \'No\' if the attachment is of sensitive nature. That way, the file will be deleted once it is attached to the email.</p>',
        ));

        $fieldset->addField('attachment_email', 'multiselect', array(
                'name' => 'attachment_email[]',
                'label' => Mage::helper('formprocessor')->__('Attach to'),
                'title' => Mage::helper('formprocessor')->__('Attach to'),
                'values' => array(
                        array(
                                'value' => 0,
                                'label' => Mage::helper('formprocessor')->__('Do not attach to any emails'),
                        ),
                        array(
                                'value' => 'owner',
                                'label' => Mage::helper('formprocessor')->__('Owner emails'),
                        ),
                        array(
                                'value' => 'visitor',
                                'label' => Mage::helper('formprocessor')->__('Visitor emails'),
                        ),
                ),
                'after_element_html' => '<p class="note" style="width:90%;"></p>',
        ));

        if (Mage::getSingleton('adminhtml/session')->getFormData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFormData());
            Mage::getSingleton('adminhtml/session')->setFormData(null);
        } elseif ($model) {
            $form->setValues(Mage::registry('formprocessor_forms_data')->getData());
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

}