<?php

class LimeSoda_SampleDataGenerator_Model_ProductAttribute extends LimeSoda_SampleDataGenerator_Model_Entity
{
    /**
     * Default options for the model.
     * 
     * @var array
     */
    protected $_defaultOptions = array(
        'attribute_set_ids' => array(),
        'min_count' => 0,
        'max_count' => 0,
        'frontend_input_type' => array(
            'multiselect' => array(
                'share' => 0,
            ),
            'select' => array(
                'share' => 0,
            ),
            'text' => array(
                'share' => 100
            )
        )
    );
    
    private $_atts_deleted = 0;
    
    /**
     * Adds attributes to sets.
     * 
     * @param int $attributeId
     * @param array $attributeSetIds
     * @return void
     */
    protected function _assignAttributeToSets($attributeId, array $attributeSetIds)
    {
        $model = Mage::getModel("catalog/product_attribute_set_api");
        foreach ($attributeSetIds as $attributeSetId) {
            $model->attributeAdd($attributeId, $attributeSetId);
        }
        unset($model);
    }
    
    /**
     * Performs type-specific actions after the initial attribute creation.
     * 
     * @param Mage_Catalog_Model_Product_Attribute_Api
     * @param string $type
     * @param int $id
     * @param array $attributeData
     * @return void
     */
    protected function _createAfter($apiModel, $type, $id, array $attributeData)
    {
        switch ($type) {
            case 'multiselect':
            case 'select':
                /**
                 * @todo More sophisticated creation of options.
                 */
                for ($i = 1, $optionCount = rand(4,10); $i <= $optionCount; $i++) {
                    $option = array(
                        "label" => array(
                            array(
                                'store_id' => array(0),
                                'value' => 'Option ' . $i
                            )
                        ),
                        "order" => 0,
                        "is_default" => ($i === 1 ? 1 : 0)
                    );
                    $apiModel->addOption($attributeData['attribute_code'], $option); 
                }
                
                break;
            default:
                return; 
        }
    }
    
    /**
     * Returns the attribute set ids where attributes should be added to.
     * 
     * If no ids are specified, a random selection of attribute sets is provided.
     * 
     * @param array $ids
     * @return array
     */
    protected function _getAttributeSetIds(array $ids)
    {
        if (!empty($ids)) {
            return $ids;
        }
        
        $entityType = Mage::getModel('catalog/product')->getResource()->getEntityType();
        $allIds = Mage::getResourceModel('eav/entity_attribute_set_collection')->setEntityTypeFilter($entityType->getId())->getAllIds();
        
        $count = rand(1,count($allIds));
        $randomkeys = array_rand($allIds, $count);
        
        $result = array();
        if (is_array($randomkeys)) {
            foreach ($randomkeys as $key) {
                $result[] = $allIds[$key];
            }
        } else {
            $result[] = $allIds[$randomkeys];
        }
        
        return $result;
    }
    
    protected function _getData($type, $attributeId)
    {
        switch ($type) {
            case 'multiselect':
                $attributeData = $this->_getMultiSelectData($attributeId);
                break;
            case 'select':
                $attributeData = $this->_getSelectData($attributeId);
                break;
            case 'text':
                $attributeData = $this->_getTextData($attributeId);
                break;
            default:
                throw new Exception ("Unknown attribute type '$type'."); 
        }
        
        return $attributeData;
    }
    
    /**
     * Returns random data for attributes of type 'multiselect'.
     * For possible values see http://www.magentocommerce.com/api/soap/catalog/catalogProductAttribute/product_attribute.create.html
     * 
     * @param int $attributeId
     * @return array
     */
    protected function _getMultiselectData($attributeId)
    {
        return array( 
            "attribute_code" => "sample_attribute_{$attributeId}",
            "frontend_input" => "multiselect",
            "scope" => $this->_getRandomScope(),
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
            "frontend_label" => array(array("store_id" => 0, "label" => "Sample Attribute {$attributeId}")),
            "additional_fields" => array(
                "is_filterable" => rand(0,1),
                "is_filterable_in_search" => rand(0,1),
                "position"  => 0,
            ),
        );
    }

    /**
     * Returns a random scope level ("store", "website" or "global").
     * 
     * @todo Refactor and move out of class?
     * @return string
     */
    protected function _getRandomScope()
    {
        $scopes = array("store, website, global");
        $key = array_rand($scopes);
        
        return $scopes[$key];
    }
    
    /**
     * Returns random data for attributes of type 'select'.
     * For possible values see http://www.magentocommerce.com/api/soap/catalog/catalogProductAttribute/product_attribute.create.html
     * 
     * @param int $attributeId
     * @return array
     */
    protected function _getSelectData($attributeId)
    {
        return array( 
            "attribute_code" => "sample_attribute_{$attributeId}",
            "frontend_input" => "select",
            "scope" => $this->_getRandomScope(),
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
            "frontend_label" => array(array("store_id" => 0, "label" => "Sample Attribute {$attributeId}")),
            "additional_fields" => array(
                "is_filterable" => rand(0,1),
                "is_filterable_in_search" => rand(0,1),
                "position"  => 0,
                "used_for_sort_by" => 0,
            ),
        );
    }
    
    /**
     * Returns random data for attributes of type 'text'.
     * For possible values see http://www.magentocommerce.com/api/soap/catalog/catalogProductAttribute/product_attribute.create.html
     * 
     * @param int $attributeId
     * @return array
     */
    protected function _getTextData($attributeId)
    {
        return array( 
            "attribute_code" => "sample_attribute_{$attributeId}",
            "frontend_input" => "text",
            "scope" => $this->_getRandomScope(),
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
    }
    
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
        
        $attributeSetIds = $this->_getAttributeSetIds($options['attribute_set_ids']);
        
        $count = rand($options['min_count'], $options['max_count']);
        
        for ($i = 1; $i <= $count; $i++) {
            $attributeId++;
                    
            $model = Mage::getModel("catalog/product_attribute_api");
            
            /**
             * @todo Typ entsprechend der Anteile der Attribut-Typen festlegen.
             */
            $type = 'text';
            
            /**
             * @todo Refactor to use event or put in class outside?
             */
            $attributeData = $this->_getData($type, $attributeId);
                        
            $id = $model->create($attributeData);
            $this->_assignAttributeToSets($id, $attributeSetIds);
            
            /**
             * @todo Refactor to use event?
             */
            $this->_createAfter($model, $type, $id, $attributeData);

            $results[] = $id;
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