<?php

/**
 *
 * @author Nicolas GILLE
 */
class Offer {
    
    /**
     * Unique id of the offer. It used when 2 offers have the same names 
     * and the same caracteristics.
     * 
     * @var integer
     *  Unique id of the offer.
     */
    private $_id;
    
    /**
     * Title of the Offer.
     * 
     * @access private
     * @var string
     *  Title of the offer. 
     */
    private $_title;
   
    /**
     * Content of the Offer
     * 
     * @access private
     * @var string 
     *  Content of the offer.
     */
    private $_content;
    
    /**
     * Type of offer.
     * 
     * @access private
     * @var EnumType 
     *  Type of offer.
     */
    private $_type;
    
    /**
     * Constuctor of an Offe.
     * It using hydrate function for created directly my Object,
     * without calling all setters functions.
     * 
     * @access public
     * @param mixed $data
     *  Data received from the database.
     */
    public function __construct($data) {
        $this->_hydrate($data);
    }
    
    /**
     * Function used for see the details about an Offer.
     * 
     * @access public
     * @return string
     *  The current state of the offer.
     */
    public function __toString() {
        $str = '';
        $str .= 'Id : ' . $this->_id;
        $str .= '<br />';
        $str .= 'Title : ' . $this->_title;
        $str .= '<br />';
        $str .= 'Content : ' . $this->_content;
        $str .= '<br />';
        $str .= 'Type : ' . $this->_type->get_key();
        $str .= '<br /> ----- <br />';
        return $str;
    }
    
    /**
     * Return identifiant.
     * 
     * @access public
     * @return int 
     *  Unique identifiant of the offer.
     */
    public function get_id() {
        return $this->_id;
    }
    
    /**
     * Return title value.
     * 
     * @access public
     * @return string
     *  Return the title of the offer.
     */
    public function get_title() {
        return $this->_title;
    }
    
    /**
     * Return content value.
     * 
     * @access public
     * @return string 
     *  Return the content of the offer.
     */
    public function get_content() {
        return $this->_content;
    }
    
    /**
     * Return an Object EnumTYpe.
     * 
     * @access public
     * @return \JobOffer\classes\domain\EnumType
     *  Return an Object EnumType.
     */
    public function get_type() {
        return $this->_type;
    }
     
    /**
     * Replace the current id by new id, or asign the value from the Database.
     * 
     * @access public
     * @param int $id
     *  New id
     */
    public function set_id($id) {
        $this->_id = $id;
    }
      
    /**
     * Replace the current title by new title, or asign the value from the Database.
     * 
     * @access public
     * @param string $title
     *  New title
     */
    public function set_title($title) {
        $this->_title = $title;
    }
    
    /**
     * Replace the current content by new cpntent, or asign the value from the Database.
     * 
     * @access public
     * @param string $content
     *  New content
     */
    public function set_content($content) {
        $this->_content = $content;
    }
    
    /**
     * Replace type of offer by another Type, or asign the value from the Database.
     * We verify in the first step if the type passed on parameter is a EnumTYpe.
     * If the parameter isn't a EnumTYpe, the type isn't modify.
     * 
     * @access public
     * @param EnumType $type
     *  Type of offer.
     */
    public function set_type(EnumType $type) {
        if ($type instanceof EnumType)
            $this->_type = $type;
    }
    
    /**
     * This function is used for hydrate Object.
     * For hydrate on object, call hydrate function on __construct 
     * and your object will be create directly without calling all setters functions.
     * 
     * @access private
     * @param array $data
     *  Data received from the database.
     */
    private function _hydrate(array $data) {
        foreach ($data as $key => $value) {
            $method = 'set_' . strtolower($key);
            if (method_exists($this, $method)) {  
                $this->$method($value);
            }
        }
    }
}
