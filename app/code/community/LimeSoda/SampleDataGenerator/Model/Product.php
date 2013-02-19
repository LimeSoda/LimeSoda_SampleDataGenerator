<?php

class LimeSoda_SampleDataGenerator_Model_Product extends LimeSoda_SampleDataGenerator_Model_Entity
{
    protected $_attributeModel = null;
    protected $_categoryModel = null;
    protected $_setModel = null;
    
    private $_products_created = Array();
    private $_products_deleted = 0;
    
    /**
     * @refactor: sollte nicht vervielfacht sein, wird außerdem nicht richtig gesetzt, da das im Konstruktor vom Processor gemacht wird.
     */
    private $numOfAttributesPerSet = 5;
    
    private function _createProduct($prodItem)
    {
        if($this->_config["attributesets"]["items"] == 0){
            $setItem = 0;
        }else{
            $setItem = $prodItem;
        }
        $product = Mage::getModel('catalog/product');

        // Build the product
        $product->setAttributeSetId($this->_setModel[$setItem]);
        $product->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE);
        
        $product->setName('Product Name ' . ($prodItem+1));
        $product->setDescription('Product Description ' . ($prodItem+1));
        $product->setShortDescription('Product Short Description ' . ($prodItem+1));
        $product->setSku('sample_product_sku_' . ($prodItem+1));
        $product->setWeight(1.0000);
        $product->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
        $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
        
        $product->setPrice(1.00);
        $product->setTaxClassId(2);
        
        if($this->_config["attributesets"]["items"] > 0){
            
            $assignedAttributes = $this->_attributeModel->getAssignedAttributes();
            for($i=0; $i<count($assignedAttributes[$this->_setModel[$setItem]]); $i++){
                    $attID = $assignedAttributes[$this->_setModel[$setItem]][$i];
                
                    $product->setData('sample_attribute_'.($attID+1),"sample_value_".($attID+1));
            }
        
        }else{
            $attsCreated = Array();
            $productAttributeCount = count($this->_attributeModel->getCreatedAttributes());
            for($i = 0; $i < $this->numOfAttributesPerSet; $i++){
            
                $attID = rand(1,$productAttributeCount);
                
                if (in_array($attID, $attsCreated)) {
                    $i--;
                }else{
                    $attsCreated[] = $attID;
                    $product->setData('sample_attribute_'.$attID,"sample_value_".$attID);
                }
                
            }
            
        }
        
        $catsCreated = $this->_categoryModel->getCreatedCategories();
        $product->setCategoryIds(array($catsCreated[$prodItem]));
        $product->setWebsiteIDs(array(1));

        //SAVE
        $product->save();
        $this->_products_created[] = $product->getId();
        
        $stockItem = Mage::getModel('cataloginventory/stock_item');
        $stockItem->loadByProduct($product->getId());
        $stockItem->assignProduct($product);
        $stockItem->setData('product_id',$product->getId());
        $stockItem->setData('is_in_stock', 1);
        $stockItem->setData('qty',1);
        $stockItem->setData('manage_stock', 0);
        $stockItem->setData('stock_id', 1);
        $stockItem->setData('use_config_manage_stock', 1);
        $stockItem->save();

    }
    
    public function createProducts($setModel, $attributeModel, $categoryModel)
    {
        $this->_attributeModel = $attributeModel;
        $this->_categoryModel = $categoryModel;
        $this->_setModel = $setModel;
        
        for ($i = 0; $i < $this->_config["products"]["items"]; $i++){
            $this->_createProduct($i);
        }
        
        $this->_debug_msg .= "[INFO] - " . count($this->_products_created) . " products created<br/>";
    }
    
    public function deleteProducts()
    {
        $product_id = Mage::getModel('catalog/product')->getIdBySku("sample_product_sku_".($this->_products_deleted+1));
        if($product_id){
            $product = Mage::getModel('catalog/product_api');
            $product->delete($product_id);
            $this->_products_deleted++;
            $this->deleteProducts();
        }
    }
    
}