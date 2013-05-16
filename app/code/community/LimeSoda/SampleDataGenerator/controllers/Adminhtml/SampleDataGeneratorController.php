<?php

class LimeSoda_SampleDataGenerator_Adminhtml_SampleDataGeneratorController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction()
    {
        $this->loadLayout()
            // Make the active menu match the menu config nodes (without 'children' inbetween)
            ->_setActiveMenu('ls_development/ls_sampledatagenerator')
            ->_title($this->__('Sample Data Generator'));
         
        return $this;
    }
    
    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('ls_development/ls_sampledatagenerator');
    }
    
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('ruleId')) {

            try {
                $model = Mage::getModel('ls_sampledatagenerator/rule');
                $model->load($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ls_sampledatagenerator')->__('The rule has been deleted.'));
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('ruleId' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ls_sampledatagenerator')->__('Unable to find a rule to delete.'));
        $this->_redirect('*/*/');
    }
    
    public function editAction()
    {  
        $this->_initAction();
     
        // Get id if available
        $id  = $this->getRequest()->getParam('ruleId');
        $model = Mage::getModel('ls_sampledatagenerator/rule');
     
        if ($id) {
            // Load record
            $model->load($id);
     
            // Check if record is loaded
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This rule doesn\'t exist.'));
                $this->_redirect('*/*/');
     
                return;
            }  
        }  
     
        $this->_title($model->getId() ? $model->getName() : $this->__('New rule'));
     
        $data = Mage::getSingleton('adminhtml/session')->getRuleData(true);
        if (!empty($data)) {
            $model->setData($data);
        }  
        
        Mage::register('ls_sampledatagenerator_rule', $model);
   
        $this
            ->_addBreadcrumb($id ? $this->__('Edit Rule') : $this->__('New Rule'), $id ? $this->__('Edit Rule') : $this->__('New Rule'))
            ->renderLayout();
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('rule');
        if(!is_array($ids)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ls_sampledatagenerator')->__('Please select rule(s).'));
        } else {
            try {
                $rule = Mage::getModel('ls_sampledatagenerator/rule');
                foreach ($ids as $id) {
                    $rule->load($id);
                    $rule->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('ls_sampledatagenerator')->__('Rule has been deleted.')
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
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

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            $model = Mage::getSingleton('ls_sampledatagenerator/rule');
            $model->setData($postData);
 
            try {
                $model->save();
                
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The rule has been saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('ruleId' => $model->getRuleId(), '_current'=>true));
                    return;
                }

                $this->_redirect('*/*/');
                return;
            }  
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('An error occurred while saving this rule.'));
            }
 
            Mage::getSingleton('adminhtml/session')->setRuleData($postData);
            $this->_redirectReferer();
        }
    }
}
