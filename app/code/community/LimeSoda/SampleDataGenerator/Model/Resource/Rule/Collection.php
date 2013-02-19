<?php

class LimeSoda_SampleDataGenerator_Model_Resource_Rule_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('ls_sampledatagenerator/rule');
    }
}