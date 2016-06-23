<?php

/**
 * This class represent an element present in Enum.
 * An enum element is composed by on key in the form of String, like 'full-time, trainneship, ...'.
 * 
 * @since Job Offer 1.0.0
 * @version 1.0.0
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
     * @param string $key
     *  The name of the enum element.
     */
    public function __construct($key) {
        $this->_set_key($key);
    }
    
    /**
     * Display information about the EnumType.
     * 
     * @return string
     *  A string representation of the key.
     */
    public function __toString() {
        return $this->get_key();
    }
    
    /**
     * Return the key of EnumType.
     * 
     * @return string
     *  Return the key of enum type.
     */
    public function get_key() {
        return $this->_key;
    }
    
    /**
     * Replace the current key by another key, or asign the value from the Database.
     * 
     * @access private
     * @param string $key
     *  Replace current key by new key.
     */
    private function _set_key($key) {
        $this->_key = $key;
    }    
}
