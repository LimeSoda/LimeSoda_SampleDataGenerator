<?php

class LimeSoda_SampleDataGenerator_Model_Rule extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix = 'ls_sampledatagenerator_rule';
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('ls_sampledatagenerator/rule');
    }
    
    /**
     * Returns the options for categories.
     * 
     * @return array
     */
    public function getCategoryOptions()
    {
        $categories = $this->getCategories();
        $result = array();
        
        foreach (array_reverse($categories) as $category) {
            
            $category['subcategories'] = $result;
            
            $result = array(
                'categories' => $category
            );
        }
        
        return $result;
    }
    
    /**
     * Returns whether at least one product attribute should be created.
     * 
     * @return boolean
     */
    public function shouldProductAttributesBeCreated()
    {
        return $this->getProductAttributeMinCount() > 0;
    }
    
    /**
     * Returns whether at least one product attribute set should be created.
     * 
     * @return boolean
     */
    public function shouldProductAttributeSetsBeCreated()
    {
        return $this->getProductAttributeSetMinCount() > 0;
    }
    
    /**
     * Returns whether at least one website should be created.
     * 
     * @return boolean
     */
    public function shouldProductsBeCreated()
    {
        return $this->getProductMinCount() > 0;
    }
    
    /**
     * Returns whether at least one store group should be created.
     * 
     * @return boolean
     */
    public function shouldStoreGroupsBeCreated()
    {
        return $this->getStoreGroupMinCount() > 0;
    }
    
    /**
     * Returns whether at least one store group should be created.
     * 
     * @return boolean
     */
    public function shouldStoreViewsBeCreated()
    {
        return $this->getStoreViewMinCount() > 0;
    }    
    
    /**
     * Returns whether at least one website should be created.
     * 
     * @return boolean
     */
    public function shouldWebsitesBeCreated()
    {
        return $this->getWebsiteMinCount() > 0;
    }
    
}
