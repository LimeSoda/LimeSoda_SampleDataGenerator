<?php
class LimeSoda_SampleDataGenerator_Block_Adminhtml_Rule_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
         
        // Set some defaults for our grid
        $this->setDefaultSort('rule_id');
        $this->setId('sampledatagenerator_rule_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }
    
    protected function _getCollectionClass()
    {
        // This is the model we are using for the grid
        return 'ls_sampledatagenerator/rule_collection';
    }
    
    protected function _prepareCollection()
    {
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
         
        return parent::_prepareCollection();
    }
     
    protected function _prepareColumns()
    {
        // Add the columns that should appear in the grid
        $this->addColumn('rule_id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'right',
                'width' => '50px',
                'index' => 'rule_id'
            )
        );
         
        $this->addColumn('title',
            array(
                'header'=> $this->__('Title'),
                'index' => 'title'
            )
        );
         
        return parent::_prepareColumns();
    }
    
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('rule_id');
        $this->getMassactionBlock()->setFormFieldName('rule');
        
        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('ls_sampledatagenerator')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete')
        ));

        $this->getMassactionBlock()->addItem('generate', array(
             'label'    => Mage::helper('ls_sampledatagenerator')->__('Generate'),
             'url'      => $this->getUrl('*/*/massGenerate')
        ));

        return $this;
    }
    
    /*
     * No edit action for now. 
     */ 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('ruleId' => $row->getRuleId()));
    }
}