<?php
class LimeSoda_SampleDataGenerator_Block_Adminhtml_Rule_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $helper = Mage::helper('ls_sampledatagenerator');
        $model  = Mage::registry('ls_sampledatagenerator_rule');
        $form   = new Varien_Data_Form();
        
        /* General */  
        $generalSet = $form->addFieldset('products_attributes',
           array('legend' => $helper->__('Attributes'))
        );
        
        $generalSet->addField('product_attribute_min_count', 'text', array(
            'name'      => 'product_attribute_min_count',
            'label'     => $helper->__('Minimum count'),
            'title'     => $helper->__('Minimum count'),
        ));
        
        $generalSet->addField('product_attribute_max_count', 'text', array(
            'name'      => 'product_attribute_max_count',
            'label'     => $helper->__('Maximum count'),
            'title'     => $helper->__('Maximum count'),
        ));
        
        $generalSet->addField('add_product_attributes_only_to_new_product_attribute_sets', 'select', array(
            'name'      => 'add_product_attributes_only_to_new_product_attribute_sets',
            'label'     => $helper->__('Add only to new attribute sets'),
            'title'     => $helper->__('Add only to new attribute sets'),
            'values'    => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        
        $form->setValues($model->getData());
        $this->setForm($form);
        
        return parent::_prepareForm();
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }
    
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('ls_sampledatagenerator')->__('Attributes');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('ls_sampledatagenerator')->__('Attributes');
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
}