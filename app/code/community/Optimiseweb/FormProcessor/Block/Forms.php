<?php

/**
 * Optimiseweb FormProcessor Block Forms
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Forms extends Mage_Core_Block_Template
{

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
    }

    /**
     *
     * @return type
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     *
     * @return type
     */
    public function getForms()
    {
        if (!$this->hasData('formprocessor_forms')) {
            $this->setData('formprocessor_forms', Mage::registry('formprocessor_forms'));
        }
        return $this->getData('formprocessor_forms');
    }

}
