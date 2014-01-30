<?php

/**
 * Optimiseweb FormProcessor Model Forms
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2013 Optimise Web Limited
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Model_Forms extends Mage_Core_Model_Abstract
{

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('formprocessor/forms');
    }

    /**
     * After Load
     *
     * @param type $id
     * @return boolean
     */
    public function loadAndPrepare($id)
    {
        $form = $this->load($id);

        if ($form->getId()) {
            Mage::helper('formprocessor')->modelPrepare($form);
            return $form;
        }

        return FALSE;
    }

    /**
     * Get All Form Names as Options Array
     *
     * @return type
     */
    public function getFormsAsOptionsArray()
    {
        $forms = $this->getCollection();
        $formsArray = array('0' => 'Unfiled');
        foreach ($forms as $form) {
            $formsArray[$form->form_id] = $form->description;
        }
        return $formsArray;
    }

}
