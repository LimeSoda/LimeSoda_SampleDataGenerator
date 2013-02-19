<?php

class LimeSoda_SampleDataGenerator_Model_StoreView
{
    /**
     * Default options for the model.
     * 
     * @var array
     */
    protected $_defaultOptions = array(
        'min_count' => 0,
        'max_count' => 0
    );
    
    /**
     * Returns the websites where store groups should be added to.
     * 
     * @param int $websiteId
     * @param array $streGroupIds
     * @return array
     */
    protected function _getStoreGroups($websiteId, array $storeGroupIds)
    {
        $websiteStoreGroups = Mage::getModel('core/store_group')->getCollection()->addWebsiteFilter($websiteId)->getAllIds();
        
        if (empty($storeGroupIds)) {
            return $websiteStoreGroups;
        }
        
        return array_intersect($websiteStoreGroups, $storeGroupIds);
    }
    
    
    /**
     * Returns the websites where store groups should be added to.
     * 
     * @param array $ids
     * @return array
     */
    protected function _getWebsites(array $ids)
    {
        return empty($ids) ? Mage::getModel('core/website')->getCollection()->getAllIds() : $ids;
    }
    
    /**
     * Creates the store groups.
     * 
     * @param array $options
     * @return array Array with IDs of created store groups. 
     */
    public function create(array $options)
    {
        $options = array_merge($this->_defaultOptions, $options);
        
        if ($options['min_count'] > $options['max_count']) {
            throw new DomainException("Minimum count must not be bigger than maximum count.");
        } elseif ($options['min_count'] == 0 && $options['max_count'] == 0) {
            return array();
        }
        
        $result = array();
        $storeViewId = max(Mage::getModel('core/store')->getCollection()->getAllIds());
        
        foreach ($this->_getWebsites($options['website_ids']) as $websiteId) {
            
            if ($websiteId == Mage_Core_Model_App::ADMIN_STORE_ID) {
                continue;
            }
            
            foreach ($this->_getStoreGroups($websiteId, $options['store_group_ids']) as $storeGroupId) {
                
                $count = rand($options['min_count'], $options['max_count']);
                
                for ($i = 1; $i <= $count; $i++) {
                    $storeViewId++;
                    
                    $model = Mage::getModel('core/store');
                    $model->setCode('storeview_' . $storeViewId)
                        ->setWebsiteId($websiteId)
                        ->setGroupId($storeGroupId)
                        ->setName('Store View ' . $storeViewId)
                        ->setIsActive(1)
                        ->save();
                    $results[] = $model->getId();    
                }
            }
        }
        
        if (count($results) === 1) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('1 store group has been generated.')
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('%s store groups have been generated.', count($results))
            );
        }

        return $results;
    }    
}