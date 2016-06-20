<?php
/*
 * Plugin Name: Job Offer
 * Plugin URI: 
 * Description: This plugin is used for create job or traineership offer for your website.
 * Version: 0.1
 * Author: Nicolas GILLE
 * Author URI:
 * Licence: GPL2+ or later
 * Licence URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: job-offer
 */

/*
 * This is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * This is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

require_once('classes/Offer.class.php');
require_once('classes/EnumType.class.php');
require_once('classes/Enum.class.php');
require_once('classes/DAO.class.php');

if (!class_exists('JobOffer')) {
    class JobOffer {
        
        /**
         * This constructor is used for adding action when create by application.
         * 
         * @access public
         */
        public function __construct() {
            if (function_exists('add_action')) {
                $page = add_action('admin_menu', array($this, 'init'));
                add_action('load-' . $page, array($this, 'registerStylesheet'));
                add_action('admin_enqueue_scripts', array($this, 'registerStylesheet'));
                add_action('admin_enqueue_scripts', array($this, 'registerScripts'));
                //add_action('init', array($this, 'createPost'));
            }
            if (function_exists('add_shortcode')) {
                add_shortcode('jo_jobs', array($this, 'shortcodeAllOffers'));
                add_shortcode('jo_job', array($this, 'shortcodeOffer'));
            }
        }
        
        /**
         * This function initialize Job Offer plugin.
         * 
         * @access public
         */
        public function init() {
            if (function_exists('add_options_page')) {
                add_options_page('Job Offer Options', 
                                 'Job Offer', 
                                 'administrator', 
                                 __FILE__, 
                                 array($this, 'createFormPage'));
            }
        }
        
        /**
         * Function called when you activate the plugin.
         * When your activate this plugin, you create a new table in database using by the plugin.
         * 
         * @access public
         * @global object $wpdb
         *  Global Object present on WrodPress Core.
         */
        public function install() {
            global $wpdb;
            $tableName = $wpdb->prefix. 'job_offer';
            if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
                $sql = "CREATE TABLE `$tableName` (
                    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `title` VARCHAR(255) NOT NULL,
                    `content` TEXT NOT NULL,
                    `type` INT NOT NULL
                )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
            }
        }
        
        /**
         * This function allows to apply the functions of addition, deletion or modification
         * of various offers used by the admin part.
         * 
         * @access public
         */
        public function createFormPage() {
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
                    if ((trim($_POST['jo_title']) != '') && (trim($_POST['jo_content']) != '') && (trim($_POST['jo_type']) != '')) {                    
                        $id = DAO::getMaxId() + 1;
                        if ($id == '') {
                            $id = 0;
                        }
                        $enum = new Enum();
                        $offerType = $enum->getEnum()[$_POST['jo_type']];
                        $data = array(
                            'id' => $id,
                            'title' => $_POST['jo_title'],
                            'content' => $_POST['jo_content'],
                            'type' => $offerType,
                        );
                        $offer = new Offer($data);
                        if (DAO::insert($offer)) {
                            //header('Location:' . get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&offer=ok');
                        } else {
                            echo '<span class="error-job-offer bold-job-offer">An error occured, please contact a developper for fix it.</span>';
                        }
                    }
                    break;
                
                /** UPDATE SECTION **/
                case 'updateoffer' :
                    if ((trim($_POST['jo_title']) != '') && (trim($_POST['jo_content']) != '') && (trim($_POST['jo_type']) != '')) {
                        $enum = new Enum();
                        $offerType = $enum->getEnum()[$_POST['jo_type']];
                        $data = array(
                            'id' => $_POST['jo_id'],
                            'title' => $_POST['jo_title'],
                            'content' => $_POST['jo_content'],
                            'type' => $offerType,
                        );
                        $offer = new Offer($data);
                        if (DAO::update($offer)) {
                            //header('Location:' . get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&updateoffer=ok');
                        } else {
                            echo '<span class="error-job-offer bold-job-offer">An error occured, please contact a developper for fix it.</span>';
                        }
                    }
                    break;
                
                /** DELETE SECTION **/
                case 'deleteoffer' :
                    if (isset($_GET['id'])) {
                        if (DAO::delete(intval($_GET['id']))) {
                            //header('Location:' . get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&updateoffer=ok');
                        } else {
                            echo '<span class="job-offer-error job-offer-bold">An error occured, please contact a developper for fix it.</span>';
                        }
                    }
                    break;
                
                /** DEFAULT SECTION **/
                default :
                    
                    break;
            }
            
            if ($_GET['offer'] == 'addok') {
                echo '<span class="job-offer-success job-offer-bold">Your offer was correctly inserted.</span>';
            } else if ($_GET['offer'] == 'deleteok') {
                echo '<span class="job-offer-success job-offer-bold">Your offer was correctly deleted.</span>';
            } else if ($_GET['offer'] == 'updateOffer') {
                echo '<span class="job-offer-success job-offer-bold">Your offer was correctly updated.</span>';
            }
       }
       
       /**
        * This function return all offers present in Database.
        * 
        * @access public
        * @return array
        *   An array who composed by Offer Object.
        */
        public function getOffers() {
            $offers = DAO::query();
            $enum = new Enum();
            $results = array();
            foreach($offers as $offer) {                
                $data = array(
                    'id' => $offer['id'],
                    'title' => $offer['title'],
                    'content' => $offer['content'],
                    'type' => new EnumType($enum->getKeyById(intval($offer['type']))),
                );
                array_push($results, new Offer($data));
            }
            return $results;
        }
        
        /**
         * This function return a single Offer object from the database.
         * Using the id for return the good entry.
         * 
         * @access public
         * @param integer $id
         *  The id of offer.
         * @return object
         *  A single Offer.
         */
        public function getOffer($id) {
            $offer = DAO::query($id);
            $enum = new Enum();
            
            $data = array(
                'id' => $offer['id'],
                'title' => $offer['title'],
                'content' => $offer['content'],
                'type' => new EnumType($enum->getKeyById(intval($offer['type']))),
            );
            return new Offer($data);
        }

        /**
         * This function register and enqueue plugin stylesheet.
         * 
         * @access public
         */
        public function registerStylesheet() {
            wp_register_style('job-offer-style', plugins_url('css/job-offer-style.css', __FILE__));
            wp_enqueue_style('job-offer-style');
        }
        
        /**
         * This function register and enqueue plugin script.
         * 
         * @access public
         */
        public function registerScripts() {
            wp_register_script('job-offer-admin-script', plugins_url('js/admin.js', __FILE__), 'jquery', '1.0');
            wp_enqueue_script('job-offer-admin-script');
        }
        
       /**
        * This function create used in posts 
        * or pages for see all Offers posted.
        * 
        * @access public
        */
       public function shortcodeAllOffers() {
            if (isset($_GET['id'])) {
                $this->shortcodeOffer($_GET['id']);
            } else {
                $str = '<table>';
                $str .= '<tr>';
                $str .= '<th>Type of offers</th>';
                $str .= '<th>Title</th>';
                $str .= '</tr>';

                $offers = $this->getOffers();
                foreach($offers as $offer) {
                     $str .= '<tr>';
                     $str .= '<td class="' . str_replace(' ', '_', strtolower($offer->getType()->getKey())) . '">' . $offer->getType()->getKey() . '</td>';
                     $str .= '<td><a class="job-offer-link" href="' . '&amp;id=' . $offer->getId() . '">' . $offer->getTitle() . '</a></td>';
                     $str .= '</tr>';
                }
                $str .= '</table>';
                return $str;
            }
       }
       /**
        * This function create a shortcode used for see only the information about 1 offer.
        * 
        * @access public
        * @param integer $id
        *   Identifiant of the offer.
        * @return string
        *   The content of the page.
        */
       public function shortcodeOffer($id) {
           if ($id >= 0)
                $offer = $this->getOffer($id['id']);
           $str = '<h2>' . $offer->getTitle() . '</h2>';
           $str .= '<p>' . $offer->getContent() . '</p>';
           return $str;
       }
       
       /**
        * This function create a new type of posts.
        */
       /*public function createPost() {
            $labels = array(
                'name'               => 'Offers', 'post type general name', 'your-plugin-textdomain',
                'singular_name'      => 'Offer', 'post type singular name', 'your-plugin-textdomain',
                'menu_name'          => 'Offers', 'admin menu', 'your-plugin-textdomain',
                'name_admin_bar'     => 'Offer', 'add new on admin bar', 'your-plugin-textdomain',
                'add_new'            => 'Add New', 'offer', 'your-plugin-textdomain',
                'add_new_item'       => 'Add New Offer', 'your-plugin-textdomain',
                'new_item'           => 'New Offer', 'your-plugin-textdomain',
                'edit_item'          => 'Edit Offer', 'your-plugin-textdomain', 
                'view_item'          => 'View Offer', 'your-plugin-textdomain', 
                'all_items'          => 'All Offers', 'your-plugin-textdomain',
                'search_items'       => 'Search Offers', 'your-plugin-textdomain',
                'parent_item_colon'  => 'Parent Offers:', 'your-plugin-textdomain',
                'not_found'          => 'No offers found.', 'your-plugin-textdomain',
                'not_found_in_trash' => 'No offers found in Trash.', 'your-plugin-textdomain',
            );

            $args = array(
                'labels'             => $labels,
                'description'        =>  'Description.', 'your-plugin-textdomain',
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array('slug' => 'offer'),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
                'menu_icon'          => 'dashicons-businessman',
            );
            register_post_type('offer', $args);
       }*/
    }
}

/*
 * Creation of an instance of JobOffer object if class not exists in wordpress or other plugin core.
 */
if (class_exists('JobOffer')) {
    $jobOffer = new JobOffer();
}

/*
 * If variable not empty, so, we register activation and deactivation hook
 * and add an action for see the option menu of this plugin.
 */
if (isset($jobOffer)) {
    register_activation_hook(__FILE__, array($jobOffer, 'install'));
}
