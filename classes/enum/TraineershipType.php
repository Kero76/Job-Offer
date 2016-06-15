<?php

namespace JobOffer\classes\enum;

/**
 *
 * @author Nicolas GILLE
 */
class TraineershipType extends EnumType {
    
    /**
     * This constructor is used for creating enum element into enum.
     * Contains only the potential type of contract.
     * For example, can be found "Ingénieur, Bac +5, ...".
     * @access public
     * @param array $data
     *  Array with Key and Value values.
     */
    public function __construct($data) {
        $this->hydrate($data);
    }
}
