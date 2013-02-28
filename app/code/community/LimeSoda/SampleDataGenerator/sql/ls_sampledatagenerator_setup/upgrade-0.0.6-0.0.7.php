<?php
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('ls_sampledatagenerator/rule'),
    'product_attribute_set_min_count',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned'  => true,
        'nullable'  => false,
        'comment'   => 'Minimum number of product attribute sets to create'
    )
);

$installer->getConnection()->addColumn(
    $installer->getTable('ls_sampledatagenerator/rule'),
    'product_attribute_set_max_count',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned'  => true,
        'nullable'  => false,
        'comment'   => 'Maxiumum number of product attribute sets to create'
    )
);

$installer->endSetup();
