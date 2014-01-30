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

-- DROP TABLE IF EXISTS {$this->getTable('ow_forms')};

CREATE TABLE {$this->getTable('ow_forms')} (
	`form_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`description` VARCHAR(255) NOT NULL COMMENT 'Friendly Name',
	`status` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT 'Status Enabled or Disabled',
	`start_date` DATETIME NULL DEFAULT NULL,
	`end_date` DATETIME NULL DEFAULT NULL,
	`store_ids` VARCHAR(255) NULL DEFAULT '0',
	`attachment_names` VARCHAR(255) NULL DEFAULT NULL,
	`attachment_allowed_extensions` VARCHAR(255) NULL DEFAULT 'jpg,jpeg,gif,png,pdf,xls,xlsx,txt,rtf,doc,docx',
	`attachment_save` SMALLINT(6) NOT NULL DEFAULT '0',
	`attachment_prepend_name` VARCHAR(255) NULL DEFAULT NULL,
	`attachment_email` VARCHAR(255) NULL DEFAULT NULL,
	`owner_enabled` SMALLINT(6) NOT NULL DEFAULT '0',
	`owner_sender` VARCHAR(255) NULL DEFAULT NULL,
	`owner_recipient_email` VARCHAR(255) NULL DEFAULT NULL,
	`owner_bcc` VARCHAR(255) NULL DEFAULT NULL,
	`owner_email_template` VARCHAR(255) NULL DEFAULT NULL,
	`visitor_enabled` SMALLINT(6) NOT NULL DEFAULT '0',
	`visitor_sender` VARCHAR(255) NULL DEFAULT NULL,
	`visitor_bcc` VARCHAR(255) NULL DEFAULT NULL,
	`visitor_email_template` VARCHAR(255) NULL DEFAULT NULL,
	`log_form_data` SMALLINT(6) NOT NULL DEFAULT '0',
	`redirect_url` VARCHAR(255) NULL DEFAULT NULL,
	`success_message` TEXT NULL,
	`error_message` TEXT NULL,
	`notice_message` TEXT NULL,
	`created_time` DATETIME NULL DEFAULT NULL,
	`update_time` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`form_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

-- DROP TABLE IF EXISTS {$this->getTable('ow_form_entries')};

CREATE TABLE {$this->getTable('ow_form_entries')} (
	`entry_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`form_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`store_id` VARCHAR(255) NULL DEFAULT '0',
	`name` VARCHAR(255) NULL DEFAULT NULL,
	`email` VARCHAR(255) NULL DEFAULT NULL,
	`all_data` TEXT NULL,
	`created_time` DATETIME NULL DEFAULT NULL,
	`update_time` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`entry_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

");

$installer->endSetup();
