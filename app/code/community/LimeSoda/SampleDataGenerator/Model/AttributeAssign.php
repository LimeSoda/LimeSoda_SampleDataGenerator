<?php

class LimeSoda_SampleDataGenerator_Model_AttributeAssign extends LimeSoda_SampleDataGenerator_Model_Entity
{
    protected $_attributeSets = array();
    protected $_attributes = array();
    
    private $_atts_assigned = Array();
    
    /**
     * @refactor: sollte nicht vervielfacht sein, wird außerdem nicht richtig gesetzt, da das im Konstruktor vom Processor gemacht wird.
     */
    private $numOfAttributesPerSet = 5;
    
    private function _assignAttribute($attItem,$setItem)
    {
        $attributeset_model = Mage::getModel("catalog/product_attribute_set_api");
        
        $_attribute = Mage::getModel('eav/entity_attribute')->load($this->_attributes[$attItem]);
        $_attribute->setAttributeSetId($this->_attributeSets[$setItem])->loadEntityAttributeIdBySet();
        
        if (!$_attribute->getEntityAttributeId()) {
            $attributeset_model->attributeAdd($this->_attributes[$attItem],$this->_attributeSets[$setItem]);
        }

        $this->_atts_assigned[$this->_attributeSets[$setItem]][] = $attItem;
        
    }
    
    private function _assignAttributeRnd($setItem)
    {
        $attsCreated = Array();
        
        for($i = 0; $i < $this->numOfAttributesPerSet; $i++){
            
            $attItem = rand(0,count($this->_attributes)-1);

            if (in_array($attItem, $attsCreated)) {
                $i--;
            }else{
                $attsCreated[] = $attItem;
                $attributeset_model = Mage::getModel("catalog/product_attribute_set_api");
            
                $_attribute = Mage::getModel('eav/entity_attribute')->load($this->_attributes[$attItem]);
                
                $_attribute->setAttributeSetId($this->_attributeSets[$setItem])->loadEntityAttributeIdBySet();
    
                if (!$_attribute->getEntityAttributeId()) {
                    $attributeset_model->attributeAdd($this->_attributes[$attItem],$this->_attributeSets[$setItem]);
                }
                $this->_atts_assigned[$this->_attributeSets[$setItem]][] = $attItem;
            }

        }
    }
    
    public function assignAttributes(array $attributeSets, array $attributes)
    {
        $this->_attributeSets = $attributeSets;
        $this->_attributes = $attributes;
        
        if(!count($this->_attributeSets)){
            $this->_attributeSets[] = 4; //set attset to default set; this may be buggy because variable is created as reference
            for ($i = 0, $productAttributeCount = count($this->_attributes); $i < $productAttributeCount; $i++){
                $this->_assignAttribute($i,0);
            }
        } else {
            for ($i = 0; $i < count($this->_attributeSets); $i++){
                $this->_assignAttributeRnd($i);
            }
        }

        $this->_debug_msg .= "[INFO] - ".count($this->_atts_assigned)." attributes assigned to sets<br/>";
    }
    
    public function getAssignedAttributes()
    {
        return $this->_atts_assigned;
    }
}