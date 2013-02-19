<?php

class LimeSoda_SampleDataGenerator_Adminhtml_SampleDataGeneratorController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $rule = Mage::getModel('ls_sampledatagenerator/rule')->load(1);

        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function massGenerateAction()
    {
        $ids = $this->getRequest()->getParam('rule');
        if(!is_array($ids)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ls_sampledatagenerator')->__('Please select rule(s).'));
        } else {
            try {
                $processor = Mage::getModel('ls_sampledatagenerator/processor');
                $rule = Mage::getModel('ls_sampledatagenerator/rule');
                foreach ($ids as $id) {
                    $rule->load($id);
                    $processor->generateData($rule);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ls_sampledatagenerator')->__('Data has been generated.')
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
}
