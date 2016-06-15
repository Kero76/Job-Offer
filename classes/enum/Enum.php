<?php

namespace JobOffer\classes\enum;

use JobOffer\classes\enum\EnumType;

/**
 *
 * @author Nicolas GILLE
 */
class Enum {
    
    /**
     * This is an array who contains all element present in enumeration.
     * @access private
     * @var array 
     *  Array who contains all enumerator elements.
     */
    private $_enum;
    
    /**
     * This is a constructor for initialize an enumeration.
     * @access public
     */
    public function __construct() {
        $this->_enum = array();
    }
    
    /**
     * Added new element at the end of the enum.
     * @param EnumType $element
     *  Element to add at the queue of the enum.
     */
    public function addEnumElement(EnumType $element) {
        $this->_enum[] =  $element;
    }
    
    /**
     * Return the id in enum thanks to an key.
     * If the key doesn't exists, the function return -1.
     * This function is used mainly because, when we send the data in database, 
     * we store only the id of the enum and not the value of enum.
     * So for that, and because the enumeration order don't change,
     * we can store directly the id.
     * @access public
     * @param string $key
     *  The key who searching in the enum.
     * @return int
     *  The rank of key in the enum.
     */
    public function getIdByKey($key) {
        if ($this->keyExists($key)) {
            return array_search($key, $this->_enum);
        }
        return -1;
    }
    
    /**
     * Return an OfferTYpe thank to the rank in the enum.
     * If the id is greater than the size of enum or is negative, 
     * the function return -1.
     * This function is used mainly because, when we recover the value of the type in Database,
     * we recover an int who representing the id in enum. 
     * So, you use this function for convert this data into an OfferType object.
     * @access public
     * @param int $id
     *  Rank of seraching the current element's enum.
     * @return \JobOffer\classes\enum\OfferType
     *  Return an OfferType object.
     */
    public function getKeyById($id) {
        if (count($this->_enum) < $id && $id > 0) {
            return $this->_enum[$id];
        }
        return -1;
    }
    
    /**
     * This function permit to verify if the current key is present or not in the enum.
     * @access public
     * @param string $key
     *  The key search in enum.
     * @return bool
     *  Return true if the key exist, then return false.
     */
    public function keyExists($key) {
        return array_key_exists($key, $this->_enum);
    }
}
