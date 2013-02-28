<?php

class LimeSoda_SampleDataGenerator_Model_Rule extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ls_sampledatagenerator/rule');
    }
    
    /**
     * @todo: add column to table, write de- and serialisation get/set methods
     */
    public function getCategoryOptions()
    {
        return array();
        
        return array(
            'categories' => array(
                'min_count' => 7,
                'max_count' => 7,
                'subcategories' => array(
                    'categories' => array(
                        'min_count' => 6,
                        'max_count' => 6,
                        'subcategories' => array(
                            'categories' => array(
                                'min_count' => 3,
                                'max_count' => 3,
                                'subcategories' => array()
                            )
                        )
                    )
                )
            )
        );
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
