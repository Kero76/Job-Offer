<?php
/*
Plugin Name: Job Offer
Plugin URI: 
Description: This plugin is used for create job or traineership offer for your website.
Version: 1.0.0
Author: Nicolas GILLE
Author URI:
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
 * Includes EnumType class.
 */
require_once('classes/EnumType.class.php');

/**
 * Includes Enum class.
 */
require_once('classes/Enum.class.php');

/**
 * Includes DAO class for interact with Database.
 */
require_once('classes/DAO.class.php');

/**
 * Includes Offer class for interact with DAO object..
 */
require_once('classes/Offer.class.php');

if (!class_exists('JobOffer')) {

    /**
     * Main class of plugin.
     * 
     * This class is the main class of plugin. 
     * In fact, she centralize all functions which enable to run the plugin. 
     * 
     * @since Job Offer 1.0.1
     *  -> Replace $enum = new Enum() by $enum = Enum::get_instance()
     *  -> Replace calling DAO's method static by a simple called method.
     * @since Job Offer 1.0.0
     * @version 1.0.0
     */
    class JobOffer {
        
        /**
         *
         * @var object
         *  A DAO object for interact with Database. 
         */
        private $_dao;
        
        /**
         * Constructor used for initialized plugin functions.
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
            }
            if (function_exists('add_shortcode')) {
                add_shortcode('jo_jobs', array($this, 'shortcode_all_offers'));
                add_shortcode('jo_job', array($this, 'shortcode_offer'));
            }
            $this->_dao = DAO::get_instance();
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
         * When your activate this plugin, this function create new table in database.
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
                    `type` INT NOT NULL
                )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
            }
        }
        
        /**
         * Initialize template for interact with database.
         * 
         * This function allows to apply the functions of addition, deletion or modification
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
                    if ((trim($_POST['jo_title']) != '') && (trim($_POST['jo_content']) != '') && (trim($_POST['jo_type']) != '')) {                    
                        $id = $this->_dao->get_max_id() + 1;
                        if ($id == '') {
                            $id = 0;
                        }
                        $enum = Enum::get_instance();
                        $offerType = $enum->get_enum()[$_POST['jo_type']];
                        $data = array(
                            'id' => intval($id),
                            'title' => sanitize_text_field($_POST['jo_title']),
                            'content' => sanitize_text_field($_POST['jo_content']),
                            'type' => $offerType,
                        );
                        $offer = new Offer($data);
                        if ($this->_dao->insert($offer)) {
                            //header('Location:' . get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&addoffer=ok');
                        } else {
                            echo '<span class="error-job-offer bold-job-offer">' . __('An error occured, please contact a developper for fix it.', 'job-offer') . '</span>';
                        }
                    }
                    break;
                
                /** UPDATE SECTION **/
                case 'updateoffer' :
                    if ((trim($_POST['jo_title']) != '') && (trim($_POST['jo_content']) != '') && (trim($_POST['jo_type']) != '')) {
                        $enum = Enum::get_instance();
                        $offerType = $enum->get_enum()[$_POST['jo_type']];
                        $data = array(
                            'id' => intval($_POST['jo_id']),
                            'title' => sanitize_text_field($_POST['jo_title']),
                            'content' => ($_POST['jo_content']),
                            'type' => $offerType,
                        );
                        $offer = new Offer($data);
                        if ($this->_dao->update($offer)) {
                            //header('Location:' . get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&updateoffer=ok');
                        } else {
                            echo '<span class="error-job-offer bold-job-offer">' . __('An error occured, please contact a developper for fix it.', 'job-offer') . '</span>';
                        }
                    }
                    break;
                
                /** DELETE SECTION **/
                case 'deleteoffer' :
                    if (isset($_GET['id'])) {
                        if ($this->_dao->delete(intval($_GET['id']))) {
                            //header('Location:' . get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&deleteoffer=ok');
                        } else {
                            echo '<span class="error-job-offer bold-job-offer">' . __('An error occured, please contact a developper for fix it.', 'job-offer') . '</span>';
                        }
                    }
                    break;
                
                /** DEFAULT SECTION **/
                default :
                    break;
            }
            
            /** MESSAGE SUCCESSFUL SECTION **/
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
       
        /**
         * Return all offers from Database.
         * 
         * This function return all offers present in Database.
         * When it create the array, create a new Offer object for each entry.
         * Then, return the array with all Offers presents in Database.
         * 
         * @return array
         *   An array who composed by Offer Object.
         */
        public function get_offers() {
            $offers = $this->_dao->query();
            $enum = Enum::get_instance();
            $results = array();
            foreach($offers as $offer) {                
                $data = array(
                    'id' => $offer['id'],
                    'title' => $offer['title'],
                    'content' => $offer['content'],
                    'type' => new EnumType($enum->get_key_by_id(intval($offer['type']))),
                );
                array_push($results, new Offer($data));
            }
            return $results;
        }
        
        /**
         * Return on offer frome Database.
         * 
         * This function return a single Offer object from the database.
         * Using the id for return the good entry, generated Offer object and return it.
         * 
         * @param integer $id
         *  The id of offer which search.
         * @return object
         *  A hydrate object with the data retrieved from the database.
         */
        public function get_offer($id) {
            $offer = $this->_dao->query($id);
            $enum = Enum::get_instance();
            
            $data = array(
                'id' => $offer['id'],
                'title' => $offer['title'],
                'content' => $offer['content'],
                'type' => new EnumType($enum->get_key_by_id(intval($offer['type']))),
            );
            return new Offer($data);
        }

        /**
         * Register and enqueue stylesheet files.
         * 
         * This function register and enqueue plugin stylesheet.
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
         * This function register and enqueue plugin script.
         * It used for activate custom javascript from plugin.
         */
        public function register_scripts() {
            if (function_exists('wp_register_script') && function_exists('wp_enqueue_script')) {
                wp_register_script('job-offer-admin-script', plugins_url('js/admin.js', __FILE__), 'jquery', '1.0');
                wp_enqueue_script('jquery');
                wp_enqueue_script('job-offer-admin-script');
                
                if (function_exists('wp_localize_script')) {
                    wp_localize_script('job-offer-admin-script', 'jo-translation', array(
                        'empty-title'       => __('Title is empty.', 'job-offer'),
                        'empty-content'     => __('Content is empty.', 'job-offer'),
                        'confirm-deletion'  => __('Really want to delete this offer ? (This action is irreversible)', 'job-offer'),
                    ));
                }
            }
        }
        
        /**
         * Loaded textdomain for translations.
         * 
         * This function load textdomain for add translations.
         * It used for activate plugin internationalization.
         */
        public function load_textdomain() {
            if (function_exists('load_plugin_textdomain')) {
                load_plugin_textdomain('job-offer', false, dirname(plugin_basename(__FILE__)) . '/languages');
            }
        }
        
       /**
        * Return all offers.
        * 
        * This function is used for displaying all offers present 
        * in database.
        * It used for generate a shortcode who placed on post page.
        */
       public function shortcode_all_offers() {
            $str = '<table>';
            $str .= '<tr>';
            $str .= '<th>Type of offers</th>';
            $str .= '<th>Title</th>';
            $str .= '</tr>';

            $offers = $this->get_offers();
            foreach($offers as $offer) {
                 $str .= '<tr>';
                 $str .= '<td class="' . str_replace(' ', '_', strtolower($offer->get_type()->get_key())) . '">' . $offer->get_type()->get_key() . '</td>';
                 $str .= '<td><a class="job-offer-link" href="' . '&amp;id=' . $offer->get_id() . '">' . stripslashes($offer->get_title()) . '</a></td>';
                 $str .= '</tr>';
            }
            $str .= '</table>';
            return $str;            
       }
       
        /**
         * Return one offer from Database.
         * 
         * This function is used for displaying 1 offer.
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
                $str = '<h2>' . stripslashes($offer->get_title()) . '</h2>';
                $str .= '<p>' . stripslashes($offer->get_content()) . '</p>';
           } else {
               $str = '<p>' . __('No offers founds') . '</p>';
           }
           return $str;
       }
    }
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
}
