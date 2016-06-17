<?php

require_once('Enum.class.php');

/**
 *
 * @author Nicolas GILLE
 */
class DAO {    
    /**
     * This function return the SELECT request. 
     * So, if you return all elements on the table, don't passed on arguments 
     * on is function, else, gieve a good value at $id for see one result.
     * 
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
        $tableName = $wpdb->prefix . 'job_offer';
        if ($id == -1) {
            $sql = 'SELECT [*] FROM ' . $tableName;
        } else {
            $sql = 'SELECT * FROM ' . $tableName . ' WHERE id = ' . $id;
        }
        return $wpdb->get_results($sql, ARRAY_A);   
    }
    
    /**
     * This function execute an insert request on the Database.
     * Insert in the database the Offer passed on parameter.
     * This function return the result of the sql query.
     * True if the request is successful, then return false.
     * 
     * @access public
     * @global object $wpdb
     *  Global Object present on WrodPress Core.
     * @param Offer $offer
     *  New Offer at add on Database.
     * @return bool
     *  True if the request is successful, then false.
     */
    public static function insert(Offer $offer) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $enum = new Enum();       
        $idType = $enum->getIdByKey($offer->getType()->getKey());
        
        $sql = $wpdb->insert($tableName,
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
        
        if (!$sql) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * This function update on entry in the Database.
     * For that, it using the Offer for research and update the good entry from the Database.
     * This function return the result of the sql query.
     * True if the request is successful, then return false.
     * 
     * @access public
     * @global object $wpdb
     *  Global Object present on WrodPress Core.
     * @param Offer $offer
     * @return bool
     *  True if the request is successful, then false.
     */
    public static function update(Offer $offer) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $sql = $wpdb->update($tableName,
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
        
        if (!$sql) 
            return false;
        else
            return true;
    }
    
    /**
     * This function delete on entry in the Database.
     * It using the Object passed on parameter for obtain the id
     * and remove the good entry from the Database.
     * This function return the result of the sql query.
     * True if the request is successful, then return false.
     * 
     * @access public
     * @global object $wpdb
     *  Global Object present on WordPress Core.
     * @param Offer $offer
     *  Offer who will delete.
     * @return bool
     *  True if the request is successful, then false.
     */
    public static function delete(Offer $offer) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $sql = $wpdb->delete($tableName, 
            array('id' => $offer->getId()), 
            array('%d')
        );
        
        if (!$sql) 
            return false;
        else
            return true;
    }
    
    /**
     * Return the MAX(id) present on database.
     * @global object $wpdb
     *  Global Object present on WordPress core.
     */
    public static function getMaxId() {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $max = $wpdb->get_row("SELECT MAX(id) AS max FROM " . $tableName, ARRAY_A);
        return $max['max'];
    }
    
}