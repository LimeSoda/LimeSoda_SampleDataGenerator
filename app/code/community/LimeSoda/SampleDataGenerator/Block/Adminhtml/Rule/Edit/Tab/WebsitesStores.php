<?php
class LimeSoda_SampleDataGenerator_Block_Adminhtml_Rule_Edit_Tab_WebsitesStores extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $helper = Mage::helper('ls_sampledatagenerator');
        $model = Mage::registry('ls_sampledatagenerator_rule');
        $form = new Varien_Data_Form();
        
        /* Websites */  
        $websiteSet = $form->addFieldset('websitesstores_websites',
           array('legend' => $helper->__('Websites'))
        );
        
        $websiteSet->addField('website_min_count', 'text', array(
            'name'      => 'website_min_count',
            'label'     => $helper->__('Minimum count'),
            'title'     => $helper->__('Minimum count'),
        ));
        
        $websiteSet->addField('website_max_count', 'text', array(
            'name'      => 'website_max_count',
            'label'     => $helper->__('Maximum count'),
            'title'     => $helper->__('Maximum count'),
        ));
        
        /* Store groups */
        $storeGroupSet = $form->addFieldset('websitesstores_storegroups',
           array('legend' => $helper->__('Storegroups'))
        );
        
        $storeGroupSet->addField('store_group_min_count', 'text', array(
            'name'      => 'store_group_min_count',
            'label'     => $helper->__('Minimum count'),
            'title'     => $helper->__('Minimum count'),
        ));
        
        $storeGroupSet->addField('store_group_max_count', 'text', array(
            'name'      => 'store_group_max_count',
            'label'     => $helper->__('Maximum count'),
            'title'     => $helper->__('Maximum count'),
        ));
        
        $storeGroupSet->addField('add_store_groups_only_to_new_websites', 'select', array(
            'name'      => 'add_store_groups_only_to_new_websites',
            'label'     => $helper->__('Add only to new websites'),
            'title'     => $helper->__('Add only to new websites'),
            'values'    => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        
        /* Store views */
        $storeViewSet = $form->addFieldset('websitesstores_storeviews',
           array('legend' => $helper->__('Store Views'))
        );
        
        $storeViewSet->addField('store_view_min_count', 'text', array(
            'name'      => 'store_view_min_count',
            'label'     => $helper->__('Minimum count'),
            'title'     => $helper->__('Minimum count'),
        ));
        
        $storeViewSet->addField('store_view_max_count', 'text', array(
            'name'      => 'store_view_max_count',
            'label'     => $helper->__('Maximum count'),
            'title'     => $helper->__('Maximum count'),
        ));

        $storeViewSet->addField('add_store_views_only_to_new_store_groups', 'select', array(
            'name'      => 'add_store_views_only_to_new_store_groups',
            'label'     => $helper->__('Add only to new store groups'),
            'title'     => $helper->__('Add only to new store groups'),
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
        return Mage::helper('ls_sampledatagenerator')->__('Websites, Store Groups & Store Views');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('ls_sampledatagenerator')->__('Websites, Store Groups & Store Views');
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