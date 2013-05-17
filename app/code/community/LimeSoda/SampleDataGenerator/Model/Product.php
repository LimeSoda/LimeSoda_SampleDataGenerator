<?php

class LimeSoda_SampleDataGenerator_Model_Product extends LimeSoda_SampleDataGenerator_Model_Entity
{
    const VALID_CHARACTERS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789ÄÖÜäöüß -/.!?";
    
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
        if ($count === 0) {
            return array();
        } elseif ($count > count($sourceArray)) {
            $count = count($sourceArray);
        }

        $keys = array_rand($sourceArray, $count);
        
        if (!is_array($keys)) {
            $keys = array($keys);
        }
        
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
        
        $productIds = Mage::getModel('catalog/product')->getCollection()->getAllIds();
        
        if (!empty($productIds)) {
            $maxId = max($productIds) !== false ? max($productIds) : 0;
        } else {
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
     * - 75% of attributes set
     * - multi selects: between 0 and max. 4 values
     * - simple selects: 50% probability of attributes set have a value set
     * - text in all text attributes. Strings between 4 and 80 chars
     * 
     * @todo remove fixed values
     * @param Mage_Catalog_Model_Product $product
     * @return void
     */
    protected function _addCustomData(Mage_Catalog_Model_Product $product)
    {
         $attributes = $this->_getRandomAttributes($product->getAttributeSetId(), 75);
         
         foreach ($attributes as $attribute) {
             /**
              * @todo remove hard coded string.
              */    
             if (strpos($attribute['code'], 'sample_attribute_') === false) {
                 continue;
             }   
             
             switch ($attribute['type']) {
                 case 'multiselect':
                     $values = $this->_getRandomValues($this->_getAttributeOptionValues($attribute['attribute_id']), rand(0,4));
                     $product->setData($attribute['code'], $values);
                     break;
                 case 'select':
                     $setValue = rand(0,1);
                     $value = ($setValue === 1 ? $this->_getRandomValue($this->_getAttributeOptionValues($attribute['attribute_id'])) : 0);
                     $product->setData($attribute['code'], $value);
                     break;
                 case 'text':
                     $product->setData($attribute['code'], $this->_getRandomString(self::VALID_CHARACTERS, rand(4,80)));
                     break;
             }
         }

    }
    
    /**
     * @todo document, move
     */
    protected $_attributeCache = array(); 
    
    /**
     * Returns the options for an attribute.
     * 
     * @todo move
     * 
     * @param int $id
     * @return array
     */
    protected function _getAttributeOptionValues($id)
    {
        if (!array_key_exists($id, $this->_attributeCache)) {
             $model = Mage::getModel("catalog/product_attribute_api");
             
             $values = array();
             foreach ($model->options($id) as $option) {
                 $values[] = $option['value'];
             }
             $this->attributeCache[$id] = $values;
        }
        return $this->attributeCache[$id];
    }
    
    /**
     * Returns a random value from an array.
     * 
     * @todo move
     * @param array $data
     * @return mixed
     */
    protected function _getRandomValue(array $data)
    {
        $key = array_rand($data);
        return $data[$key];
    }
    
    /**
     * Returns random values from an array.
     * 
     * @todo: make sure that not more values are selected than are possible for this element.
     * 
     * @todo move
     * @param array $data
     * @param int $count
     * @return mixed
     */
    protected function _getRandomValues(array $data, $count)
    {
        if ($count === 0) {
            return null;
        }
        
        $keys = array_rand($data, $count);

        if (!is_array($keys)) {
            return $data[$keys];
        } 
        
        $result = array();
        foreach ($keys as $key) {
            $result[] = $data[$key];
        }
        return $result;
        
    }
    
    /**
     * @todo move (maybe to own helper class or use Magento standard one if one exists?)
     * @todo document
     * @todo rewrite according to coding standards
     */
    protected function _getRandomString($valid_chars, $length)
    {
        // start with an empty random string
        $random_string = "";
    
        // count the number of chars in the valid chars string so we know how many choices we have
        $num_valid_chars = strlen($valid_chars);
    
        // repeat the steps until we've created a string of the right length
        for ($i = 0; $i < $length; $i++)
        {
            // pick a random number from 1 up to the number of valid chars
            $random_pick = mt_rand(1, $num_valid_chars);
    
            // take the random character out of the string of valid chars
            // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
            $random_char = $valid_chars[$random_pick-1];
    
            // add the randomly-chosen char onto the end of our string so far
            $random_string .= $random_char;
        }
    
        // return our finished random string
        return $random_string;
        }
    
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
        
        $product->setPrice(rand(1,500));
        $product->setTaxClassId(2);
        
        $product->setCategoryIds($categoryAssignmentIds);
        $product->setWebsiteIDs($websiteIds);
        
        /**
         * @todo Move to event observer.
         */ 
        $this->_addCustomData($product);

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
    
    /**
     * @todo move, document
     */
    protected $_attributeSetCache = array();

    /**
     * Returns random attributes from the attribute set.
     * 
     * @param int $setId
     * @param int $percentage Percentage of total attributes
     * @return array 
     */
    protected function _getRandomAttributes($setId, $percentage)
    {
        $attributes = $this->_getAttributesForSet($setId);

        $totalCount = count($attributes);
        $useCount = round($totalCount * $percentage / 100);
        
        $randomKeys = array_rand($attributes, $useCount);
        
        $result = array();
        
        if (is_array($randomKeys)) {
            foreach ($randomKeys as $key) {
                $result[] = $attributes[$key];
            }    
        } else {
            $result[] = $attributes[$randomKeys];
        }
        
        return $result;
    }
    
    /**
     * @todo move, document
     */
    protected function _getAttributesForSet($setId)
    {
        if (!array_key_exists($setId, $this->_attributeSetCache)) {
             $model = Mage::getModel("catalog/product_attribute_api");
             $this->_attributeSetCache[$setId] = $model->items($setId);
             
        }
        return $this->_attributeSetCache[$setId];
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