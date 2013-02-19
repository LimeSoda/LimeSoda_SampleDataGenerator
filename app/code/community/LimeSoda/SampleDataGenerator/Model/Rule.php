<?php

class LimeSoda_SampleDataGenerator_Model_Rule extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ls_sampledatagenerator/rule');
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
    
    
}
