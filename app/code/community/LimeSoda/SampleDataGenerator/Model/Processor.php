<?php

class LimeSoda_SampleDataGenerator_Model_Processor
{
    protected $_config = array();
    
    /**
     * Generates the sample data according to the rules.
     * 
     * @refactor: extract methods for websites, store groups, ...
     * @refactor: automate options arrays / build separate option objects so that option array don't have to be built here.
     * 
     * @param LimeSoda_SampleDataGenerator_Model_Rule
     * @return LimeSoda_SampleDataGenerator_Model_Processor
     */
    public function generateData(LimeSoda_SampleDataGenerator_Model_Rule $rule)
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
        
        if ($rule->shouldStoreGroupsBeCreated()) {
            $storeGroupModel = Mage::getModel('ls_sampledatagenerator/storeGroup');
            
            $options = array(
                'min_count' => $rule->getStoreGroupMinCount(),
                'max_count' => $rule->getStoreGroupMaxCount()
            );
            
            if ($rule->getAddStoreGroupsOnlyToNewWebsites()) {
                $options['website_ids'] = $websiteIds;
            }
            
            $storeGroupIds = $storeGroupModel->create($options);
        } else {
            $storeGroupIds = array();
        }
        
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
            
            $storeViewModel->create($options);
        }
        
        return $this;
        
        /**
         * @todo: rewrite code
         *
        $categoryModel = Mage::getModel('ls_sampledatagenerator/category');
        $categoryModel->setConfig($this->_config)->createCategories();
        
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
        $productModel = Mage::getModel('ls_sampledatagenerator/product');
        $productModel->setConfig($this->_config)->deleteProducts();

        $setModel = Mage::getModel('ls_sampledatagenerator/productAttributeSets');
        $setModel->setConfig($this->_config)->deleteAttributeSets();
        
        $attributeModel = Mage::getModel('ls_sampledatagenerator/productAttributes');
        $attributeModel->setConfig($this->_config)->deleteAttributes();
        
        $categoryModel = Mage::getModel('ls_sampledatagenerator/category');
        $categoryModel->setConfig($this->_config)->deleteCategories();
    }
    
}
