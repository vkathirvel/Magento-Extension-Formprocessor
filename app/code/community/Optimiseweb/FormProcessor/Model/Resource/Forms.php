<?php

/**
 * Optimiseweb FormProcessor Model Resource Forms
 *
 * @package     Optimiseweb_FormProcessor
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Optimiseweb_FormProcessor_Model_Resource_Forms extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     *
     */
    public function _construct()
    {
        $this->_init('formprocessor/forms', 'form_id');
    }

}
