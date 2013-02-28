<?php

class LimeSoda_SampleDataGenerator_Model_ProductAttribute extends LimeSoda_SampleDataGenerator_Model_Entity
{
    /**
     * Default options for the model.
     * 
     * @var array
     */
    protected $_defaultOptions = array(
        'min_count' => 0,
        'max_count' => 0,
    );
    
    private $_atts_deleted = 0;
    
    /**
     * Creates the product attributes.
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
        $attributeId = max(Mage::getModel('eav/entity_attribute')->getCollection()->getAllIds());
        
        $count = rand($options['min_count'], $options['max_count']);
        
        for ($i = 1; $i <= $count; $i++) {
            $attributeId++;
                    
            $model = Mage::getModel("catalog/product_attribute_api");
            
            /**
             * @see http://www.magentocommerce.com/api/soap/catalog/catalogProductAttribute/product_attribute.create.html
             */
            $attributeData = array( 
                "attribute_code" => "sample_attribute_{$attributeId}",
                "frontend_input" => "text",
                "scope" => "store", // [store|website|global]
                "default_value" => "",
                "is_unique" => 0,
                "is_required" => 0,
                "apply_to" => array(),
                "is_configurable" => rand(0, 1),
                "is_searchable" => rand(0, 1),
                "is_visible_in_advanced_search" => rand(0, 1),
                "is_comparable" => rand(0, 1),
                "is_used_for_promo_rule" => rand(0, 1),
                "is_visible_on_front" => rand(0, 1),
                "used_in_product_listing" => rand(0, 1),
                "additional_fields" => array(),
                "frontend_label" => array(array("store_id" => 0, "label" => "Sample Attribute {$attributeId}")),
            );

            $results[] = $model->create($attributeData);
        }
        
        if (count($results) === 1) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('1 attribute has been generated.')
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('%s attributes have been generated.', count($results))
            );
        }

        return $results;
    }  

    public function deleteAttributes()
    {
        /*
        $_attribute = Mage::getModel('eav/entity_attribute')->loadByCode("catalog_product", "sample_attribute_".($this->_atts_deleted+1));
        $_attribute_id = $_attribute->getId();

        if($_attribute_id){
            $attribute_model = Mage::getModel("catalog/product_attribute_api");
            $attribute_model->remove($_attribute_id);
            $this->_atts_deleted++;
            $this->deleteAttributes();
        }
        
        $this->_debug_msg .= "[INFO] - " . $this->_atts_deleted . " attributes deleted<br/>";
         * 
         */
    }
    
}