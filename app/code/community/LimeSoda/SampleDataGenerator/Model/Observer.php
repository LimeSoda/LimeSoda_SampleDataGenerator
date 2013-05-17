<?php

class LimeSoda_SampleDataGenerator_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     * @return LimeSoda_SampleDataGenerator_Model_Observer
     */
    public function ruleLoadAfter(Varien_Event_Observer $observer)
    {
        $model = $observer->getEvent()->getObject();
        
        $model->setData('categories', unserialize($model->getCategories()));
        
        return $this;
    }
    
    /**
     * @param Varien_Event_Observer $observer
     * @return LimeSoda_SampleDataGenerator_Model_Observer
     */
    public function ruleSaveBefore(Varien_Event_Observer $observer)
    {
        $model = $observer->getEvent()->getObject();
        
        $categories = $model->getCategories() === null ? serialize(array()) : serialize($model->getCategories()); 
        $model->setData('categories', $categories);
        
        return $this;
    }
}
