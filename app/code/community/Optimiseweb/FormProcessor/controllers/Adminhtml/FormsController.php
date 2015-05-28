<?php

/**
 * Optimiseweb FormProcessor Adminhtml Forms Controller
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (vkathirvel@gmail.com)
 * @copyright   Copyright (c) 2015 Kathir Vel
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Adminhtml_FormsController extends Mage_Adminhtml_Controller_Action
{

    /**
     * INIT Action
     */
    protected function _initAction()
    {
        $this->loadLayout();
        $this->loadLayout()->_setActiveMenu('optimiseweball/formprocessor/forms');
        $this->loadLayout()->_addBreadcrumb(Mage::helper('formprocessor')->__('Forms Processor'), Mage::helper('formprocessor')->__('Forms Processor'));
        return $this;
    }

    /**
     * Index Action
     */
    public function indexAction()
    {
        $this->_initAction();
        $block = $this->getLayout()->createBlock('formprocessor/adminhtml_forms', 'formprocessor_forms');
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    /**
     * Edit Action
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('form_id');
        $model = Mage::getModel('formprocessor/forms')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('formprocessor_forms_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('optimiseweball/formprocessor');
            $this->_addBreadcrumb(Mage::helper('formprocessor')->__('Forms Manager'), Mage::helper('formprocessor')->__('Forms Manager'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('formprocessor/adminhtml_forms_edit'));
            $this->_addLeft($this->getLayout()->createBlock('formprocessor/adminhtml_forms_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('formprocessor')->__('Form does not exist'));
            $this->_redirect('*/*/');
        }
    }

    /**
     * New Action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Save Action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            $model = Mage::getModel('formprocessor/forms');

            /* Store Ids */
            if (!isset($data['store_ids']) OR in_array('0', $data['store_ids'])) {
                $data['store_ids'] = array('0');
            }
            $data['store_ids'] = implode(',', $data['store_ids']);

            /* Attachment Emails */
            if (!isset($data['attachment_email']) OR in_array('0', $data['attachment_email'])) {
                $data['attachment_email'] = array('0');
            }
            $data['attachment_email'] = implode(',', $data['attachment_email']);

            /* Dates */
            if ($data['start_date'] == '') {
                $data['start_date'] = NULL;
            } else {
                $date = Mage::app()->getLocale()->date($data['start_date'], Zend_Date::DATE_SHORT);
                $data['start_date'] = $date->toString('YYYY-MM-dd');
            }
            if ($data['end_date'] == '') {
                $data['end_date'] = NULL;
            } else {
                $date1 = Mage::app()->getLocale()->date($data['end_date'], Zend_Date::DATE_SHORT);
                $data['end_date'] = $date1->toString('YYYY-MM-dd');
            }

            /* Save into the model */
            $model->setData($data)->setId($this->getRequest()->getParam('form_id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('formprocessor')->__('Form was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('form_id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('form_id' => $this->getRequest()->getParam('form_id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('formprocessor')->__('Unable to find Form to save'));
        $this->_redirect('*/*/');
    }

    /**
     * Delete Action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('form_id') > 0) {
            try {
                $model = Mage::getModel('formprocessor/forms');

                $model->setId($this->getRequest()->getParam('form_id'))->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('formprocessor')->__('Form was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('form_id' => $this->getRequest()->getParam('form_id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Mass Delete Action
     */
    public function massDeleteAction()
    {
        $formIds = $this->getRequest()->getParam('formprocessor');
        if (!is_array($formIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('formprocessor')->__('Please select Form(s)'));
        } else {
            try {
                foreach ($formIds as $formId) {
                    $form = Mage::getModel('formprocessor/forms')->load($formId);

                    $form->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('formprocessor')->__('Total of %d record(s) were successfully deleted', count($formIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Mass Status Action
     */
    public function massStatusAction()
    {
        $formIds = $this->getRequest()->getParam('formprocessor');
        if (!is_array($formIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Form(s)'));
        } else {
            try {
                foreach ($formIds as $formId) {
                    $forms = Mage::getSingleton('formprocessor/forms')
                        ->load($formId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($formIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Export CSV Action
     */
    public function exportCsvAction()
    {
        $fileName = 'formprocessor_forms.csv';
        $content = $this->getLayout()->createBlock('formprocessor/adminhtml_forms_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     * Import CSV Action
     */
    public function exportXmlAction()
    {
        $fileName = 'formprocessor_forms.xml';
        $content = $this->getLayout()->createBlock('formprocessor/adminhtml_forms_grid')->getXml();
        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     * Send Upload Response
     */
    protected function _sendUploadResponse($fileName, $content,
                                           $contentType = 'application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}
