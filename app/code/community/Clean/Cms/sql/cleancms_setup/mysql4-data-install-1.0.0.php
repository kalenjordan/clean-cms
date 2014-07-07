<?php

/**
 * @var $this Mage_Core_Model_Resource_Setup
 */

$installer = $this;
$installer->startSetup();



try {
    $table = $this->getConnection()->newTable($this->getTable('cleancms/fieldset'));
    $table
        ->addColumn(
            'fieldset_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'primary'  => true,
                'unsigned' => true,
                'nullable' => false,
            )
        )
        ->addColumn(
            'page_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            11
        )
        ->addColumn(
            'sort_order',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            11
        )
        ->addColumn(
            'type',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255
        )
        ->addColumn(
            'css_classes',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME
        );
    $this->getConnection()->createTable($table);

    $table = $this->getConnection()->newTable($this->getTable('cleancms/field'));
    $table
        ->addColumn(
            'field_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'primary'  => true,
                'unsigned' => true,
                'nullable' => false,
            )
        )
        ->addColumn(
            'page_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            11
        )
        ->addColumn(
            'fieldset_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            11
        )
        ->addColumn(
            'field_identifier',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            255
        )
        ->addColumn(
            'value',
            Varien_Db_Ddl_Table::TYPE_TEXT
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME
        );
    $this->getConnection()->createTable($table);
} catch (Exception $e) {
    Mage::logException($e);
}

