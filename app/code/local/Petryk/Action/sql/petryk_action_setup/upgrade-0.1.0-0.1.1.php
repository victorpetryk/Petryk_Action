<?php
/**
 * Upgrade script from 0.1.0 to 0.1.1
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('petryk_action/action');

$installer->getConnection()->addColumn($tableName, 'status', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'unsigned' => true,
    'nullable' => false,
    'default' => '1',
    'after' => 'is_active',
    'comment' => 'Action Status',
));

$installer->endSetup();
