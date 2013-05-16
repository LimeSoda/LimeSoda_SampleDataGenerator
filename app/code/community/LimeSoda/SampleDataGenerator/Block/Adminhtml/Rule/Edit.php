<?php
class LimeSoda_SampleDataGenerator_Block_Adminhtml_Rule_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    
    /**
     * Prepare layout
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $tabsBlock = $this->getLayout()->getBlock('ls_sampledatagenerator_adminhtml_rule_edit_tabs');
        if ($tabsBlock) {
            $tabsBlockJsObject = $tabsBlock->getJsObjectName();
            $tabsBlockPrefix   = $tabsBlock->getId() . '_';
        } else {
            $tabsBlockJsObject = 'form_tabsJsTabs';
            $tabsBlockPrefix   = 'form_tabs_';
        }

        $this->_formScripts[] = "
            function saveAndContinueEdit(urlTemplate) {
                var tabsIdValue = " . $tabsBlockJsObject . ".activeTab.id;
                var tabsBlockPrefix = '" . $tabsBlockPrefix . "';
                if (tabsIdValue.startsWith(tabsBlockPrefix)) {
                    tabsIdValue = tabsIdValue.substr(tabsBlockPrefix.length)
                }
                var template = new Template(urlTemplate, /(^|.|\\r|\\n)({{(\w+)}})/);
                var url = template.evaluate({tab_id:tabsIdValue});
                editForm.submit(url);
            }
        ";
        return parent::_prepareLayout();
    }
    
    /**
     * Init class
     */
    public function __construct()
    {
        $this->_objectId = 'ruleId';  
        $this->_blockGroup = 'ls_sampledatagenerator';
        $this->_controller = 'adminhtml_rule';
     
        parent::__construct();
     
        $this->_updateButton('save', 'label', $this->__('Save Rule'));
        $this->_updateButton('delete', 'label', $this->__('Delete Rule'));
        
        $this->_addButton('saveandcontinue', array(
                'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')',
                'class'     => 'save',
        ), -100);
    }  
    
    public function getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'   => true,
            'back'       => 'edit',
            'active_tab' => '{{tab_id}}'
        ));
    }
     
    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText()
    {  
        if (Mage::registry('ls_sampledatagenerator_rule')->getId()) {
            return $this->__('Edit Rule');
        } else {
            return $this->__('New Rule');
        }  
    }  
}