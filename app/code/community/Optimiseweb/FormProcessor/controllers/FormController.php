<?php

/**
 * Optimiseweb FormProcessor Form Controller
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2013 Optimise Web Limited
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_FormController extends Mage_Core_Controller_Front_Action
{
    /* Form ID */

    public $formId;
    /* Form Model */
    public $form;
    /* Post Data */
    public $post;
    /* Owner Email */
    public $ownerEmail;
    /* Visitor Email */
    public $visitorEmail;
    /* Log Data */
    public $logData;

    /**
     *
     */
    public function preDispatch()
    {
        parent::preDispatch();
    }

    /**
     * Index Action
     *
     * @return type
     */
    public function indexAction()
    {
        /* 404 */
        $this->norouteAction();
        return;
    }

    /**
     * Sample Action
     *
     * @return type
     */
    public function sampleAction()
    {
        if (Mage::helper('formprocessor')->getConfig('sample/enabled') AND Mage::helper('formprocessor')->getConfig('sample/form_id') AND (Mage::helper('formprocessor')->getConfig('sample/form_id') != NULL)) {

            $this->loadLayout();

            /* Root Template */
            $this->getLayout()->helper('page/layout')->applyTemplate(Mage::helper('formprocessor')->getConfig('sample/root_template'));

            /* Head Meta */
            if ($head = $this->getLayout()->getBlock('head')) {
                $head->setRobots('NOINDEX,NOFOLLOW');
            }

            if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbs->addCrumb('home', array('label' => 'Home', 'title' => Mage::helper('formprocessor')->__('Home Page'), 'link' => Mage::getUrl()));
                $breadcrumbs->addCrumb('sample', array('label' => Mage::helper('formprocessor')->__('Sample Page')));
            }
            /**
             * Content block
             */
            if ($content = $this->getLayout()->getBlock('content')) {
                $content->append($this->getLayout()->createBlock('core/template', '')->setTemplate('optimiseweb/formprocessor/sample/sample.phtml'));
            }

            $this->renderLayout();
        } else {
            /* 404 */
            $this->norouteAction();
        }
        return;
    }

    /**
     * Post Action
     *
     * @return type
     */
    public function postAction()
    {
        /* CSRF Check */
        if ($this->_validateSecretKey()) {
            /* Obtain the Form ID from the url */
            $this->formId = Mage::app()->getRequest()->getParam('form_id');
            /* Get the Post data */
            $this->post = $this->getRequest()->getPost();
            /* If the Form ID is not empty and Post data is available */
            if ($this->formId AND $this->post) {
                /* Load the form model using the ID */
                $this->form = Mage::getModel('formprocessor/forms')->loadAndPrepare($this->formId);
                /* If the Model can return a valid record */
                if ($this->form) {
                    /* If the form is active */
                    if ($this->form->getStatusFlag()) {
                        try {
                            /**
                             * Instantiate Owner Email Data
                             */
                            $this->ownerEmail->senderName = $this->form->getOwnerSenderName();
                            $this->ownerEmail->senderEmail = $this->form->getOwnerSenderEmail();
                            $this->ownerEmail->recipientName = $this->form->getOwnerRecipientEmail();
                            $this->ownerEmail->recipientEmail = $this->form->getOwnerRecipientEmail();
                            $this->ownerEmail->replyTo = NULL;
                            $this->ownerEmail->cc = NULL;
                            $this->ownerEmail->bcc = $this->form->getOwnerBcc();
                            $this->ownerEmail->subject = NULL;
                            $this->ownerEmail->variables = $this->post;
                            $this->ownerEmail->attachment = NULL;
                            $this->ownerEmail->template = $this->form->getOwnerEmailTemplate();
                            /**
                             * Instantiate Visitor Email Data
                             */
                            $this->visitorEmail->senderName = $this->form->getVisitorSenderName();
                            $this->visitorEmail->senderEmail = $this->form->getVisitorSenderEmail();
                            if (isset($this->post['visitor_name'])) {
                                $this->visitorEmail->recipientName = $this->post['visitor_name'];
                            } elseif (isset($this->post['visitor_email'])) {
                                $this->visitorEmail->recipientName = $this->post['visitor_email'];
                            } else {
                                $this->visitorEmail->recipientName = NULL;
                            }
                            if (isset($this->post['visitor_email'])) {
                                $this->visitorEmail->recipientEmail = $this->post['visitor_email'];
                            } else {
                                $this->visitorEmail->recipientEmail = NULL;
                            }
                            $this->visitorEmail->replyTo = NULL;
                            $this->visitorEmail->cc = NULL;
                            $this->visitorEmail->bcc = $this->form->getVisitorBcc();
                            $this->visitorEmail->subject = NULL;
                            $this->visitorEmail->variables = $this->post;
                            $this->visitorEmail->attachment = NULL;
                            $this->visitorEmail->template = $this->form->getVisitorEmailTemplate();
                            /**
                             * Upload, Process and Attach Attachments
                             */
                            $this->_processAttachments();
                            /**
                             * Instantiate Log Data
                             */
                            $this->logData = $this->post;
                            /*
                             * Addition Custom Processing with the Post Data
                             * Mention the filename in the form settings
                             * Create a file (form_id.php) under the Includes folder
                             */
                            if (!is_null($this->form->getCustomProcessorFilename()) AND is_file(Mage::getModuleDir('controllers', 'Optimiseweb_FormProcessor') . DS . 'Includes' . DS . $this->form->getCustomProcessorFilename())) {
                                require_once Mage::getModuleDir('controllers', 'Optimiseweb_FormProcessor') . DS . 'Includes' . DS . $this->form->getCustomProcessorFilename();
                            } elseif (is_file(Mage::getModuleDir('controllers', 'Optimiseweb_FormProcessor') . DS . 'Includes' . DS . $this->form->getId() . '.php')) {
                                require_once Mage::getModuleDir('controllers', 'Optimiseweb_FormProcessor') . DS . 'Includes' . DS . $this->form->getId() . '.php';
                            }
                            /**
                             * Handle Newsletter Subscriptions
                             */
                            $this->_newsletterSubscribe();
                            /* Send Owner Email */
                            if ($this->form->getOwnerEnabled()) {
                                Mage::helper('emailer')->sendEmails($this->ownerEmail->senderName, $this->ownerEmail->senderEmail, $this->ownerEmail->recipientName, $this->ownerEmail->recipientEmail, $this->ownerEmail->replyTo, $this->ownerEmail->cc, $this->ownerEmail->bcc, $this->ownerEmail->subject, $this->ownerEmail->variables, $this->ownerEmail->attachment, $this->ownerEmail->template);
                            }
                            /* Send Visitor Email */
                            if ($this->form->getVisitorEnabled() AND $this->visitorEmail->recipientName AND $this->visitorEmail->recipientEmail) {
                                Mage::helper('emailer')->sendEmails($this->visitorEmail->senderName, $this->visitorEmail->senderEmail, $this->visitorEmail->recipientName, $this->visitorEmail->recipientEmail, $this->visitorEmail->replyTo, $this->visitorEmail->cc, $this->visitorEmail->bcc, $this->visitorEmail->subject, $this->visitorEmail->variables, $this->visitorEmail->attachment, $this->visitorEmail->template);
                            }
                            /* Log Form Data */
                            if ($this->form->getLogFormData()) {
                                $entry = Mage::getModel('formprocessor/entries');
                                $entry->setFormId($this->form->getId());
                                $entry->setStoreId(Mage::app()->getStore(true)->getId());
                                $entry->setName($this->visitorEmail->recipientName);
                                $entry->setEmail($this->visitorEmail->recipientEmail);
                                $entry->setAllData(json_encode($this->logData));
                                $entry->setCreatedTime(now());
                                $entry->setUpdateTime(now());
                                $entry->save();
                            }
                            /* Success Return */
                            $this->returnResult('success', $this->form->getSuccessMessage(), $this->form->getRedirectUrl());
                        } catch (Exception $e) {
                            /* Error Return */
                            $this->returnResult('error', $this->form->getErrorMessage());
                        }
                    } else {
                        /* Form is not active. Return notice message. */
                        $this->returnResult('error', $this->form->getNoticeMessage());
                    }
                } else {
                    /* Model did not return a valid record */
                    $this->returnResult('error', 'Form not found');
                }
            } else {
                /* Form ID was not provided */
                $this->returnResult('error', 'Not allowed');
            }
        } else {
            /* CSRF Detected */
            $this->returnResult('error', 'CSRF detected');
        }
    }

    /**
     * Upload, Process and Attach Attachments
     */
    protected function _processAttachments()
    {
        /* If the form is setup to accept attachments */
        if ($this->form->getAttachmentNames()) {
            $media_path = Mage::getBaseDir('media') . DS;
            $media_sub_folder = 'optimiseweb/formprocessor' . DS;
            $final_media_path = $media_path . $media_sub_folder;

            $uploader = '';
            $upload = '';
            $attachmentsArray = array();

            /* Loop through the attachment names provided in the form setup */
            foreach ($this->form->getAttachmentNames() as $attachment) {
                if (isset($_FILES[$attachment]['name']) && $_FILES[$attachment]['name'] != '') {
                    $uploader = new Varien_File_Uploader($attachment);
                    $uploader->setAllowedExtensions($this->form->getAttachmentAllowedExtensions());
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $upload = $uploader->save($final_media_path, $this->form->getAttachmentPrependName() . $_FILES[$attachment]['name']);
                    /* Create and array of the attachments */
                    $attachmentsArray[] = array(
                            'content' => file_get_contents($final_media_path . $upload['file']),
                            'mime' => $upload['type'],
                            'filename' => $upload['file'],
                    );
                    /* Save the attachment or detele it? */
                    if ($this->form->getAttachmentSave() == 0) {
                        unlink($final_media_path . $upload['file']);
                    } else {
                        $this->post['attachments'][$attachment][url] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $media_sub_folder . $upload['file'];
                    }
                    $this->post['attachments'][$attachment][filename] = $upload['file'];
                }
            }
            /* Check if the attachment is to be attached to the emails */
            if ($this->form->getAttachmentEmail()) {
                if (in_array('owner', $this->form->getAttachmentEmail())) {
                    $this->ownerEmail->attachment = $attachmentsArray;
                }
                if (in_array('visitor', $this->form->getAttachmentEmail())) {
                    $this->visitorEmail->attachment = $attachmentsArray;
                }
            }
        }
    }

    /**
     * Return the JSON result or redirect and set the session message
     *
     * @param type $status
     * @param type $message
     * @param type $redirectToUrl
     * @return type
     */
    protected function returnResult($status, $message, $redirectToUrl = FALSE)
    {
        if (Mage::app()->getRequest()->isAjax()) {
            $resultFormat = 'json';
        }

        switch ($resultFormat) {
            /* JSON */
            case 'json':
                $response = array('status' => $status, 'message' => $message);
                $this->getResponse()->setHeader('Content-type', 'application/json');
                $this->getResponse()->setBody(json_encode($response));
                break;

            /* Session Message and Redirect */
            default:
                /* Set session message */
                switch ($status) {
                    case 'error':
                        Mage::getSingleton('core/session')->addError($message);
                        break;

                    case 'success':
                        Mage::getSingleton('core/session')->addSuccess($message);
                        break;

                    case 'warning':
                        Mage::getSingleton('core/session')->addWarning($message);
                        break;

                    case 'notice':
                        Mage::getSingleton('core/session')->addNotice($message);
                        break;

                    default:
                        break;
                }
                /* Redirect */
                if ($redirectToUrl) {
                    Mage::app()->getResponse()->setRedirect($redirectToUrl)->sendResponse();
                } else {
                    $this->_redirectReferer();
                }
                break;
        }
        return;
    }

    /**
     * Validate Secret Key
     *
     * @return bool
     */
    protected function _validateSecretKey()
    {
        if (!($secretKey = Mage::app()->getRequest()->getParam(Mage_Adminhtml_Model_Url::SECRET_KEY_PARAM_NAME, null)) || $secretKey != Mage::getSingleton('adminhtml/url')->getSecretKey('formprocessor_form', 'post')) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    protected function _newsletterSubscribe()
    {
        /* If the form is armed for newsletter subscription */
        if (($this->form->getNewsletterSubscribe() == 1) OR (isset($this->post['newsletter_subscribe']) AND ($this->post['newsletter_subscribe'] == 1))) {
            if (($this->form->getNewsletterSubscribeConfirm() == 1) OR (isset($this->post['newsletter_subscribe_confirm']) AND ($this->post['newsletter_subscribe_confirm'] == 1))) {
                /* Easy method (but sends a confirmation email to the customer) */
                $newsletterSubscriber = Mage::getModel('newsletter/subscriber')->subscribe($this->post['visitor_email']);
            } else {
                /* Long way (Doesn't send confirmation email to customer) */
                /* Load up the subscriber if already there */
                $newsletterSubscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($this->post['visitor_email']);

                if (!$newsletterSubscriber->getId() || $newsletterSubscriber->getStatus() == Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED || $newsletterSubscriber->getStatus() == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    $newsletterSubscriber->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
                    $newsletterSubscriber->setSubscriberEmail($this->post['visitor_email']);
                    $newsletterSubscriber->setSubscriberConfirmCode($newsletterSubscriber->RandomSequence());
                    $newsletterSubscriber->setStoreId(Mage::app()->getStore()->getWebsiteId());
                    /* Load up the customer we want to subscribe */
                    $customer = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($this->post['visitor_email']);
                    /* If we find a matching customer */
                    if ($customer->getId()) {
                        $newsletterSubscriber->setCustomerId($customer->getId());
                    }
                    try {
                        $newsletterSubscriber->save();
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }
        }
    }

}