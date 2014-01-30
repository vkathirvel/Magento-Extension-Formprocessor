<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Forms Edit Tab Emails
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2013 Optimise Web Limited
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Forms_Edit_Tab_Emails extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     *
     * @return type
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('formprocessor_forms_data');

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('emails-1', array('legend' => Mage::helper('formprocessor')->__('Store Owner Email'), 'class' => 'fieldset-wide'));

        $fieldset->addField('owner_enabled', 'select', array(
                'name' => 'owner_enabled',
                'label' => Mage::helper('formprocessor')->__('Enable?'),
                'title' => Mage::helper('formprocessor')->__('Enable?'),
                'required' => false,
                'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $fieldset->addField('owner_sender', 'select', array(
                'name' => 'owner_sender',
                'label' => Mage::helper('formprocessor')->__('Sender'),
                'title' => Mage::helper('formprocessor')->__('Sender'),
                'required' => false,
                'values' => Mage::getModel('adminhtml/system_config_source_email_identity')->toOptionArray(),
        ));

        $fieldset->addField('owner_recipient_email', 'text', array(
                'name' => 'owner_recipient_email',
                'label' => Mage::helper('formprocessor')->__('Recipient'),
                'title' => Mage::helper('formprocessor')->__('Recipient'),
                'required' => false,
                'class' => 'validate-email',
        ));

        $fieldset->addField('owner_bcc', 'text', array(
                'name' => 'owner_bcc',
                'label' => Mage::helper('formprocessor')->__('BCC (Comma Separated)'),
                'title' => Mage::helper('formprocessor')->__('BCC (Comma Separated)'),
                'required' => false,
        ));

        $fieldset->addField('owner_email_template', 'select', array(
                'name' => 'owner_email_template',
                'label' => Mage::helper('formprocessor')->__('Template'),
                'title' => Mage::helper('formprocessor')->__('Template'),
                'required' => false,
                'values' => Mage::getModel('adminhtml/system_config_source_email_template')->toOptionArray(),
        ));

        $fieldset = $form->addFieldset('emails-2', array('legend' => Mage::helper('formprocessor')->__('Visitor Email'), 'class' => 'fieldset-wide'));

        $fieldset->addField('visitor_enabled', 'select', array(
                'name' => 'visitor_enabled',
                'label' => Mage::helper('formprocessor')->__('Enable?'),
                'title' => Mage::helper('formprocessor')->__('Enable?'),
                'required' => false,
                'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $fieldset->addField('visitor_sender', 'select', array(
                'name' => 'visitor_sender',
                'label' => Mage::helper('formprocessor')->__('Sender'),
                'title' => Mage::helper('formprocessor')->__('Sender'),
                'required' => false,
                'values' => Mage::getModel('adminhtml/system_config_source_email_identity')->toOptionArray(),
        ));

        $fieldset->addField('visitor_bcc', 'text', array(
                'name' => 'visitor_bcc',
                'label' => Mage::helper('formprocessor')->__('BCC (Comma Separated)'),
                'title' => Mage::helper('formprocessor')->__('BCC (Comma Separated)'),
                'required' => false,
        ));

        $fieldset->addField('visitor_email_template', 'select', array(
                'name' => 'visitor_email_template',
                'label' => Mage::helper('formprocessor')->__('Template'),
                'title' => Mage::helper('formprocessor')->__('Template'),
                'required' => false,
                'values' => Mage::getModel('adminhtml/system_config_source_email_template')->toOptionArray(),
        ));

        $fieldset = $form->addFieldset('emails-3', array('legend' => Mage::helper('formprocessor')->__('Newsletter Subscription'), 'class' => 'fieldset-wide'));

        $fieldset->addField('newsletter_subscribe', 'select', array(
                'name' => 'newsletter_subscribe',
                'label' => Mage::helper('formprocessor')->__('Subscribe?'),
                'title' => Mage::helper('formprocessor')->__('Subscribe?'),
                'required' => false,
                'values' => Mage::getModel('formprocessor/system_config_source_newsletter_subscribe')->toOptionArray(),
                'after_element_html' => '<p class="note" style="width:90%;">This option automatically subscribes the visitor to the Magento newsletter subscribers list. To obtain explicit permission from the visitor, set this option to "No" and use a checkbox name="newsletter_subscribe" with value="1" in the form.</p>',
                ));

        $fieldset->addField('newsletter_subscribe_confirm', 'select', array(
                'name' => 'newsletter_subscribe_confirm',
                'label' => Mage::helper('formprocessor')->__('Send Confirmation Email?'),
                'title' => Mage::helper('formprocessor')->__('Send Confirmation Email?'),
                'required' => false,
                'values' => Mage::getModel('formprocessor/system_config_source_newsletter_confirm')->toOptionArray(),
                'after_element_html' => '<p class="note" style="width:90%;">This option sends the default Magento newsletter subscription confirmation email to the visitor. Alternatively use a hidden field name="newsletter_subscribe_confirm" with value="1" in the form.</p>',
        ));

        $fieldset = $form->addFieldset('emails-4', array('legend' => Mage::helper('formprocessor')->__('Log / Store / Record Entries'), 'class' => 'fieldset-wide'));

        $fieldset->addField('log_form_data', 'select', array(
                'name' => 'log_form_data',
                'label' => Mage::helper('formprocessor')->__('Log Form Data?'),
                'title' => Mage::helper('formprocessor')->__('Log Form Data?'),
                'required' => false,
                'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
                'after_element_html' => '<p class="note" style="width:90%;">Logged form data can be accessed by going to Optimiseweb > Form Processor > Manage Entries.</p>',
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