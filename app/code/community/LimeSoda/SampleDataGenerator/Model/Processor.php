<?php

/**
 * @refactor: automate options arrays / build separate option objects so that option arrays don't have to be built in the _generate methods.
 */
class LimeSoda_SampleDataGenerator_Model_Processor
{
    protected $_config = array();
    
    /**
     * Generates the categories according to the rule.
     * 
     * @param LimeSoda_SampleDataGenerator_Model_Rule $rule
     * @return array Ids of generated categories
     */
    protected function _generateCategories(LimeSoda_SampleDataGenerator_Model_Rule $rule)
    {
        $categoryModel = Mage::getModel('ls_sampledatagenerator/category');
        $options = $rule->getCategoryOptions();
        $categoryIds = $categoryModel->create($options);
        
        return $categoryIds;
    }
    
    /**
     * Generates the products according to the rule.
     * 
     * @param LimeSoda_SampleDataGenerator_Model_Rule $rule
     * @return array Ids of generated products
     */
    protected function _generateProducts(LimeSoda_SampleDataGenerator_Model_Rule $rule)
    {
        if ($rule->shouldProductsBeCreated()) {
            $model = Mage::getModel('ls_sampledatagenerator/product');
            
            $options = array(
                'min_count' => $rule->getProductMinCount(),
                'max_count' => $rule->getProductMaxCount(),
                'min_category_assignments_count' => $rule->getProductMinCategoryAssignmentsCount(),
                'max_category_assignments_count' => $rule->getProductMaxCategoryAssignmentsCount()
            );
            
            $ids = $model->create($options);
        } else {
            $ids = array();
        }
        
        return $ids;
    }
    
    /**
     * Generates the store groups according to the rule.
     * 
     * @param LimeSoda_SampleDataGenerator_Model_Rule $rule
     * @param array Website Ids
     * @return array Ids of generated store groups
     */
    protected function _generateStoreGroups(LimeSoda_SampleDataGenerator_Model_Rule $rule, array $websiteIds)
    {
        if ($rule->shouldStoreGroupsBeCreated()) {
            $storeGroupModel = Mage::getModel('ls_sampledatagenerator/storeGroup');
            
            if ($rule->getAddStoreGroupsOnlyToNewWebsites()) {
                $options['website_ids'] = $websiteIds;
            }
            
            $storeGroupIds = $storeGroupModel->create($options);
        } else {
            $storeGroupIds = array();
        }
        
        return $storeGroupIds;
    }
    
    /**
     * Generates the store views according to the rule.
     * 
     * @param LimeSoda_SampleDataGenerator_Model_Rule $rule
     * @param array Website ids
     * @param array Store groups
     * @return array Ids of generated store views
     */
    protected function _generateStoreViews(LimeSoda_SampleDataGenerator_Model_Rule $rule, array $websiteIds, array $storeGroupIds)
    {
        if ($rule->shouldStoreViewsBeCreated()) {
            $storeViewModel = Mage::getModel('ls_sampledatagenerator/storeView');
            
            $options = array(
                'min_count' => $rule->getStoreViewMinCount(),
                'max_count' => $rule->getStoreViewMaxCount()  
            );
            
            if ($rule->getAddStoreViewsOnlyToNewStoreGroups()) {
                $options['website_ids'] = $websiteIds;
                $options['store_group_ids'] = $storeGroupIds;
            }
            
            $storeViewIds = $storeViewModel->create($options);
        } else {
            $storeViewIds = array();
        }
        
        return $storeViewIds;
    }
    
    /**
     * Generates the websites according to the rule.
     * 
     * @param LimeSoda_SampleDataGenerator_Model_Rule $rule
     * @return array Ids of generated websites
     */
    protected function _generateWebsites(LimeSoda_SampleDataGenerator_Model_Rule $rule)
    {
        if ($rule->shouldWebsitesBeCreated()) {
            $websiteModel = Mage::getModel('ls_sampledatagenerator/website');
            
            $options = array(
                'min_count' => $rule->getWebsiteMinCount(),
                'max_count' => $rule->getWebsiteMaxCount()  
            );
            
            $websiteIds = $websiteModel->create($options);
        } else {
            $websiteIds = array();
        }
        
        return $websiteIds;
    }
    
    /**
     * Generates the sample data according to the rules.
     * 
     * @param LimeSoda_SampleDataGenerator_Model_Rule
     * @return LimeSoda_SampleDataGenerator_Model_Processor
     */
    public function generateData(LimeSoda_SampleDataGenerator_Model_Rule $rule)
    {
        $websiteIds    = $this->_generateWebsites($rule);
        $storeGroupIds = $this->_generateStoreGroups($rule, $websiteIds);
        $storeViewIds  = $this->_generateStoreViews($rule, $websiteIds, $storeGroupIds);
        $categoryIds   = $this->_generateCategories($rule);
        $productIds    = $this->_generateProducts($rule);
        
        return $this;
        
        /**
         * @todo: rewrite code
         *
        $attributeModel = Mage::getModel('ls_sampledatagenerator/productAttributes');
        $attributeModel->setConfig($this->_config)->createAttributes();
        
        $setModel = Mage::getModel('ls_sampledatagenerator/productAttributeSets');
        $setModel->setConfig($this->_config)->createAttributeSets();
        
        $attributeAssignModel = Mage::getModel('ls_sampledatagenerator/attributeAssign');
        $attributeAssignModel->setConfig($this->_config)->assignAttributes($setModel->getAttributeSets(), $attributeModel->getCreatedAttributes());
        
        $productModel = Mage::getModel('ls_sampledatagenerator/product');
        $productModel->setConfig($this->_config)->createProducts($setModel, $attributeModel, $categoryModel);
         * 
         */
    }
    
    public function delete()
    {
        /**
         * @todo: rewrite code 
        $productModel = Mage::getModel('ls_sampledatagenerator/product');
        $productModel->setConfig($this->_config)->deleteProducts();

        $setModel = Mage::getModel('ls_sampledatagenerator/productAttributeSets');
        $setModel->setConfig($this->_config)->deleteAttributeSets();
        
        $attributeModel = Mage::getModel('ls_sampledatagenerator/productAttributes');
        $attributeModel->setConfig($this->_config)->deleteAttributes();
        
        $categoryModel = Mage::getModel('ls_sampledatagenerator/category');
        $categoryModel->setConfig($this->_config)->deleteCategories();
         */
    }
    
}
