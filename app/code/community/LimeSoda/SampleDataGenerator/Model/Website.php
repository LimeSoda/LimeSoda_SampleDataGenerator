<?php

class LimeSoda_SampleDataGenerator_Model_Website
{
    /**
     * Default options for the website model.
     * 
     * @var array
     */
    protected $_defaultOptions = array(
        'min_count' => 0,
        'max_count' => 0
    );
    
    /**
     * Creates the websites.
     * 
     * @param array $options
     * @return array Array with IDs of created websites. 
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
        $count = rand($options['min_count'], $options['max_count']);
        $maxId = max(Mage::getModel('core/website')->getCollection()->getAllIds());
        
        for ($i = 1; $i <= $count; $i++) {
            $website = Mage::getModel('core/website');
            $website->setCode('website_' . ($maxId + $i));
            $website->setName('Website ' . ($maxId + $i));
            $website->save();
            $results[] = $website->getId();
        }
        
        if (count($results) === 1) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('1 website has been generated.')
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('%s websites have been generated.', count($results))
            );
        }
        
        return $results;
    }    
}