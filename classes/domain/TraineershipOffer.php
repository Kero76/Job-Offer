<?php

namespace JobOffer\classes\domain;

/**
 *
 * @author Nicolas GILLE
 */
class TraineershipOffer extends Offer {
    
    /**
     * Constuctor of Traineership offer.
     * @access public
     * @param mixed $data
     *  Data received from the database.
     */
    public function __construct($data) {
        $this->hydrate($data);
    }
}
