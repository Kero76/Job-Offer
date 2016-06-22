<?php

/**
 * Includes Enum class because using Enum for insert new Offer.
 */
require_once('Enum.class.php');

/**
 * This class represent the connexion between the model and the view.
 * All of DAO's functions are static because it avoid to create an object for using method.
 * 
 * @since Job Offer 1.0
 * @version 1.0
 */
class DAO {    
    /**
     * This function return the SELECT request. 
     * So, if you return all elements on the table, don't passed any argument 
     * on is function.
     * Otherwise, if you would return an specific offer, return only one offer.
     * 
     * @since Job Offer 1.0
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     * @param int $id
     *  The id of the potential return value.
     * @return array
     *  Return an array with element(s).
     */
    public static function query($id = -1) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        if ($id == -1) {
            $sql = 'SELECT * FROM ' . $tableName;
            return $wpdb->get_results($sql, ARRAY_A);   
        } else {
            $sql = 'SELECT * FROM ' . $tableName . ' WHERE id = ' . $id;
            return $wpdb->get_row($sql, ARRAY_A);   
        }
    }
    
    /**
     * This function execute an INSERT request on the Database.
     * Insert in the database the Offer passed on parameter.
     * This function return the result of the sql query.
     * True if the request is successful, then return false.
     * 
     * @since Job Offer 1.0
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     * @param Offer $offer
     *  New Offer to add on Database.
     * @return bool
     *  The state of the request.
     */
    public static function insert(Offer $offer) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $enum = new Enum();       
        $idType = $enum->get_id_by_key($offer->get_type()->get_key());
        
        $sql = $wpdb->insert(
            $tableName,
            array(
                'id' => $offer->get_id(),
                'title' => $offer->get_title(),
                'content' => $offer->get_content(),
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
     * This function UPDATE on entry in the Database.
     * For that, it using the Offer for research and update the good entry from the Database.
     * This function return the result of the sql query.
     * True if the request is successful, then return false.
     * 
     * @since Job Offer 1.0
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     * @param Offer $offer
     *  The new content of the offer.
     * @return bool
     *  The state of the request.
     */
    public static function update(Offer $offer) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $enum = new Enum();       
        $idType = $enum->get_id_by_key($offer->get_type()->get_key());        
        
        $sql = $wpdb->update(
            $tableName,
            array(
                'title' => $offer->get_title(),
                'content' => $offer->get_content(),
                'type' => $idType,
            ),
            array('id' => $offer->get_id()),
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
     * This function DELETE on entry in the Database.
     * It using the id passed on parameter for remove the good entry from the Database.
     * This function return the result of the sql query.
     * True if the request is successful, then return false.
     * 
     * @since Job Offer 1.0
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     * @param integer $id
     *  Id of offer who deleted.
     * @return bool
     *  The state of the request.
     */
    public static function delete($id) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $sql = $wpdb->delete(
            $tableName, 
            array('id' => $id), 
            array('%d')
        );
        
        if (!$sql) 
            return false;
        else
            return true;
    }
    
    /**
     * Return the last id present on database.
     * 
     * @since Job Offer 1.0
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     */
    public static function get_max_id() {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $max = $wpdb->get_row("SELECT MAX(id) AS max FROM " . $tableName, ARRAY_A);
        return $max['max'];
    }
}