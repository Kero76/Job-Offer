<?php

namespace JobOffer\classes\dao;

/**
 *
 * @author Nicolas GILLE
 * 
 * @TODO
 *  WORK IN PROGRESS, SEARCH A SOLUTION FOR DATABASE.
 */
class DAOTraineerOffer extends DAO{
    
    /**
     * This function return the SELECT request. 
     * So, if you return all elements on the table, don't passed on arguments 
     * on is function, else, gieve a good value at $id for see one result.
     * @access public
     * @param int $id
     *  The id of the potential return value.
     * @return array
     *  Return an array with research element.
     */
    public function query($id = -1) {
        global $wpdb;
        
        if ($id == -1) {
            $sql = 'SELECT [*] FROM TABLE_NAME';
        } else {
            $sql = 'SELECT * FROM TABLE_NAME WHERE id = ' . $id;
        }
        return $wpdb->get_results($sql);        
    }
    
    /**
     * This function execute an insert request on the Database.
     * Insert in the database the JobOffer passed on parameter.
     * @access public
     * @param \JobOffer\classes\domain\Offer $offer
     *  New JobOffer at add on Database.
     */
    public function insert(\JobOffer\classes\domain\Offer $offer) {
        global $wpdb;
        $wpdb->insert('WORDPRESS_TABLE_NAME',
            // represent like that : 'name_entry' => 'value_entry'
            array(
                
            ),
            // represent like this : '%s' => string, '%d' => int, '$f' => float
            array(
                
            )
        );
    }
    
    /**
     * This function update on entry in the Database.
     * For that, it using the JobOffer for research and update the good entry from the Database.
     * @access public
     * @param \JobOffer\classes\domain\Offer $offer
     */
    public function update(\JobOffer\classes\domain\Offer $offer) {
        global $wpdb;
        $wpdb->update('WORDPRESS_TABLE_NAME',
            // represent like that : 'name_entry' => 'value_entry'
            array(
                
            ),
            array('id' => $offer->getId()),
            // represent like this : '%s' => string, '%d' => int, '$f' => float, in correlation with first array
            array(
                
            ),
            array('%d')
        );
    }
    
    /**
     * This function delete on entry in the Database.
     * It using the Object passed on parameter for obtain the id
     * and remove the good entry from the Database.
     * @access public
     * @param \JobOffer\classes\domain\Offer $offer
     */
    public function delete(\JobOffer\classes\domain\Offer $offer) {
        global $wpdb;
        $wpdb->delete('WORDPRESS_TABLE_NAME', 
            array('id' => $offer->getId()), 
            array('%d')
        );
    }
}
