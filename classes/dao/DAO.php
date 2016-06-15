<?php

namespace JobOffer\classes\dao;

/**
 *
 * @abstract
 * @author Nicolas GILLE
 */
abstract class DAO {
    
    /**
     * This function return the SELECT request. 
     * So, if you return all elements on the table, don't passed on arguments 
     * on is function, else, gieve a good value at $id for see one result.
     * @access public
     * @param int $id
     *  The id of the potential return value.
     */
    public function query($id = -1);
    
    /**
     * This function execute an insert request on the Database.
     * Insert in the database the JobOffer passed on parameter.
     * @access public
     * @param \JobOffer\classes\domain\Offer $offer
     *  New JobOffer at add on Database.
     */
    public function insert(\JobOffer\classes\domain\Offer $offer);
    
    /**
     * This function update on entry in the Database.
     * For that, it using the JobOffer for research and update the good entry from the Database.
     * @access public
     * @param \JobOffer\classes\domain\Offer $offer
     */
    public function update(\JobOffer\classes\domain\Offer $offer);
    
    /**
     * This function delete on entry in the Database.
     * It using the Object passed on parameter for obtain the id
     * and remove the good entry from the Database.
     * @access public
     * @param \JobOffer\classes\domain\Offer $offer
     */
    public function delete(\JobOffer\classes\domain\Offer $offer);
}
