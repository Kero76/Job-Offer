<?php

/**
 * Includes EnumType class because when the enumerator is compose by object EnumType.
 */
require_once('EnumType.class.php');

/**
 * This class represent an enumeration.
 * This enumeration represent the different type present in Offer.
 * For add new element in is enumeration, modified the function _init_enum()
 * and add new element at the end of the array if you have create previously an offer.
 * In fact, if you add a new type in the middle of the array, the potential id present in database 
 * are potentially modifed and taht create possible bugs.
 * 
 * @since Job Offer 1.0
 * @version 1.0
 */
class Enum {
    
    /**
     * This is an array who contains all element present in enumeration.
     * 
     * @since Job Offer 1.0
     * @access private
     * @var array 
     *  Array which contains all enumerators elements.
     */
    private $_enum;
    
    /**
     * Constructor of Enum object.
     * This is a constructor for initialize enumeration.
     * 
     * @since Job Offer 1.0
     */
    public function __construct() {
        $this->_enum = array();
        $this->_init_enum();
    }
    
    /**
     * Added new element at the end of the enum.
     * It verify in the first time if the key is not present in the enum before added it.
     * 
     * @since Job Offer 1.0
     * @param EnumType $element
     *  Element to add at the queue of the enum.
     */
    public function add_enum_element(EnumType $element) {
        if (!$this->key_exists($element->get_key()))
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
     * @since Job Offer 1.0
     * @param string $key
     *  The key who search in the enum.
     * @return integer
     *  The rank of key in the enum.
     */
    public function get_id_by_key($key) {
        for ($i = 0; $i < count($this->_enum); $i++) {
            if (strcmp($key, $this->_enum[$i]->get_key()) == 0) {
                return $i;
            }
        }
        return -1;
    }
    
    /**
     * Return an Offer thanks to the rank in the enum.
     * If the id is greater than the size of enum or is negative, 
     * the function return -1.
     * This function is used mainly because, when we recover the value of the type in Database,
     * we recover an int who represent the id in enum. 
     * So, you use this function for convert this data into a key string, and create object thanks that.
     * 
     * @since Job Offer 1.0
     * @param int $id
     *  Rank of the current element enum.
     * @return string
     *  Return a string representation of the EnumType.
     */
    public function get_key_by_id($id) {
        if ($id > count($this->_enum) || $id < 0) {
            return -1;
        }
        return $this->_enum[$id]->get_key();
    }
    
    /**
     * Verify if the key passed on parameter exists.
     * This function allow to verify if the current key is present or not in the enum.
     * 
     * @since Job Offer 1.0
     * @param string $key
     *  The key search in enum.
     * @return bool
     *  Return true if the key exist, then return false.
     */
    public function key_exists($key) {
        return array_key_exists($key, $this->_enum);
    }
    
    /**
     * This function return the enumerator.
     * 
     * @since Job Offer 1.0
     * @return array
     *  Return the enum.
     */
    public function get_enum() {
        return $this->_enum;
    }
    
    /**
     * This function initialize the enum value with an array who composed
     * by all possible key present in is enum.
     * Warning /!\ 
     *  Don't modify the current order of enum.
     * If you modify this, check beforehand that the SQL table is empty.
     * 
     * @since Job Offer 1.0
     * @access private
     */
    private function _init_enum() {
        $data = array(
          __('Permanent position', 'job-offer'), // CDD
          __('Temporary position', 'job-offer'), // CDI
          __('Full-time', 'job-offer'),          // Temps plein
          __('Traineeship', 'job-offer'),        // Stage
        );      
        
        for ($i = 0; $i < count($data); $i++) {
            $this->add_enum_element(new EnumType($data[$i]));
        }
    }
}
