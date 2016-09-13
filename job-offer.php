<?php
/*
Plugin Name: Job Offer
Plugin URI: 
Description: This plugin is used for create job or traineership offer for your website. In fact, lot of companies website wish create job or traineeship offer for recrut. So thanks Job Offer, these societies can generate directly offers on their website.
Version: 1.2.2
Author: Nicolas GILLE
Author URI: http://nicolas-gille.fr
Licence: GPLv2+ or later
Licence URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: job-offer
Domain Path: /languages/

Job Offer is a WordPress plugin for added job or traineeship offer in your website.
Copyright (C) 2016 Nicolas GILLE<nic.gille@gmail.com>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

 */

/**
 * Include EnumType class.
 */
require_once('classes/EnumType.class.php');

/**
 * Include Enum class.
 */
require_once('classes/Enum.class.php');

/**
 * Include DAO class for interact with Database.
 */
require_once('classes/DAO.class.php');

/**
 * Include Offer class for interact with DAO object..
 */
require_once('classes/Offer.class.php');

if (!class_exists('JobOffer')) {

    /**
     * Main class of plugin.
     * 
     * This class is the main class of plugin. 
     * In fact, it centralize all methods which enable to run the plugin.
     *
     * @since Job Offer 1.2.2
     *  -> Remove $enum = Enum::get_instance() and replace $enum by get_instance() method.
     *  -> Remove $_dao attribute and replace it by DAO::get_instance().
     *  -> Fix translation failed in Frontend method.
     *  -> Fix translation failed in custom script.
     *  -> Fix translation failed on pop-up deletion.
     * @since Job Offer 1.2.1
     *  -> Added visibility range in job_offer table creation.
     *  -> Added parameter visibility in all functions using DAO functions.
     * @since Job OFfer 1.1.2
     *  -> Removed Sanitizer class include because unused now.
     *  -> Register specific type of post for Job Offer.
     * @since Job Offer 1.1.1
     *  -> Fixed url writting with &amp; insteand of &.
     * @since Job Offer 1.1.0
     *  -> Added class Sanitizer for sanitize href value on shortcode_all_offers() method.
     *  -> Autogenerate good link bewteen all offers page and individual page.
     * @since Job Offer 1.0.1
     *  -> Replace $enum = new Enum() by $enum = Enum::get_instance().
     *  -> Replace calling DAO's method static by a simple called method.
     *  -> Creation of private method _create_offer($title, $content, $type).
     *  -> Creation of private method _update_offer($id, $title, $content, $type).
     *  -> Modify function shortcode_all_offers for generate a link for see all informations about offers.
     * @since Job Offer 1.0.0
     * @version 1.2.0
     */
    class JobOffer {
        /**
         * Constructor used for initialized plugin methods.
         * 
         * This constructor is used for adding action when create by application.
         * In fact, he add all necessary actions to make it usable application.
         */
        public function __construct() {
            if (function_exists('add_action')) {
                $page = add_action('admin_menu', array($this, 'init'));
                add_action('load-' . $page, array($this, 'register_stylesheet'));
                add_action('admin_enqueue_scripts', array($this, 'register_stylesheet'));
                add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
                add_action('plugins_loaded', array($this, 'load_textdomain'));
                add_action('init', array($this, 'register_custom_type_post'));
            }
            if (function_exists('add_shortcode')) {
                add_shortcode('jo_jobs', array($this, 'shortcode_all_offers'));
                add_shortcode('jo_job', array($this, 'shortcode_offer'));
            }
        }
        
        /**
         * Create a link in sub menu settings in dahsboard.
         * 
         * These options appear under settings menu because plugin not used everyday (convention from WordPress).
         */
        public function init() {
            if (function_exists('add_options_page')) {
                add_options_page('Job Offer Options', 
                                 'Job Offer', 
                                 'administrator', 
                                 __FILE__, 
                                 array($this, 'create_form_page'));
            }
        }
        
        /**
         * Install the plugin in WordPress.
         * 
         * When your activate this plugin, this method create new table in database.
         * This table is used for stored all data from the offers.
         * 
         * @global object $wpdb
         *  It's a represent of the Database access create by WordPress.
         */
        public function install() {
            global $wpdb;
            $tableName = $wpdb->prefix. 'job_offer';
            if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
                $sql = "CREATE TABLE `$tableName` (
                    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `title` VARCHAR(255) NOT NULL,
                    `content` TEXT NOT NULL,
                    `type` INT NOT NULL,
                    `visibility` TINYINT(1) NOT NULL
                )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
            }
        }
        
        /**
         * Initialize template for interact with database.
         * 
         * This method allows to apply the methods of addition, deletion or modification
         * of various offers used by the admin part.
         * In fact, she redirige the admin from the good form page.
         */
        public function create_form_page() {
            switch($_GET['p']) {
                /** Insert page **/
                case 'insert' :
                    require_once('template-add-offer-admin-page.php');
                    break;
                
                /** Updating page **/
                case 'update' :
                    require_once('template-update-offer-admin-page.php');
                    break;
                
                /** View page **/
                default:
                    require_once('template-view-offer-admin-page.php');
                    break;
            }
            
            switch($_GET['action']) {
                
                /** INSERT SECTION **/
                case 'addoffer' :
                    if (isset($_POST['jo_title'], $_POST['jo_content'], $_POST['jo_type'], $_POST['jo_visibility'])) {
                        if ((trim($_POST['jo_title']) != '') && (trim($_POST['jo_content']) != '') && (trim($_POST['jo_type']) != '') && $_POST['jo_visibility']) {                  
                            if ($this->_insert_offer($_POST['jo_title'], $_POST['jo_content'], $_POST['jo_type'], $_POST['jo_visibility'])) {
                                wp_redirect(get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&addoffer=ok');
                            } else {
                                echo '<span class="job-offer-error job-offer-bold">' . __('An error occured, please contact a developper for fix it.', 'job-offer') . '</span>';
                            }
                        }
                    }
                    break;
                    
                /** UPDATE SECTION **/
                case 'updateoffer' :
                    if (isset($_POST['jo_title'], $_POST['jo_content'], $_POST['jo_type'], $_POST['jo_id'])) {
                        if ((trim($_POST['jo_title']) != '') && (trim($_POST['jo_content']) != '') && (trim($_POST['jo_type'] && $_POST['jo_visibility']) != '')) {
                            if ($this->_update_offer($_POST['jo_id'], $_POST['jo_title'], $_POST['jo_content'], $_POST['jo_type'], $_POST['jo_visibility'])) {
                                wp_redirect(get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&updateoffer=ok');
                            } else {
                                echo '<span class="job-offer-error job-offer-bold">' . __('An error occured, please contact a developper for fix it.', 'job-offer') . '</span>';
                            }
                        } 
                    }
                    break;
                
                /** DELETE SECTION **/
                case 'deleteoffer' :
                    if (isset($_GET['id'])) {
                        if (DAO::get_instance()->delete(intval($_GET['id']))) {
                            wp_redirect(get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&deleteoffer=ok');
                        } else {
                            echo '<span class="job-offer-error job-offer-bold">' . __('An error occured, please contact a developper for fix it.', 'job-offer') . '</span>';
                        }
                    }
                    break;
                
                /** DEFAULT SECTION **/
                default :
                    break;
            }
            
            /** MESSAGE SUCCESSFUL SECTION **/
            /* /!\ Not Working for the moment because header('Location:'); not working. */
            if (isset($_GET['offer'])) {
                switch ($_GET['offer']) {
                    case 'addoffer' :
                        echo '<span class="job-offer-success job-offer-bold">' . __('Your offer was correctly inserted.', 'job-offer') . '</span>';
                        break;
                    
                    case 'deleteoffer' :
                        echo '<span class="job-offer-success job-offer-bold">' . __('Your offer was correctly deleted.', 'job-offer') . '</span>';
                        break;
                    
                    case 'updateoffer' : 
                        echo '<span class="job-offer-success job-offer-bold">' . __('Your offer was correctly updated.', 'job-offer') . '</span>';
                        break;
                    
                    default :
                        break;
                }
            }
       }
       
        /**********************************************/
        /*            GET OFFERS SECTION              */
        /**********************************************/
       
        /**
         * Return all offers from Database.
         * 
         * This method return all offers present in Database.
         * When it create the array, create a new Offer object for each entry.
         * Then, return the array with all Offers presents in Database.
         * 
         * @param array $request
         *  Request issue from Database query.
         * @return array
         *   An array who composed by Offer Object.
         */
        public function get_offers(array $request) {
            $offers = $request;
            $results = array();
            foreach($offers as $offer) {
                $data = array(
                    'id'         => $offer['id'],
                    'title'      => $offer['title'],
                    'content'    => $offer['content'],
                    'type'       => new EnumType(Enum::get_instance()->get_key_by_id(intval($offer['type']))),
                    'visibility' => $offer['visibility'],
                );
                array_push($results, new Offer($data));
            }
            return $results;
        }
        
        /**
         * Return on offer frome Database.
         * 
         * This method return a single Offer object from the database.
         * Using the id for return the good entry, generated Offer object and return it.
         * 
         * @param integer $id
         *  The id of offer which search.
         * @return object
         *  A hydrate object with the data retrieved from the database.
         */
        public function get_offer($id) {
            $offer = DAO::get_instance()->query($id);
            
            $data = array(
                'id'         => $offer['id'],
                'title'      => $offer['title'],
                'content'    => $offer['content'],
                'type'       => new EnumType(Enum::get_instance()->get_key_by_id(intval($offer['type']))),
                'visibility' => $offer['visibility'],
            );
            return new Offer($data);
        }
        
        /**********************************************/
        /*            REGISTERS SECTION               */
        /**********************************************/

        /**
         * Register and enqueue stylesheet files.
         * 
         * This method register and enqueue plugin stylesheet.
         * It used for activate custom css from plugin.
         */
        public function register_stylesheet() {
            if (function_exists('wp_register_style') && function_exists('wp_enqueue_style')) {
                wp_register_style('job-offer-style', plugins_url('css/job-offer-style.css', __FILE__));
                wp_enqueue_style('job-offer-style');
            }
        }
        
        /**
         * Register, translate and enqueue script files.
         * 
         * This method register and enqueue plugin script.
         * It used for activate custom javascript from plugin.
         */
        public function register_scripts() {
            if (function_exists('wp_register_script') && function_exists('wp_enqueue_script')) {
                wp_register_script('job-offer-admin-script', plugins_url('js/admin.js', __FILE__), 'jquery', '1.0');
                wp_enqueue_script('jquery');
                wp_enqueue_script('job-offer-admin-script');
                
                if (function_exists('wp_localize_script')) {
                    wp_localize_script('job-offer-admin-script', 'jo_translation', array(
                        'empty_title'       => __('Title is empty.', 'job-offer'),
                        'empty_content'     => __('Content is empty.', 'job-offer'),
                        'confirm_deletion'  => __('Really want to delete this offer ? (This action is irreversible)', 'job-offer'),
                    ));
                }
            }
        }
        
        /**
         * Register a new type of post on WordPress.
         * 
         * This methid register a new type of post named Offers.
         */
        public function register_custom_type_post() {
            if (function_exists(register_post_type)) {
                register_post_type('job-offer', array(
                    'labels' => array(
                        'name'                  => __('Offers', 'job-offer'),
                        'singular_name'         => __('Offer', 'job-offer'),
                        'name_admin_bar'        => __('Offers', 'job-offer'),
                        'add_new'               => __('Add new Offer', 'job-offer'),
                        'add_new_item'          => __('Add new Offer', 'job-offer'),
                        'new_item'              => __('New Offer', 'job-offer'),
                        'edit_item'             => __('Edit Offer', 'job-offer'),
                        'view_new'              => __('View Offer', 'job-offer'),
                        'all_items'             => __('All Offers', 'job-offer'),
                        'parent_item_colon'     => __('Parent Offers:', 'job-offer'),
                        'search_items'          => __('Search Offers', 'job-offer'),
                        'not_found'             => __('No offers found', 'job-offer'),
                        'not_found_in_trash'    => __('No offers found in Trash', 'job-offer'),
                    ),
                    'description'       => __('Job or Traineeship offers.', 'job-offer'),
                    'public'            => true,
                    'query_var'         => true,
                    'rewrite'           => array('slug' => 'offer', 'with_front' => false, 'feed' => false),
                    'capability_type'   => 'post',
                    'menu_position'     => null,
                    'supports'          => array('title', 'editor', 'post-formats'),
                    'menu_icon'         => 'dashicons-businessman',
                ));
                $this->flush_rewrite_rules();
            }
        }
        
        /**
         * Loaded textdomain for translations.
         * 
         * This method load textdomain for add translations.
         * It used for activate plugin internationalization.
         */
        public function load_textdomain() {
            if (function_exists('load_plugin_textdomain')) {
                load_plugin_textdomain('job-offer', false, dirname(plugin_basename(__FILE__)) . '/languages');
            }
        }
        
        /**
         * Flush rewrite rules.
         * 
         * This method flush current rewrite rules and add rules about Job Offer.
         */
        public function flush_rewrite_rules() {
            if(function_exists('flush_rewrite_rules')) {
                flush_rewrite_rules();
            }
        }
        
        /**********************************************/
        /*            SHORTOCODE SECTION              */
        /**********************************************/
        
        /**
         * Return all offers.
         * 
         * This method is used for displaying all offers present in database.
         * It used for generate a shortcode who placed on post page.
         */
        public function shortcode_all_offers() {
            $offers = $this->get_offers(DAO::get_instance()->get_publish_post());
                        
            if (count($offers) == 0) {
                $str = '<p>' . __('Nothing offers are currently present.', 'job-offer') . '</p>';
            } else {
                $str = '<table>';
                $str .= '<tr>';
                $str .= '<th>' . __('Type of offers', 'job-offer') . '</th>';
                $str .= '<th>' . __('Offer title', 'job-offer') . '</th>';
                $str .= '</tr>';

                foreach($offers as $offer) {
                    $str .= '<tr>';
                    $str .= '<td><div class="' . str_replace(' ', '_', strtolower($offer->get_type()->get_key())) . '">' . $offer->get_type()->get_key()  . '</div></td>';
                    $str .= '<td><a class="job-offer-link" href="' . sanitize_title($offer->get_title()) . '">' . stripslashes($offer->get_title()) . '</a></td>';
                    $str .= '<input type="hidden" value="' . $offer->get_id() . '" id="jo_id" name="jo_id" />';
                    $str .= '</tr>';
                }
                $str .= '</table>';
            }
            return $str;
       }
       
        /**
         * Return one offer from Database.
         * 
         * This method is used for displaying 1 offer.
         * It used for create a shortcode used for see description from 1 offer.
         * 
         * @param integer $id
         *   Identifiant of the offer.
         * @return string
         *   The content of the page used on a post page.
         */
       public function shortcode_offer($id) {
           if ($id >= 0) {
                $offer = $this->get_offer($id['id']);
                $str = '<h2 id="job-offer-title-post">' . stripslashes($offer->get_title()) . '</h2>';
                $str .= '<p id="job-offer-content-post>' . stripslashes($offer->get_content()) . '</p>';
           } else {
               $str = '<p>' . __('No offer found') . '</p>';
           }
           return $str;
       }
              
        /**********************************************/
        /*        PRIVATE FUNCTION SECTION            */
        /**********************************************/
       
       /**
        * Added offer in Database.
        * 
        * This method added a new offer in Database.
        * For that, using all parameters for creating a new Offer, 
        * and after creation, send it into Database.
        * 
        * @access private
        * @param string $title
        *   Title of the Offer.
        * @param string $content
        *   Content of the Offer.
        * @param integer $type
        *   Rank of the value present in Enum.
        * @param string $visibility
        *   Visibility of the Offer with Yes or No value.
        * @return bool
        *   The state of the request.
        */
       private function _insert_offer($title, $content, $type, $visibility) {
            $id = DAO::get_instance()->get_max_id() + 1;
            if ($id == '') {
                $id = 0;
            }
            
            if ($visibility === "Yes") {
                $is_visible = true;
            } else {
                $is_visible = false;    
            }

            $offerType = Enum::get_instance()->get_enum()[$type];
            $data = array(
                'id'         => intval($id),
                'title'      => stripslashes($title),
                'content'    => $content,
                'type'       => $offerType,
                'visibility' => $is_visible,
            );
            $offer = new Offer($data);
            return DAO::get_instance()->insert($offer);
       }

       /**
        * Update an offer present in Database.
        * 
        * This fucntion updated an offer present in Database.
        * Using Id for modify the good entry from Database and construct 
        * an object Offer for send it into DAO method. 
        * 
        * @access private
        * @param integer $id 
        *   Id of the offer on Database.
        * @param string $title
        *   Title of the Offer.
        * @param string $content
        *   Content of the Offer.
        * @param integer $type
        *   Rank of the value present in Enum.
        * @param string $visibility
        *   Visibility of the Offer.
        * @return bool
        *   The state of the request.
        */
       private function _update_offer($id, $title, $content, $type, $visibility) {
            $offerType = Enum::get_instance()->get_enum()[$type];
            
            if ($visibility === "Yes") {
                $is_visible = true;
            } else {
                $is_visible = false;    
            }
            
            $data = array(
                'id'         => intval($id),
                'title'      => sanitize_text_field($title),
                'content'    => $content,
                'type'       => $offerType,
                'visibility' => $is_visible,
            );
            $offer = new Offer($data);
            return DAO::get_instance()->update($offer);
       }
    } /** END CLASS **/
}

/*
 * Creation of an instance of JobOffer object 
 * if class not exists in WordPress core or other plugin.
 */
if (class_exists('JobOffer')) {
    $jobOffer = new JobOffer();
}

/*
 * If variable not empty, so, we register activation 
 * and add an action for see the option menu of this plugin.
 */
if (isset($jobOffer)) {
    register_activation_hook(__FILE__, array($jobOffer, 'install'));
    register_activation_hook(__FILE__, array($jobOffer, 'flush_rewrite_rules'));
}
