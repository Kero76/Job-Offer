<?php

/**
 *
 * @author Nicolas GILLE
 */
class EnumType {
    
    /**
     * Name give at the new element of the enum list.
     * 
     * @access private
     * @var string
     *  Name of the enum's element.
     */
    private $_key;
         
    /**
     * This constructor is used for creating enum element into enum.
     * Contains only the key in the form of string.
     * 
     * @access public
     * @param string $key
     *  The name of the enum element.
     */
    public function __construct($key) {
        $this->_setKey($key);
    }
    
    public function __toString() {
        return $this->getKey();
    }
    
    /**
     * Return the key of EnumType.
     * 
     * @access public
     * @return string
     *  Return the key of enum type.
     */
    public function getKey() {
        return $this->_key;
    }
    
    /**
     * Replace the current key by another key, or asign the value from the Database.
     * 
     * @access private
     * @param string $key
     *  Replace current key by new key.
     */
    private function _setKey($key) {
        $this->_key = $key;
    }    
}