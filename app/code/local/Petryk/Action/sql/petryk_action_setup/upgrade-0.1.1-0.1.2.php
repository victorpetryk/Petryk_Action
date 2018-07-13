<?php

/**
 * Upgrade script from 0.1.1 to 0.1.1
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$actionProductTableName = $installer->getTable('petryk_action/action_product');
$actionTableName = $installer->getTable('petryk_action/action');
$productTableName = $installer->getTable('catalog/product');

$table = $installer->getConnection()->newTable($actionProductTableName)
    ->addColumn('ap_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Action Product ID')
    ->addColumn('action_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Action ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Product ID')
    ->addForeignKey('FK_AP_ACTION', 'action_id', $actionTableName, 'action_id', Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey('FK_AP_PRODUCT', 'product_id', $productTableName, 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Action Product Table');

$installer->getConnection()->createTable($table);

$installer->endSetup();
