<?php

class LimeSoda_SampleDataGenerator_Model_ProductAttributeSets extends LimeSoda_SampleDataGenerator_Model_Entity
{
    private $_attsets_created = Array();
    private $_attsets_deleted = 0;
    
    private function _createAttributeSet($item)
    {
        $_attributeset_id = Mage::getModel('eav/entity_attribute_set')->load("sample_attributeset_".($item+1), 'attribute_set_name')->getAttributeSetId();
        if(!$_attributeset_id){
            $attributeset_model = Mage::getModel("catalog/product_attribute_set_api");
            $_attributeset_id = $attributeset_model->create("sample_attributeset_".($item+1), "4"); //id of Default set is 4
        }
        $this->_attsets_created[] = $_attributeset_id;
        
    }
    
    public function createAttributeSets()
    {
        for ($i = 0; $i < $this->_config["attributesets"]["items"]; $i++){
            $this->_createAttributeSet($i);
        }
        $this->_debug_msg .= "[INFO] - " . count($this->_attsets_created) . " attributesets created<br/>";
    }
    
    public function deleteAttributeSets(){
        $_attributeset_id = Mage::getModel('eav/entity_attribute_set')->load("sample_attributeset_".($this->_attsets_deleted+1), 'attribute_set_name')->getAttributeSetId();
        if($_attributeset_id){
            $attributeset_model = Mage::getModel("catalog/product_attribute_set_api");
            $attributeset_model->remove($_attributeset_id); 
            $this->_attsets_deleted++;
            $this->deleteAttributeSets();
        }
        $this->_debug_msg .= "[INFO] - " . $this->_attsets_deleted . " attributesets deleted<br/>";
    }
    
    public function getAttributeSets()
    {
        return $this->_attsets_created;
    }
}