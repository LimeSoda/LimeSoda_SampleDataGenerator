<?php
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('ls_sampledatagenerator/rule'),
    'add_store_groups_only_to_new_websites',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned'  => true,
        'nullable'  => false,
        'comment'   => 'Minimum number of store views to create per store group'
    )
);

$installer->getConnection()->addColumn(
    $installer->getTable('ls_sampledatagenerator/rule'),
    'add_store_views_only_to_new_store_groups',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned'  => true,
        'nullable'  => false,
        'comment'   => 'Maximum number of store views to create per store group'
    )
);

$installer->endSetup();
