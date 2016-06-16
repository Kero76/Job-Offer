<?php

namespace JobOffer\classes\enum;

/**
 *
 * @author Nicolas GILLE
 */
class EnumType {
    
    /**
     * Name give at the new element of the enum list.
     * @access private
     * @var string
     *  Name of the enum's element.
     */
    private $_key;
         
    /**
     * This constructor is used for creating enum element into enum.
     * Contains only the potential type of contract.
     * For example, can be found "CDI, CDD, Temps plein, Stage, ...".
     * @access public
     * @param array $data
     *  An aray composed by a Key, where the key represent the name of enum element.
     */
    public function __construct($data) {
        $this->hydrate($data);
    }
    
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
     * Replace the current key by another key, or asign the value from the Database.
     * @access private
     * @param string $key
     *  Replace current key by new key.
     */
    private function _setKey($key) {
        $this->_key = $key;
    }
       
    /**
     * This function is used for hydrate Object.
     * For hydrate on object, call hydrate function on __construct 
     * and your object will be create directly without calling all setters functions.
     * @access private
     * @param array $data
     *  Data received from an array with enum elements.
     */
    private function _hydrate(array $data) {
        foreach ($data as $key => $value) {
            $method = '_set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
