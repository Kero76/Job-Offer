<?php

/**
 * Includes Enum class because using Enum for insert new Offer.
 */
require_once('Enum.class.php');

/**
 * This class represent the connexion between the model and the view.
 * She implement the Design Pattern Singleton because only one connexion with the Database
 * are necessary for running the plugin and avoid to create at each time an object DAO.
 * 
 * @since Job Offer 1.0.1
 *  -> Implements Design Pattern Singleton.
 *  -> Replace $enum = new Enum() by $enum = Enum::get_instance()
 * @since Job Offer 1.0.0
 * @version 1.0.0
 */
class DAO {    
    
    /**
     * This is the only representation of the Object DAO.
     * 
     * @access private
     * @static
     * @var object
     *  Represent this.
     */
    private static $_instance = null;
    
    
    /**
     * Empty constructor only create for private visibility.
     * @access private
     */
    private function __construct() {}
    
   
    /**
     * Return an instance of DAO.
     * 
     * The object DAO implements the pattern singleton.
     * In fact, it's sufficient to create an instance of DAO for communicate with the Datbase.
     * It potentialy avoid to have 2 connexion at the same table at the same time.
     * 
     * @static
     * @return object 
     *  The only one instance of DAO.
     */
    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new DAO();
        } 
        return self::$_instance;
    }
    
    /**
     * This function return the SELECT request. 
     * So, if you return all elements on the table, don't passed any argument 
     * on is function.
     * Otherwise, if you would return an specific offer, return only one offer.
     * 
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     * @param int $id
     *  The id of the potential return value.
     * @return array
     *  Return an array with element(s).
     */
    public function query($id = -1) {
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
     * True if the request is successful, otherwise return false.
     * 
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     * @param Offer $offer
     *  New Offer to add on Database.
     * @return bool
     *  The state of the request.
     */
    public function insert(Offer $offer) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $enum = Enum::get_instance();       
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
     * True if the request is successful, otherwise return false.
     * 
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     * @param Offer $offer
     *  The new content of the offer.
     * @return bool
     *  The state of the request.
     */
    public function update(Offer $offer) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $enum = Enum::get_instance();       
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
     * True if the request is successful, otherwise return false.
     * 
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     * @param integer $id
     *  Id of offer who deleted.
     * @return bool
     *  The state of the request.
     */
    public function delete($id) {
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
     * @global object $wpdb
     *  It's a represent of the Database access create by WordPress.
     */
    public function get_max_id() {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $max = $wpdb->get_row("SELECT MAX(id) AS max FROM " . $tableName, ARRAY_A);
        return $max['max'];
    }
}