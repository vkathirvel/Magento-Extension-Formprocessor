<?php

/**
 * Optimiseweb FormProcessor Block Entries
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (vkathirvel@gmail.com)
 * @copyright   Copyright (c) 2015 Kathir Vel
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Block_Entries extends Mage_Core_Block_Template
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
    public function getEntries()
    {
        if (!$this->hasData('formprocessor_entries')) {
            $this->setData('formprocessor_entries', Mage::registry('formprocessor_entries'));
        }
        return $this->getData('formprocessor_entries');
    }

}
