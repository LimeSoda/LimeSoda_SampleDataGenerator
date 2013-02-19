<?php
class LimeSoda_SampleDataGenerator_Block_Adminhtml_Rule extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        // The blockGroup must match the first half of how we call the block, and controller matches the second half
        // ie. foo_bar/adminhtml_baz
        $this->_blockGroup = 'ls_sampledatagenerator';
        $this->_controller = 'adminhtml_rule';
        $this->_headerText = $this->__('Sample Data Generator');
         
        parent::__construct();
    }
}