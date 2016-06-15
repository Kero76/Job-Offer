<?php

namespace JobOffer\classes\domain;

use JobOffer\classes\enum\OfferType;

/**
 *
 * @abstract
 * @author Nicolas GILLE
 */
abstract class Offer {
    
    /**
     * Unique id of the offer. It used when 2 offers have the same names 
     * ans the same caracteristics.
     * @var int
     *  Unique id of the offer.
     */
    private $_id;
    
    /**
     * Title of the Offer.
     * @access private
     * @var string
     *  Title of the offer. 
     */
    private $_title;
    
    /**
     * Intro of the offer.
     * @access private
     * @var string 
     *  Introduction present in the begin of the offer.
     */
    private $_intro;
    
    /**
     * Assignements necessary for job.
     * @access private
     * @var string 
     *  Assignements necessary for the job.
     */
    private $_assignments;
    
    /**
     * Knowledge necessary for the job.
     * @access private
     * @var string 
     *  Knowledge necessary for the job.
     */
    private $_knowledges;
    
    /**
     * Extra information about job.
     * For example, we can be found "salary, movements necessary, ...".
     * @access private
     * @var string 
     *  Extra informations about offer.
     */
    private $_extra;
    
    /**
     * Type of offer.
     * @access private
     * @var OfferType 
     *  Type of offer.
     */
    private $_type;
    
    /**
     * Return identifiant.
     * @access public
     * @return int 
     *  Unique identifiant of the offer.
     */
    public function getId() {
        return $this->_id;
    }
    
    /**
     * Return title value.
     * @access public
     * @return string
     *  Return the title of the offer.
     */
    public function getTitle() {
        return $this->_title;
    }
    
    /**
     * Return intro value.
     * @access public
     * @return string 
     *  Return the introduction of the offer.
     */
    public function getIntro() {
        return $this->_intro;
    }
    
    /**
     * Return the assignements as an array.
     * It return an array and not a string, because in the view,
     * it's so easy to process an array for HTML presentation.
     * @access public
     * @return array
     *  Return the assignements value as an array.
     */
    public function getAssignements() {
        $assignements = explode(',', $this->_assignments);
        return $assignements;
    }
    
    /**
     * Return the knowledges as an array.
     * It return an array and not a string, because in the view,
     * it's so easy to process an array for HTML presentation.
     * @access public
     * @return array
     *  Return the knowledges value as an array.
     */
    public function getKnowledges() {
        $knowledges = explode(',', $this->_knowledges);
        return $knowledges;
    }
    
    /**
     * Return extra information value.
     * @access public
     * @return string
     *  Return the extra informations about offer.
     */
    public function getExtra() {
        return $this->_extra;
    }
    
    /**
     * Return an Object OfferType.
     * @access public
     * @return \JobOffer\classes\domain\OfferType
     *  Return an Object OfferType.
     */
    public function getType() {
        return $this->_type;
    }
     
    /**
     * Replace the current id by new id, or asign the value from the Database.
     * @access public
     * @param int $id
     *  New id
     */
    public function setId($id) {
        $this->_id = $id;
    }
      
    /**
     * Replace the current title by new title, or asign the value from the Database.
     * @access public
     * @param string $title
     *  New title
     */
    public function setTitle($title) {
        $this->_title = $title;
    }
    
    /**
     * Replace the current intro by new introduction, or asign the value from the Database.
     * @access public
     * @param string $intro
     *  New introduction
     */
    public function setIntro($intro) {
        $this->_intro = $intro;
    }
        
    /**
     * Replace assignements by new assignements, or asign the value from the Database.
     * @access public
     * @param string $assignements
     *  New assignements
     */
    public function setAssignements($assignements) {
        $this->_assignments = $assignements;
    }
    
    /**
     * Replace knowledged by new knowledges, or asign the value from the Database.
     * @access public
     * @param string $knowledges
     *  New knowledges
     */
    public function setKnowledges($knowledges) {
        $this->_knowledges = $knowledges;
    }
    
    /**
     * Replace extra information by new extra informations, or asign the value from the Database.
     * @access public
     * @param string $extra
     *  New extra informations
     */                 
    public function setExtra($extra) {
        $this->_extra = $extra;
    }
    
    /**
     * Replace type of offer by another Type, or asign the value from the Database.
     * We verify in the first step if the type passed on parameter is a OfferType.
     * If the parameter isn't a OfferType, the type isn't modify.
     * @access public
     * @param \JobOffer\classes\domain\OfferType $type
     *  Type of offer.
     */
    public function setType(OfferType $type) {
        if ($type instanceof OfferType)
            $this->_type = $type;
    }
    
    /**
     * This function is used for hydrate Object.
     * For hydrate on object, call hydrate function on __construct 
     * and your object will be create directly without calling all setters functions.
     * @access public
     * @param array $data
     *  Data received from the database.
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
