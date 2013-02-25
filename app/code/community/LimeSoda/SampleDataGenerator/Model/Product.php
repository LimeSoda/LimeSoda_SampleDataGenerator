<?php

class LimeSoda_SampleDataGenerator_Model_Product extends LimeSoda_SampleDataGenerator_Model_Entity
{
    /**
     * Default options for the website model.
     * 
     * @var array
     */
    protected $_defaultOptions = array(
        'min_count' => 0,
        'max_count' => 0,
        'min_category_assignments_count' => 0,
        'max_category_assignments_count' => 0,
    );
    
    /**
     * Returns an array of all attribute set ids in the system.
     * 
     * @return array
     */
    protected function _getAttributeSetIds()
    {
        $result = array();
        
        foreach(Mage::getModel('catalog/product_attribute_set_api')->items() as $item) {
            $result[] = $item['set_id'];
        }
        
        return $result;
    }
    
    /**
     * Returns an array with $count random entries of the provided array.
     * 
     * @param array $sourceArray
     * @param int $count
     * @return array
     */
    protected function _getRandomSelection(array $sourceArray, $count)
    {
        $keys = array_rand($sourceArray, $count);
        
        $result = array();
        foreach ($keys as $key) {
            $result[] = $sourceArray[$key];
        }
        
        return $result;
    }
    
    /**
     * Creates the products.
     * 
     * @param array $options
     * @return array Array with ids of created products
     */
    public function create(array $options)
    {
        $options = array_merge($this->_defaultOptions, $options);
        
        if ($options['min_count'] > $options['max_count']) {
            throw new DomainException("Minimum count must not be bigger than maximum count.");
        } elseif ($options['min_count'] == 0 && $options['max_count'] == 0) {
            return array();
        }
        
        $count = rand($options['min_count'], $options['max_count']);
        
        $maxId = max(Mage::getModel('catalog/product')->getCollection()->getAllIds());
        if ($maxId === false) {
            $maxId = 0;
        }
        
        $attributeSetIds = $this->_getAttributeSetIds();
        $categoryIds = Mage::getModel('catalog/category')->getCollection()->getAllIds();
        $websiteIds = Mage::getModel('core/website')->getCollection()->getAllIds();
        
        $results = array();
        for ($i = 1; $i <= $count; $i++) {
            $nextId = $maxId + $i;
            $categoryAssignmentIds = $this->_getRandomSelection($categoryIds, rand($options['min_category_assignments_count'],$options['max_category_assignments_count']));
            $results[] = $this->_createProduct($nextId, $attributeSetIds, $categoryAssignmentIds, $websiteIds);
        }
        
        if (count($results) === 1) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('1 product has been generated.')
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('%s products have been generated.', count($results))
            );
        }
        
        return $results;
    }    
    
    private $_products_deleted = 0;
    
    /**
     * Creates a product.
     * 
     * @param int $nextId Next available product id
     * @param array $attributeSetIds
     * @param array $categoryAssignmentIds
     * @param array $websiteIds
     * @return int The id of the created product
     */
    protected function _createProduct($nextId, array $attributeSetIds, array $categoryAssignmentIds, array $websiteIds)
    {
        $product = Mage::getModel('catalog/product');
        
        // Build the product
        $product->setAttributeSetId($attributeSetIds[array_rand($attributeSetIds)]);
        $product->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE);
        
        $product->setName('Product Name ' . $nextId);
        $product->setDescription('Product Description ' . $nextId);
        $product->setShortDescription('Product Short Description ' . $nextId);
        $product->setSku('sample_product_sku_' . $nextId);
        $product->setWeight(1.0000);
        $product->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
        $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
        
        $product->setPrice(1.00);
        $product->setTaxClassId(2);
        
        $product->setCategoryIds($categoryAssignmentIds);
        $product->setWebsiteIDs($websiteIds);

        $product->save();
        
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

        return $product->getId();
    }
    
    public function deleteProducts()
    {
        /**
         * @todo: refactor / adjust to new code
        $product_id = Mage::getModel('catalog/product')->getIdBySku("sample_product_sku_".($this->_products_deleted+1));
        if($product_id){
            $product = Mage::getModel('catalog/product_api');
            $product->delete($product_id);
            $this->_products_deleted++;
            $this->deleteProducts();
        }
         */ 
    }
    
}