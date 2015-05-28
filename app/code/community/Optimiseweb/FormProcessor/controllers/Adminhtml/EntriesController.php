<?php

/**
 * Optimiseweb FormProcessor Adminhtml Entries Controller
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (vkathirvel@gmail.com)
 * @copyright   Copyright (c) 2015 Kathir Vel
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Adminhtml_EntriesController extends Mage_Adminhtml_Controller_Action
{

    /**
     * INIT Action
     */
    protected function _initAction()
    {
        $this->loadLayout();
        $this->loadLayout()->_setActiveMenu('optimiseweball/formprocessor/entries');
        $this->loadLayout()->_addBreadcrumb(Mage::helper('formprocessor')->__('Forms Processor'), Mage::helper('formprocessor')->__('Forms Processor'));
        return $this;
    }

    /**
     * Index Action
     */
    public function indexAction()
    {
        $this->_initAction();
        $block = $this->getLayout()->createBlock('formprocessor/adminhtml_entries', 'formprocessor_entries');
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    /**
     * Edin Action
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('entry_id');
        $model = Mage::getModel('formprocessor/entries')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('formprocessor_entries_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('optimiseweball/formprocessor');
            $this->_addBreadcrumb(Mage::helper('formprocessor')->__('Entries Manager'), Mage::helper('formprocessor')->__('Entries Manager'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('formprocessor/adminhtml_entries_edit'));
            $this->_addLeft($this->getLayout()->createBlock('formprocessor/adminhtml_entries_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('formprocessor')->__('Entry does not exist'));
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
            $model = Mage::getModel('formprocessor/entries');

            // Save into the model
            $model->setData($data)->setId($this->getRequest()->getParam('entry_id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('formprocessor')->__('Entry was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('entry_id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('entry_id' => $this->getRequest()->getParam('entry_id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('formprocessor')->__('Unable to find Entry to save'));
        $this->_redirect('*/*/');
    }

    /**
     *
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('entry_id') > 0) {
            try {
                $model = Mage::getModel('formprocessor/entries');

                $model->setId($this->getRequest()->getParam('entry_id'))->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('formprocessor')->__('Entry was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('entry_id' => $this->getRequest()->getParam('entry_id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     *
     */
    public function massDeleteAction()
    {
        $entryIds = $this->getRequest()->getParam('formprocessor');
        if (!is_array($entryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('formprocessor')->__('Please select Entry(s)'));
        } else {
            try {
                foreach ($entryIds as $entryId) {
                    $entry = Mage::getModel('formprocessor/entries')->load($entryId);

                    $entry->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('formprocessor')->__('Total of %d record(s) were successfully deleted', count($entryIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     *
     */
    public function massStatusAction()
    {
        $entryIds = $this->getRequest()->getParam('formprocessor');
        if (!is_array($entryIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Entry(s)'));
        } else {
            try {
                foreach ($entryIds as $entryId) {
                    $entries = Mage::getSingleton('formprocessor/entries')
                        ->load($entryId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($entryIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     *
     */
    public function exportCsvAction()
    {
        $fileName = 'formprocessor_entries.csv';
        $content = $this->getLayout()->createBlock('formprocessor/adminhtml_entries_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     *
     */
    public function exportXmlAction()
    {
        $fileName = 'formprocessor_entries.xml';
        $content = $this->getLayout()->createBlock('formprocessor/adminhtml_entries_grid')->getXml();
        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     *
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
