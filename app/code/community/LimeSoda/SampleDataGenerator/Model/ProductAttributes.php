<?php

class LimeSoda_SampleDataGenerator_Model_ProductAttributes extends LimeSoda_SampleDataGenerator_Model_Entity
{
    private $_atts_created = Array();
    
    private $_atts_deleted = 0;
    
    private function _createAttribute($item)
    {
            
        $_attribute_id = Mage::getModel('eav/entity_attribute')->loadByCode("catalog_product", "sample_attribute_".($item+1))->getId();
    
        if(!$_attribute_id){
            
            //START: create attribute
            $attribute_model = Mage::getModel("catalog/product_attribute_api");
            
            $attribute_data = Array ( 
                "scope" => "global",
                "attribute_code" => "sample_attribute_".($item+1),
                "frontend_label" => Array (Array("store_id" => 0, "label" => "Sample Attribute ".($item+1))),
                "frontend_input" => "text" ,
                "is_searchable" => 1 ,
                "is_visible_in_advanced_search" => 1 ,
                "is_comparable" => 1 ,
                "is_visible_on_front" => $this->_config["attributes"]["is_visible_on_front"] ,
                "used_in_product_listing" => 1
                            
            );

            $_attribute_id = $attribute_model->create($attribute_data);
            
        }
        $this->_atts_created[] = $_attribute_id;
    }

    public function createAttributes()
    {
        for ($i = 0; $i < $this->_config["attributes"]["items"]; $i++){
            $this->_createAttribute($i);
        }
        
        $this->_debug_msg .= "[INFO] - " . count($this->_atts_created) . " attributes created<br/>";
    }
    
    public function deleteAttributes()
    {
        $_attribute = Mage::getModel('eav/entity_attribute')->loadByCode("catalog_product", "sample_attribute_".($this->_atts_deleted+1));
        $_attribute_id = $_attribute->getId();

        if($_attribute_id){
            $attribute_model = Mage::getModel("catalog/product_attribute_api");
            $attribute_model->remove($_attribute_id);
            $this->_atts_deleted++;
            $this->deleteAttributes();
        }
        
        $this->_debug_msg .= "[INFO] - " . $this->_atts_deleted . " attributes deleted<br/>";
    }
    
    public function getCreatedAttributes()
    {
        return $this->_atts_created;
    } 
}