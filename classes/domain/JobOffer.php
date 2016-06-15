<?php

namespace JobOffer\classes\domain;

/**
 *
 * @author Nicolas GILLE
 */
class JobOffer extends Offer {
    
    /**
     * Constuctor of Job offer.
     * @access public
     * @param mixed $data
     *  Data received from the database.
     */
    public function __construct($data) {
        $this->hydrate($data);
    }
}
