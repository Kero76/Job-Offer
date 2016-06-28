<?php

/**
 * Includes Enum class because using Enum for insert new Offer.
 */
require_once('Enum.class.php');

/**
 * Includes Sanitizer class for sanitize post name before insert it into Database.
 */
require_once('Sanitizer.class.php');

/**
 * This class represent the connexion between the model and the view.
 * She implement the Design Pattern Singleton because only one connexion with the Database
 * are necessary for running the plugin and avoid to create at each time an object DAO.
 * 
 * @since Job Offer 1.1.1
 *  -> Added method get_publish_post() for retrieve only the post with publish status for display it on front page.
 *  -> Moved insert, update and delete wordpress posts table in private function.
 *  -> Changed post status to publish at pending, for see and validate offer before send it on website.
 * @since Job Offer 1.1.0
 *  -> Added class Sanitize for sanitize post name before added on WordPress Database.
 * @since Job Offer 1.0.1
 *  -> Implements Design Pattern Singleton.
 *  -> Replace $enum = new Enum() by $enum = Enum::get_instance()
 *  -> Added ORDER BY id ASC into query method.
 *  -> Creation, deletion and update wp_posts table for create post for each Offer from custom table.
 * @since Job Offer 1.0.0
 * @version 1.1.2
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
     * This method return the SELECT request. 
     * So, if you return all elements on the table, don't passed any argument 
     * on is method.
     * Otherwise, if you would return an specific offer, return only one offer.
     * 
     * @global object $wpdb
     *  It's a representant of the Database access create by WordPress.
     * @param int $id
     *  The id of the potential return value.
     * @return array
     *  Return an array with element(s).
     */
    public function query($id = -1) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        if ($id == -1) {
            $sql = 'SELECT * FROM ' . $tableName . ' ORDER BY id ASC';
            return $wpdb->get_results($sql, ARRAY_A);   
        } else {
            $sql = 'SELECT * FROM ' . $tableName . ' WHERE id = ' . $id;
            return $wpdb->get_row($sql, ARRAY_A);   
        }
    }
    
    /**
     * This method execute an INSERT request on the Database.
     * Insert in the database the Offer passed on parameter.
     * This method return the result of the sql query.
     * True if the request is successful, otherwise return false.
     * 
     * @global object $wpdb
     *  It's a representant of the Database access create by WordPress.
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
            $this->_insert_post($offer);
            return true;
        }
    }
    
    /**
     * This method UPDATE on entry in the Database.
     * For that, it using the Offer for research and update the good entry from the Database.
     * This method return the result of the sql query.
     * True if the request is successful, otherwise return false.
     * 
     * @global object $wpdb
     *  It's a representant of the Database access create by WordPress.
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
        
        $oldData = $this->query($offer->get_id());
        
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
        
        if (!$sql) {
            return false;
        } else {
            $this->_update_post($offer, $oldData);
            return true;
        }
    }
    
    /**
     * This method DELETE on entry in the Database.
     * It using the id passed on parameter for remove the good entry from the Database.
     * This method return the result of the sql query.
     * True if the request is successful, otherwise return false.
     * 
     * @global object $wpdb
     *  It's a representant of the Database access create by WordPress.
     * @param integer $id
     *  Id of offer who deleted.
     * @return bool
     *  The state of the request.
     */
    public function delete($id) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        
        $oldData = $this->query($id);
        
        $sql = $wpdb->delete(
            $tableName, 
            array('id' => $id), 
            array('%d')
        );
        
        if (!$sql) {
            return false;
        } else {
            $this->_delete_post($oldData);
            return true;
        }
    }
    
    /**
     * Return the last id present on database.
     * 
     * @global object $wpdb
     *  It's a representant of the Database access create by WordPress.
     */
    public function get_max_id() {
        global $wpdb;
        $tableName = $wpdb->prefix . 'job_offer';
        $max = $wpdb->get_row("SELECT MAX(id) AS max FROM " . $tableName, ARRAY_A);
        return $max['max'];
    }
    
    /**
     * Return offer post with status publish.
     * 
     * It used because when we add new offer post on Database, the status is pending
     * and while the status is pending, we can't see offer informations before upadting status post.
     * 
     * @global object $wpdb
     *  It's a representant of the Database access create by WordPress.
     */
    public function get_publish_post() {
        global $wpdb;
        $job_offer_table = $wpdb->prefix. 'job_offer';
        $post_table = $wpdb->prefix . 'posts';
        
        $parameters = array(
            'jo_id'         => $job_offer_table . '.id',
            'jo_title'      => $job_offer_table . '.title',
            'jo_content'    => $job_offer_table . '.type',
            'post_title'    => $post_table . '.post_title',
        );
        
        $sql = "SELECT " . $parameters['jo_id'] . ", " . $parameters['jo_title'] . ", " . $parameters['jo_content'] .
                " FROM " . $job_offer_table . 
                " JOIN " . $post_table . 
                " ON "   . $parameters['post_title'] . " = " . $parameters['jo_title'] .
                " WHERE `post_status` = 'publish' ORDER BY " . $parameters['jo_id'] . " ASC";
        
        return $wpdb->get_results($sql, ARRAY_A);
    }
    
    /**********************************************/
    /*     PRIVATE WORDPRESS ACCESS DB SECTION    */
    /**********************************************/
    
    /**
     * Insert a new post after offer information.
     * 
     * The new post are create with draft stauts. 
     * In fact, it can see and validate offer before send it in website.
     * 
     * @access private
     * @param Offer $offer
     *  The offer is used for receive data to insert on post value.
     */
    private function _insert_post(Offer $offer) {
        if (function_exists('get_current_user_id')) {
            $id_user = get_current_user_id();
        } else {
            $id_user = 1;
        }

        $post_name = '';
        if (class_exists('Sanitizer')) {
            $sanitizer = new Sanitizer($offer->get_title());
            $sanitizer->sanitize_post_name();
            $post_name = $sanitizer->get_string();
        }

        if ($post_name != '') {
            $post = array(
                'post_title'        => $offer->get_title(),
                'post_content'      => $offer->get_content(),
                'post_name'         => $post_name,
                'post_status'       => 'pending',
                'post_author'       => $id_user,
                'comment_status'    => 'closed',
            );
        } else {
            $post = array(
                'post_title'        => $offer->get_title(),
                'post_content'      => $offer->get_content(),
                'post_status'       => 'pending',
                'post_author'       => $id_user,
                'comment_status'    => 'closed',
            ); 
        }
        wp_insert_post($post);
    }
    
    /**
     * Update a post from WordPress table.
     * 
     * This function update the post issue from posts table.
     * It execute after offer's update and modify directly the post after new data.
     * 
     * @access private
     * @global object $wpdb
     *  It's a representant of the Database access create by WordPress.
     * @param Offer $offer
     *  Offer for update content and title of post in posts table.
     * @param array $oldData
     *  This array contains all informations needed for update the good post.
     */
    private function _update_post(Offer $offer, array $oldData) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'posts';
        $request = 'SELECT ID FROM `' . $tableName . '` WHERE `post_title` = "' . $oldData['title'] . '"';
        $result = $wpdb->get_row($request, ARRAY_A);

        $wpdb->update(
            $tableName,
            array(
                'post_title' => $offer->get_title(),
                'post_content' => $offer->get_content(),
            ),
            array('ID' => $result['ID']),
            array(
                '%s',
                '%s',
            ),
            array('%d')
        );
    }
    
    /**
     * Delete a post in table posts.
     * 
     * @global object $wpdb
     *  It's a representant of the Database access create by WordPress.
     * @param array $oldData
     *  This array contains all informations needed for delete the good post.
     */
    private function _delete_post(array $oldData) {
        global $wpdb;
        $tableName = $wpdb->prefix . 'posts';
        $request = 'SELECT ID FROM `' . $tableName . '` WHERE `post_title` = "' . $oldData['title'] . '"';
        $result = $wpdb->get_row($request, ARRAY_A);
        $wpdb->delete(
            $tableName, 
            array('ID' => $result['ID']), 
            array('%d')
        );
    }
}