<?php
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('ls_sampledatagenerator/rule'),
    'store_view_min_count',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned'  => true,
        'nullable'  => false,
        'comment'   => 'Minimum number of store views to create per store group'
    )
);

$installer->getConnection()->addColumn(
    $installer->getTable('ls_sampledatagenerator/rule'),
    'store_view_max_count',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned'  => true,
        'nullable'  => false,
        'comment'   => 'Maximum number of store views to create per store group'
    )
);

$installer->endSetup();
