<?php
class LimeSoda_SampleDataGenerator_Block_Adminhtml_Rule_Edit_Tab_Categories extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $helper = Mage::helper('ls_sampledatagenerator');
        $model  = Mage::registry('ls_sampledatagenerator_rule');
        $form   = new Varien_Data_Form();
        
        /* General */  
        $generalSet = $form->addFieldset('categories_general',
           array('legend' => $helper->__('Categories'))
        );
        
        $categories = $generalSet->addField('categories', 'text', array(
            'name'      => 'categories',
            'label'     => $helper->__('Categories'),
            'title'     => $helper->__('Categories'),
            'value'     => $model->getData('categories')
        ));
        
        $categories->setRenderer(
            $this->getLayout()->createBlock('ls_sampledatagenerator/adminhtml_rule_edit_tab_categories_nested')
        );

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
        return Mage::helper('ls_sampledatagenerator')->__('Categories');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('ls_sampledatagenerator')->__('Categories');
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