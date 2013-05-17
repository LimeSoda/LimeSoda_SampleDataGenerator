<?php

class LimeSoda_SampleDataGenerator_Block_Adminhtml_Rule_Edit_Tab_Categories_Nested extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface
{
    protected function _prepareLayout()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label' => Mage::helper('ls_sampledatagenerator')->__('Add category level'),
                'onclick' => 'return categoryLevel.addItem()',
                'class' => 'add'
            ));
        $button->setName('add_category_level_item_button');

        $this->setChild('add_button', $button);
        return parent::_prepareLayout();
    }
    
    public function __construct()
    {
        $this->setTemplate('ls_sampledatagenerator/rule/edit/tab/categories/nested.phtml');
    }
    
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }
    
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }
}
