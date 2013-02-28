<?php
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('ls_sampledatagenerator/rule'),
    'add_product_attributes_only_to_new_product_attribute_sets',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned'  => true,
        'nullable'  => false,
        'comment'   => 'Should product attributes only be added to new product attribute sets?'
    )
);

$installer->endSetup();
