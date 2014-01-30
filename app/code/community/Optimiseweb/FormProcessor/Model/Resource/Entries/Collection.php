<?php

/**
 * Optimiseweb FormProcessor Model Resource Entries Collection
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2013 Optimise Web Limited
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Model_Resource_Entries_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('formprocessor/entries');
    }

}
