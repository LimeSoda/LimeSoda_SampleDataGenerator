<?php

class LimeSoda_SampleDataGenerator_Model_Resource_Rule extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('ls_sampledatagenerator/rule', 'rule_id');
    }
}
