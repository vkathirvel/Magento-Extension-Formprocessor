<?php

/**
 * Optimiseweb FormProcessor Model Resource Entries
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (vkathirvel@gmail.com)
 * @copyright   Copyright (c) 2015 Kathir Vel
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Model_Resource_Entries extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     *
     */
    public function _construct()
    {
        $this->_init('formprocessor/entries', 'entry_id');
    }

}
