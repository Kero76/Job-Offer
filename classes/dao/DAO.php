<?php

namespace JobOffer\classes\dao;

use JobOffer\classes\enum\Enum;

/**
 *
 * @author Nicolas GILLE
 */
class DAO {
    
    /**
     * This function return the SELECT request. 
     * So, if you return all elements on the table, don't passed on arguments 
     * on is function, else, gieve a good value at $id for see one result.
     * @access public
     * @global object $wpdb
     *  Global Object present on WrodPress Core.
     * @param int $id
     *  The id of the potential return value.
     * @return array
     *  Return an array with element(s).
     */
    public static function query($id = -1) {
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
     * Insert in the database the Offer passed on parameter.
     * @access public
     * @global object $wpdb
     *  Global Object present on WrodPress Core.
     * @param \JobOffer\classes\domain\Offer $offer
     *  New Offer at add on Database.
     */
    public static function insert(\JobOffer\classes\domain\Offer $offer) {
        global $wpdb;
        $idType = Enum::getIdByKey($offer->getType()->getKey());
        $wpdb->insert('WORDPRESS_TABLE_NAME',
            array(
                'id' => $offer->getId(),
                'title' => $offer->getTitle(),
                'content' => $offer->getContent(),
                'type' => $idType,
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%d',
            )
        );
    }
    
    /**
     * This function update on entry in the Database.
     * For that, it using the Offer for research and update the good entry from the Database.
     * @access public
     * @global object $wpdb
     *  Global Object present on WrodPress Core.
     * @param \JobOffer\classes\domain\Offer $offer
     */
    public static function update(\JobOffer\classes\domain\Offer $offer) {
        global $wpdb;
        $wpdb->update('WORDPRESS_TABLE_NAME',
            array(
                'title' => '',
                'content' => '',
                'type' => '',
            ),
            array('id' => $offer->getId()),
            array(
                '%s',
                '%s',
                '%d',
            ),
            array('%d')
        );
    }
    
    /**
     * This function delete on entry in the Database.
     * It using the Object passed on parameter for obtain the id
     * and remove the good entry from the Database.
     * @access public
     * @global object $wpdb
     *  Global Object present on WrodPress Core.
     * @param \JobOffer\classes\domain\Offer $offer
     */
    public static function delete(\JobOffer\classes\domain\Offer $offer) {
        global $wpdb;
        $wpdb->delete('WORDPRESS_TABLE_NAME', 
            array('id' => $offer->getId()), 
            array('%d')
        );
    }
}