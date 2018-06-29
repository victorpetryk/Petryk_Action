<?php
/**
 * Install script
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('petryk_action/action');

$installer->getConnection()->dropTable($tableName);

$table = $installer->getConnection()->newTable($tableName)
    ->addColumn('action_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Action ID')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '1',
    ), 'Is Action Active')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, '255', array(
        'nullable' => false,
    ), 'Action Name')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'Action Description')
    ->addColumn('short_description', Varien_Db_Ddl_Table::TYPE_VARCHAR, '255', array(
        'nullable' => true,
    ), 'Action Short Description')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, '255', array(
        'nullable' => true,
    ), 'Action Image')
    ->addColumn('create_datetime', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Action Create Date and Time')
    ->addColumn('start_datetime', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'Action Start Date and Time')
    ->addColumn('end_datetime', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Action End Date and Time')
    ->setComment('Action Table');

$installer->getConnection()->createTable($table);

$installer->endSetup();
