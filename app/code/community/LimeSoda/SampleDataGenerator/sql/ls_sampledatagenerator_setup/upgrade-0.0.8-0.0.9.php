<?php
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('ls_sampledatagenerator/rule'),
    'categories',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => false,
        'comment'   => 'Categories configuration'
    )
);

$installer->endSetup();
