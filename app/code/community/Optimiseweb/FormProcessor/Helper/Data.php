<?php

/**
 * Optimiseweb FormProcessor Data Helper
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Get config
     *
     * @param type $field
     * @return type
     */
    public function getConfig($field)
    {
        return Mage::getStoreConfig('optimisewebformprocessor/' . $field);
    }

    /**
     * Get Ajax Loader
     *
     * @return type
     */
    public function getLoader()
    {
        if ($this->getConfig('ajax/loader')) {
            return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'optimiseweb/formprocessor/loader/' . $this->getConfig('ajax/loader');
        }
        return FALSE;
    }

    /**
     * Check and return default return messages
     *
     * @param type $form
     * @return type
     */
    public function defaultReturnMessage($status)
    {
        if ($status) {
            switch ($status) {
                case 'success':
                    if (($this->getConfig('return_messages/success_message') == NULL) OR ($this->getConfig('return_messages/success_message') == '')) {
                        return 'The form has been successfully submitted.';
                    } else {
                        return $this->getConfig('return_messages/success_message');
                    }
                    break;

                case 'error':
                    if (($this->getConfig('return_messages/error_message') == NULL) OR ($this->getConfig('return_messages/error_message') == '')) {
                        return 'Errors were encountered while submitting the form.';
                    } else {
                        return $this->getConfig('return_messages/error_message');
                    }
                    break;

                case 'notice':
                    if (($this->getConfig('return_messages/notice_message') == NULL) OR ($this->getConfig('return_messages/notice_message') == '')) {
                        return 'The form is disabled.';
                    } else {
                        return $this->getConfig('return_messages/notice_message');
                    }
                    break;

                default:
                    return;
                    break;
            }
        }
        return;
    }

    /**
     * Prep the model data
     *
     * @param type $form
     * @return type
     */
    public function modelPrepare($form)
    {
        /**
         * Status
         */
        if ($form->getStatus() == 1) {
            if ($this->filterStore($form->getStoreIds())) {
                if ($this->checkDateRange($form->getStartDate(), $form->getEndDate())) {
                    if (($form->getOwnerEnabled() == 1) OR ($form->getVisitorEnabled() == 1) OR ($form->getLogFormData() == 1)) {
                        $form->setStatusFlag(TRUE);
                        $form->setStatusMessage('The form is active and enabled.');
                    } else {
                        $form->setStatusFlag(FALSE);
                        $form->setStatusMessage('The form has no action assigned to it.');
                    }
                } else {
                    $form->setStatusFlag(FALSE);
                    $form->setStatusMessage('The form is set to work within a speific date range and is now disabled.');
                }
            } else {
                $form->setStatusFlag(FALSE);
                $form->setStatusMessage('The form is disabled for this store.');
            }
        } else {
            $form->setStatusFlag(FALSE);
            $form->setStatusMessage('The form is disabled.');
        }
        /**
         * Attachments
         */
        /* Attachment Names */
        if (($form->getAttachmentNames() != NULL) OR ($form->getAttachmentNames() != '')) {
            $form->setAttachmentNames(explode(',', $form->getAttachmentNames()));
            /* Attachment Allowed Extensions */
            if (($form->getAttachmentAllowedExtensions() != NULL) OR ($form->getAttachmentAllowedExtensions() != '')) {
                $form->setAttachmentAllowedExtensions(explode(',', $form->getAttachmentAllowedExtensions()));
            } else {
                $form->setAttachmentAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'xls', 'xlsx', 'txt', 'rtf', 'doc', 'docx'));
            }
            /* Attachment Prepend Name */
            if (($form->getAttachmentPrependName() == NULL)) {
                $form->setAttachmentPrependName('');
            }
            /* Attachment Emails */
            if (($form->getAttachmentEmail() != NULL) OR ($form->getAttachmentEmail() != '')) {
                $form->setAttachmentEmail(explode(',', $form->getAttachmentEmail()));
            } else {
                $form->setAttachmentEmail(FALSE);
            }
        } else {
            $form->setAttachmentNames(FALSE);
        }
        /**
         * Email Headers
         */
        $form->setOwnerSenderName(Mage::getStoreConfig('trans_email/ident_' . $form->getOwnerSender() . '/name'));
        $form->setOwnerSenderEmail(Mage::getStoreConfig('trans_email/ident_' . $form->getOwnerSender() . '/email'));
        if (($form->getOwnerRecipientEmail() == NULL) OR ($form->getOwnerRecipientEmail() == '')) {
            $form->setOwnerRecipientEmail($form->getOwnerSenderEmail());
            $form->setOwnerRecipientEmail($form->getOwnerSenderEmail());
        }
        if (($form->getOwnerBcc() != NULL) OR ($form->getOwnerBcc() != '')) {
            $form->setOwnerBcc(explode(',', $form->getOwnerBcc()));
        } else {
            $form->setOwnerBcc(NULL);
        }
        $form->setVisitorSenderName(Mage::getStoreConfig('trans_email/ident_' . $form->getVisitorSender() . '/name'));
        $form->setVisitorSenderEmail(Mage::getStoreConfig('trans_email/ident_' . $form->getVisitorSender() . '/email'));
        if (($form->getVisitorBcc() != NULL) OR ($form->getVisitorBcc() != '')) {
            $form->setVisitorBcc(explode(',', $form->getVisitorBcc()));
        } else {
            $form->setVisitorBcc(NULL);
        }
        /**
         * Redirect URL
         */
        if (($form->getRedirectUrl() == NULL) OR ($form->getRedirectUrl() == '')) {
            $form->setRedirectUrl(FALSE);
        } else {
            $form->setRedirectUrl($this->destinationUrlCheck($form->getRedirectUrl()));
        }

        /**
         * Return messages
         */
        if (($form->getSuccessMessage() == NULL) OR ($form->getSuccessMessage() == '')) {
            $form->setSuccessMessage($this->defaultReturnMessage('success'));
        }
        if (($form->getErrorMessage() == NULL) OR ($form->getErrorMessage() == '')) {
            $form->setErrorMessage($this->defaultReturnMessage('error'));
        }
        if (($form->getNoticeMessage() == NULL) OR ($form->getNoticeMessage() == '')) {
            $form->setNoticeMessage($this->defaultReturnMessage('notice'));
        }
        return;
    }

    /**
     * Check if today is within the given date range
     *
     * @param type $startDate
     * @param type $endDate
     * @return boolean
     */
    public function checkDateRange($startDate, $endDate)
    {
        if ($startDate) {
            
        } else {
            $startDate = '2000-01-01 00:00:00';
        }
        if ($endDate) {
            
        } else {
            $endDate = '2038-12-31 00:00:00';
            //$endDate = date('Y-m-d H:i:s', PHP_INT_MAX);
        }

        $today = strtotime(date('Y-m-d'));
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        if (($today >= $startDate) AND ($today <= $endDate)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Check if the item can be shown within the current store
     *
     * @param type $storeIds
     * @return boolean
     */
    public function filterStore($storeIds)
    {
        $storeIdData = explode(',', $storeIds);

        if (in_array('0', $storeIdData) OR in_array(Mage::app()->getStore()->getId(), $storeIdData)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Check for the protocol and add the store url if no protocol provided
     *
     * @param string $url
     * @return string
     */
    public function destinationUrlCheck($url)
    {
        $count = 0;
        $protocols = array('http://', 'https://', 'ftp://', 'mailto:');
        foreach ($protocols as $protocol) {
            if (substr($url, 0, strlen($protocol)) !== $protocol)
                $count++;
        }
        if (count($protocols) == $count) {
            if ($url == "baseurl") {
                $url = Mage::getUrl();
            } else {
                $url = Mage::getUrl() . $url;
            }
        }
        return $url;
    }

    /**
     * Get the logged in customer's name
     *
     * @return string
     */
    public function getUserName()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim($customer->getName());
    }

    /**
     * Get the logged in customer's first name
     *
     * @return string
     */
    public function getUserFirstname()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim($customer->getFirstname());
    }

    /**
     * Get the logged in customer's first name
     *
     * @return string
     */
    public function getUserLastname()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim($customer->getLastname());
    }

    /**
     * Get the logged in customer's email
     *
     * @return string
     */
    public function getUserEmail()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }

    /**
     * Get the form's action URL with CSRF key
     *
     * @param type $formId
     * @param type $key
     * @return type
     */
    public function getFormActionUrl($formId, $key = TRUE)
    {
        if ($key) {
            return Mage::getUrl() . 'formprocessor/form/post/key/' . Mage::getSingleton('adminhtml/url')->getSecretKey('formprocessor_form', 'post') . '/form_id/' . $formId . '/';
        } else {
            return Mage::getUrl() . 'formprocessor/form/post/form_id/' . $formId . '/';
        }
    }

}
