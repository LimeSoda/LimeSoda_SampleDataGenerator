<?php

class LimeSoda_SampleDataGenerator_Model_Entity
{
        
    /**
     * @refactor: entfernen
     */
    private $_config = array();
    
    /**
     * @refactor: entfernen
     */
    private $_debug_msg = "<br/><br/>";
    
    public function setConfig($config)
    {
        $this->_config = $config;
        return $this;
    }
    
}
