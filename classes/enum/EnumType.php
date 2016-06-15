<?php

namespace JobOffer\classes\enum;

/**
 *
 * @abstract
 * @author Nicolas GILLE
 */
abstract class EnumType {
    
    /**
     * Name give at the new element of the enum list.
     * @access private
     * @var string
     *  Name of the enum's element.
     */
    private $_key;
    
    /**
     * Value associated at the specific key.
     * @access private
     * @var mixed
     *  Value associated with Key
     */
    private $_value;
    
    /**
     * Return the key of EnumType.
     * @access public
     * @return string
     *  Return the key of enum type.
     */
    public function getKey() {
        return $this->_key;
    }
    
    /**
     * Return the value of EnumType
     * @access public
     * @return mixed
     *  Return the content value of enum type.
     */
    public function getValue() {
        return $this->_value;
    }
    
    /**
     * Replace the current key by another key, or asign the value from the Database.
     * @access public
     * @param string $key
     *  Replace current key by new key.
     */
    private function setKey($key) {
        $this->_key = $key;
    }
    
    /**
     * Replace the current value by another value, or asign the value from the Database.
     * @access public
     * @param type $value
     *  Replace current value by new value.
     */
    private function setValue($value) {
        $this->_value = $value;
    }
        
    /**
     * This function is used for hydrate Object.
     * For hydrate on object, call hydrate function on __construct 
     * and your object will be create directly without calling all setters functions.
     * @access public
     * @param array $data
     *  Data received from an array with enum elements.
     */
    protected function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
