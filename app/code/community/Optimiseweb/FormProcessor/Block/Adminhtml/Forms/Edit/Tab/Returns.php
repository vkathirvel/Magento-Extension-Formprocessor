<?php

/**
 * Optimiseweb FormProcessor Block Adminhtml Forms Edit Tab Returns
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Adminhtml_Forms_Edit_Tab_Returns extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     *
     * @return type
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('formprocessor_forms_data');

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('returns-1', array('legend' => Mage::helper('formprocessor')->__('Redirection'), 'class' => 'fieldset-wide'));

        $fieldset->addField('redirect_url', 'text', array(
                'name' => 'redirect_url',
                'label' => Mage::helper('formprocessor')->__('Redirect URL'),
                'title' => Mage::helper('formprocessor')->__('Redirect URL'),
                'required' => false,
                'after_element_html' => '<p class="note" style="width:90%;">Redirection works for non-AJAX processing. Leave this field blank to redirect the visitor back to the same page. If you would like to redirect the visitor to another page or an external website, provide the full URL including the protocol (http:// or https://) like http://www.yourwebsite.com/examplepage.html or leave it as examplepage.html (the store URL will be prepended to the URL provided). To link to the homepage, you can use "baseurl".</p>',
        ));

        $fieldset = $form->addFieldset('returns-2', array('legend' => Mage::helper('formprocessor')->__('Messages'), 'class' => 'fieldset-wide'));

        $fieldset->addField('success_message', 'text', array(
                'name' => 'success_message',
                'label' => Mage::helper('formprocessor')->__('Success Message'),
                'title' => Mage::helper('formprocessor')->__('Success Message'),
                'required' => false,
        ));

        $fieldset->addField('error_message', 'text', array(
                'name' => 'error_message',
                'label' => Mage::helper('formprocessor')->__('Error Message'),
                'title' => Mage::helper('formprocessor')->__('Error Message'),
                'required' => false,
        ));

        $fieldset->addField('notice_message', 'text', array(
                'name' => 'notice_message',
                'label' => Mage::helper('formprocessor')->__('Notice Message'),
                'title' => Mage::helper('formprocessor')->__('Notice Message'),
                'required' => false,
        ));

        $fieldset->addField('return_messages_comment', 'note', array(
                'text' => Mage::helper('formprocessor')->__('If these fields are left blank, default return messages (System > Config > Form Processor) will be used.'),
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
