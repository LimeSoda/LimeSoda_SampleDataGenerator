<?php

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
require_once '../app/Mage.php';
umask(0);
Mage::app();

$startingId = 337;
$stoppingId = 345;

for ($id = $startingId; $id <= $stoppingId; $id++) {
    $attributeCode = "sample_attribute_{$id}";
    $_attribute = Mage::getModel('eav/entity_attribute')->loadByCode("catalog_product", $attributeCode);
    $_attribute_id = $_attribute->getId();

    if($_attribute_id){
        $attribute_model = Mage::getModel("catalog/product_attribute_api");
        $attribute_model->remove($_attribute_id);
        echo "Attribute '$attributeCode' deleted.<br>";
    } else {
        echo "Attribute '$attributeCode' skipped.<br>";
    }
}
