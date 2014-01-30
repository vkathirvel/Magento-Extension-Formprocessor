<?php

/**
 * Optimiseweb Ctas Installer
 *
 * @package     Optimiseweb_Ctas
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2013 Optimise Web Limited
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('ow_forms')}
	ADD COLUMN `custom_processor_filename` VARCHAR(255) NULL DEFAULT NULL AFTER `description`,
	ADD COLUMN `newsletter_subscribe` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT 'Newsletter Subscription' AFTER `visitor_email_template`,
	ADD COLUMN `newsletter_subscribe_confirm` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT 'Newsletter Subscription Confirmation' AFTER `newsletter_subscribe`;

");

$installer->endSetup();
