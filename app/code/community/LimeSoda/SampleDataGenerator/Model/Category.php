<?php

class LimeSoda_SampleDataGenerator_Model_Category extends LimeSoda_SampleDataGenerator_Model_Entity
{
    /**
     * Default options for the model.
     * 
     * @var array
     */
    protected $_defaultOptions = array(
        'categories' => array(
            'min_count' => 0,
            'max_count' => 0,
            'subcategories' => array()
        )
    );
    
    private $_cats_deleted = 0;
    
    /**
     * Creates the categories and calls itself in case sub categories are defined.
     * 
     * @param array $options
     * @param int $parentCategoryId 
     * @return array Array containing ids of created categories
     */
    protected function _createCategories(array $options, $parentCategoryId)
    {
        if ($options['min_count'] > $options['max_count']) {
            throw new DomainException("Minimum count must not be bigger than maximum count.");
        } elseif ($options['min_count'] == 0 && $options['max_count'] == 0) {
            return array();
        }
        
        $categoryIds = array();
        $count = rand($options['min_count'], $options['max_count']);
        
        $categoryApi = Mage::getModel("catalog/category_api");
                    
        for ($i = 1; $i <= $count; $i++) {
            
            $nextId = max(Mage::getModel('catalog/category')->getCollection()->getAllIds()) + 1;
            
            $categoryData = array(
                'name' => "Sample Category $nextId",
                'is_active' => 1,
                'available_sort_by' => 'position',
                'default_sort_by' => 'position',
                'description' => "Sample Category Description $nextId",
                'is_anchor' => 1,
                'meta_description' => "Sample Category meta description $nextId",
                'meta_keywords' => "Sample Category meta keywords $nextId",
                'meta_title' => "Sample Category meta title $nextId",
                'url_key' => "sample-category-$nextId",
                'include_in_menu' => 1
            );

            $categoryId = $categoryApi->create($parentCategoryId, $categoryData);
            $categoryIds[] = $categoryId;
            
            if (!empty($options['subcategories']) && array_key_exists('categories', $options['subcategories'])) {
                $subCategoryIds = $this->_createCategories($options['subcategories']['categories'], $categoryId); 
            }
            
            $categoryIds = array_merge($categoryIds, $subCategoryIds);
            
        }
        
        return $categoryIds;
            
    }

    /**
     * Creates the categories.
     * 
     * @todo make root category id configurable / make script flexbile for using multiple root category ids.
     * @param array $options
     * @return array Array with IDs of created categories. 
     */
    public function create(array $options)
    {
        $options = array_merge($this->_defaultOptions, $options);
        $rootCategoryId = array_shift(Mage::getModel('catalog/category')->getCollection()->addRootLevelFilter()->getAllIds());
        
        $results = $this->_createCategories($options['categories'], $rootCategoryId);
        
        if (count($results) === 0) {
            return $results;
        } else if (count($results) === 1) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('1 category has been generated.')
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('ls_sampledatagenerator')->__('%s categories have been generated.', count($results))
            );
        }
        
        return $results;
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
   
}
