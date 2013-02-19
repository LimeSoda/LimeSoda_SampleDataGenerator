<?php

class LimeSoda_SampleDataGenerator_Model_Category extends LimeSoda_SampleDataGenerator_Model_Entity
{
    
    private $_cat_tree = Array(0 => Array("id" => 2, "sub" => Array()));
    
    private $_cats_created = Array();
    
    private $_cats_deleted = 0;
    
    private $_current_cat_tree_depth = 1;
    
    private $_current_cat_tree_pos = Array(0,0,-1,-1);
    
    private $_max_items_per_level = Array(0 => 1, 1 => 5, 2 => 20, 3 => 20);
    
    protected function _createCategory($item)
    {
        //TODO: hat die root category immer die ID 2 ?

        //check if category exists
        $_category = Mage::getModel('catalog/category')->loadByAttribute('url_key', 'sample-category-'.($item+1));
                
        if(!$_category){
            
            $category_api = Mage::getModel("catalog/category_api");
                    
            $category_data = array(
                'name' => 'Sample Category '.($item+1),
                'is_active' => 1,
                'available_sort_by' => 'position',
                'default_sort_by' => 'position',
                'description' => 'Sample Category Description '.($item+1),
                'is_anchor' => 1,
                'meta_description' => 'Sample Category meta description '.($item+1),
                'meta_keywords' => 'Sample Category meta keywords '.($item+1),
                'meta_title' => 'Sample Category meta title '.($item+1),
                'url_key' => 'sample-category-'.($item+1),
                'include_in_menu' => 1
            );

            $_category_id = $category_api->create($this->_getParentCategoryId(), $category_data);
                
        }else{
            
            $_category_id = $_category->getId();
                        
        }

        $this->_insertCategoryInArray($_category_id);
        
        $this->_cats_created[] = $_category_id;
    }

    protected function _getParentCategoryId(){
        
        if($this->_current_cat_tree_depth == 1){
            return $this->_cat_tree[$this->_current_cat_tree_pos[0]]["id"];
        }
        if($this->_current_cat_tree_depth == 2){
            return $this->_cat_tree[$this->_current_cat_tree_pos[0]]["sub"][$this->_current_cat_tree_pos[1]]["id"];
        }
        if($this->_current_cat_tree_depth == 3){
            return $this->_cat_tree[$this->_current_cat_tree_pos[0]]["sub"][$this->_current_cat_tree_pos[1]]["sub"][$this->_current_cat_tree_pos[2]]["id"];
        }
    }

    protected function _insertCategoryInArray($_category_id){

        //insert item
        $_tmpArray = Array("id" => $_category_id, "sub" => Array());
        if($this->_current_cat_tree_depth == 1){
            $this->_cat_tree[$this->_current_cat_tree_pos[0]]["sub"][] = $_tmpArray;
        }
        if($this->_current_cat_tree_depth == 2){
            $this->_cat_tree[$this->_current_cat_tree_pos[0]]["sub"][$this->_current_cat_tree_pos[1]]["sub"][] = $_tmpArray;
        }
        if($this->_current_cat_tree_depth == 3){
            $this->_cat_tree[$this->_current_cat_tree_pos[0]]["sub"][$this->_current_cat_tree_pos[1]]["sub"][$this->_current_cat_tree_pos[2]]["sub"][] = $_tmpArray;
        }

        $this->_current_cat_tree_pos[1]++;
        
        if($this->_current_cat_tree_pos[1] == $this->_max_items_per_level[1]){
                
            $this->_current_cat_tree_pos[1] = 0;
            $this->_current_cat_tree_pos[2]++;
            if($this->_current_cat_tree_depth == 1) $this->_current_cat_tree_depth = 2;
            
            if($this->_current_cat_tree_pos[2] == $this->_max_items_per_level[2]){
                $this->_current_cat_tree_pos[2] = 0;
                $this->_current_cat_tree_pos[3]++;
                $this->_current_cat_tree_depth = 3;
            }
            
        }
        
    }
    
    public function createCategories()
    {
        
        for ($i = 0; $i < $this->_config["categories"]["items"]; $i++){
            $this->_createCategory($i);
        }
        
        $this->_debug_msg .= "[INFO] - " . count($this->_cats_created) . " categories created<br/>";
    }
    
    public function deleteCategories()
    {
        $_category = Mage::getModel('catalog/category')->loadByAttribute('url_key', 'sample-category-'.($this->_cats_deleted+1));
        
        if($_category){
            $_category_id = $_category->getId();
            $category_api = Mage::getModel("catalog/category_api");
            $category_api->delete($_category_id);
            $this->_cats_deleted++;
            $this->deleteCategories();
        }
        
        $this->_debug_msg .= "[INFO] - " . $this->_cats_deleted . " categories deleted<br/>";
    }
    
    public function getCreatedCategories()
    {
        return $this->_cats_created;
    }
}
