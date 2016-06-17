<?php

require_once('EnumType.class.php');

/**
 *
 * @author Nicolas GILLE
 */
class Enum {
    
    /**
     * This is an array who contains all element present in enumeration.
     * 
     * @access private
     * @var array 
     *  Array who contains all enumerator elements.
     */
    private $_enum;
    
    /**
     * This is a constructor for initialize an enumeration.
     * 
     * @access public
     */
    public function __construct() {
        $this->_enum = array();
        $this->_init_enum();
    }
    
    /**
     * Added new element at the end of the enum.
     * It verify in the first time if the key is not present in the enum before added it.
     * 
     * @param EnumType $element
     *  Element to add at the queue of the enum.
     */
    public function addEnumElement(EnumType $element) {
        if (!$this->keyExists($element->getKey()))
            $this->_enum[] = $element;
    }
    
    /**
     * Return the id in enum thanks to an key.
     * If the key doesn't exists, the function return -1.
     * This function is used mainly because, when we send the data in database, 
     * we store only the id of the enum and not the value of enum.
     * So for that, and because the enumeration order don't change,
     * we can store directly the id.
     * 
     * @access public
     * @param string $key
     *  The key who searching in the enum.
     * @return integer
     *  The rank of key in the enum.
     */
    public function getIdByKey($key) {
        for ($i = 0; $i < count($this->_enum); $i++) {
            if (strcmp($key, $this->_enum[$i]->getKey()) == 0) {
                return $i;
            }
        }
        return -1;
    }
    
    /**
     * Return an Offer thank to the rank in the enum.
     * If the id is greater than the size of enum or is negative, 
     * the function return -1.
     * This function is used mainly because, when we recover the value of the type in Database,
     * we recover an int who representing the id in enum. 
     * So, you use this function for convert this data into an OfferType object.
     * 
     * @access public
     * @param int $id
     *  Rank of seraching the current element's enum.
     * @return object
     *  Return an EnumType object.
     */
    public function getKeyById($id) {
        $enum = new Enum();
        if (count($enum->getEnum()) > $id || $id < 0) {
            return -1;
        }
        $type = new EnumType((array('key', $enum->getEnum()[$id]->getKey())));
        return $type;
    }
    
    /**
     * This function permit to verify if the current key is present or not in the enum.
     * 
     * @access public
     * @param string $key
     *  The key search in enum.
     * @return bool
     *  Return true if the key exist, then return false.
     */
    public function keyExists($key) {
        return array_key_exists($key, $this->_enum);
    }
    
    /**
     * This function return the enum.
     * 
     * @access public
     * @return array
     *  Return the enum.
     */
    public function getEnum() {
        return $this->_enum;
    }
    
    /**
     * This function initialize the enum value with an array who composed
     * by all possible key present in is enum.
     * Warning /!\ 
     *  Don't modify the current order of enum.
     * If you modify this, check beforehand that the SQL table is empty.
     * 
     * @access private
     */
    private function _init_enum() {
        $data = array(
          'Permanent position', // CDD
          'Temporary position', // CDI
          'Full-time',          // Temps plein
          'Traineeship',        // Stage
        );      
        
        for ($i = 0; $i < count($data); $i++) {
            $this->addEnumElement(new EnumType($data[$i]));
        }
    }
}
