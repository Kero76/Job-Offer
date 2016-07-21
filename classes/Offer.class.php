<?php

/**
 * This class represent an offer. 
 * An offer is composed by :
 *  - Id : Unique id for stored offer on database.
 *  - Title : Title of an offer.
 *  - Content : Content of an offer.
 *  - Type : Sort of offer like Traineeship, Fill-Time, Permanent Position, ...
 *  - visibility : Visibility of an offer.
 * When you work with an offer, you used only an Offer object for manipulate data about an offer.
 * 
 * @since Job Offer 1.2.1
 *  -> Added visibility attributes and getter and setter corresponding.
 * @since Job Offer 1.0.0
 * @version 1.1.0
 */
class Offer {
    
    /**
     * Unique id of the offer. It used when 2 offers have the same names 
     * and the same caracteristics.
     * 
     * @access private
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
     * Visibility of the offer.
     *
     * @access private
     * @var bool
     *  Visibility of the offer. 
     */
    private $_visibility;
    
    /**
     * Constuctor of an Offer.
     * 
     * It using hydrate method for created directly my Object,
     * without calling all setters methods.
     * 
     * @param mixed $data
     *  Data received from the database.
     */
    public function __construct($data) {
        $this->_hydrate($data);
    }
    
    /**
     * See details about an offer.
     * 
     * Function used for see the details about an Offer.
     * 
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
     * Return id.
     * 
     * @return int 
     *  Unique identifiant of the offer.
     */
    public function get_id() {
        return $this->_id;
    }
    
    /**
     * Return title.
     * 
     * @return string
     *  Return the title of the offer.
     */
    public function get_title() {
        return $this->_title;
    }
    
    /**
     * Return content.
     * 
     * @return string 
     *  Return the content of the offer.
     */
    public function get_content() {
        return $this->_content;
    }
    
    /**
     * Return an Object EnumTYpe.
     * 
     * EnumType object represent the type of offer. 
     * For example, we can return traineeship, full-time, ...
     * 
     * @return EnumType
     *  Return an Object EnumType.
     */
    public function get_type() {
        return $this->_type;
    }
    
    /**
     * Return the visibility of the current offer.
     * 
     * @return bool
     *  Return the current state of visibility.
     */
    public function get_visibility() {
        return $this->_visibility;
    }
     
    /**
     * Set id of current offer.
     * Replace the current id by new id, or asign the value from the Database.
     * 
     * @param int $id
     *  Value of id.
     */
    public function set_id($id) {
        $this->_id = $id;
    }
      
    /**
     * Set title of current offer.
     * Replace the current title by new title, or asign the value from the Database.
     * 
     * @param string $title
     *  Value of title.
     */
    public function set_title($title) {
        $this->_title = $title;
    }
    
    /**
     * Set content of current offer.
     * Replace the current content by new cpntent, or assign the value from the Database.
     * 
     * @param string $content
     *  Value of content.
     */
    public function set_content($content) {
        $this->_content = $content;
    }
    
    /**
     * Set type of current offer. 
     * Replace type of offer by another EnumType, or assign the value from the Database.
     * We verify in the first step if the type passed on parameter is a EnumTYpe.
     * If the parameter isn't a EnumTYpe, the type isn't modify.
     * 
     * @param EnumType $type
     *  Type of offer.
     */
    public function set_type(EnumType $type) {
        if ($type instanceof EnumType)
            $this->_type = $type;
    }
    
    /**
     * Set visibility of the current offer..
     * Replace the current visibility by new visibility or assign the value from the Database.
     * 
     * @param bool $visibility
     *  True if the offer is show. False if the offer is hide.
     */
    public function set_visibility($visibility) {
        $this->_visibility = $visibility;
    }
    
    /**
     * This method is used for hydrate Object.
     * For hydrate on object, call hydrate method on __construct 
     * and your object will be create directly without calling all setters methods.
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
