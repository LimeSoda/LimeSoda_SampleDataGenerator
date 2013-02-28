<?php

class LimeSoda_SampleDataGenerator_Model_ProductAttributeSet extends LimeSoda_SampleDataGenerator_Model_Entity
{
    /**
     * @refactor Value should be read dynamically.
     * 
     * @var int
     */
    const DEFAULT_ATTRIBUTE_SET_ID = 4;
    
    /**
     * Default options for the model.
     * 
     * @var array
     */
    protected $_defaultOptions = array(
        'min_count' => 0,
        'max_count' => 0,
    );
    
    private $_attsets_deleted = 0;
    
    /**
     * Creates the product attribute ses.
     * 
     * @param array $options
     * @return array Array with IDs of created entities 
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
        $attributeSetId = max(Mage::getModel('eav/entity_attribute_set')->getCollection()->getAllIds());
        
        $count = rand($options['min_count'], $options['max_count']);
        
        for ($i = 1; $i <= $count; $i++) {
            $attributeSetId++;
                    
            $model = Mage::getModel("catalog/product_attribute_set_api");
            $results[] = $model->create("sample_attributeset_{$attributeSetId}", self::DEFAULT_ATTRIBUTE_SET_ID);
            
        }
        
        if (count($results) === 1) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('1 attribute set has been generated.')
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('%s attribute sets have been generated.', count($results))
            );
        }

        return $results;
    }  
    
    public function deleteAttributeSets(){
        /*
         * @todo rewrite code
        $_attributeset_id = Mage::getModel('eav/entity_attribute_set')->load("sample_attributeset_".($this->_attsets_deleted+1), 'attribute_set_name')->getAttributeSetId();
        if($_attributeset_id){
            $attributeset_model = Mage::getModel("catalog/product_attribute_set_api");
            $attributeset_model->remove($_attributeset_id); 
            $this->_attsets_deleted++;
            $this->deleteAttributeSets();
        }
        $this->_debug_msg .= "[INFO] - " . $this->_attsets_deleted . " attributesets deleted<br/>";
         */
    }
    
}