<?php

class LimeSoda_SampleDataGenerator_Model_StoreGroup
{
    /**
     * Default options for the model.
     * 
     * @var array
     */
    protected $_defaultOptions = array(
        'min_count' => 0,
        'max_count' => 0,
        'website_ids' => array()
    );
    
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
        
        $results = array();
        $groupId = max(Mage::getModel('core/store_group')->getCollection()->getAllIds());
        $rootCategoryIds = Mage::getModel('catalog/category')->getCollection()->addRootLevelFilter()->getAllIds();
        
        foreach ($this->_getWebsites($options['website_ids']) as $website) {
            
            if ($website == Mage_Core_Model_App::ADMIN_STORE_ID) {
                continue;
            }
            
            $count = rand($options['min_count'], $options['max_count']);
            
            $rootCategoryId = $rootCategoryIds[array_rand($rootCategoryIds)];
            
            for ($i = 1; $i <= $count; $i++) {
                $groupId++;
                $model = Mage::getModel('core/store_group');
                $model->setWebsiteId($website)
                    ->setName('Store Group ' . $groupId)
                    ->setRootCategoryId($rootCategoryId)
                    ->save();
                $results[] = $model->getId();    
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