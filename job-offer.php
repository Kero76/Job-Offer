<?php
/*
 * Plugin Name: Job Offer
 * Plugin URI: 
 * Description: This plugin is used for create job or traineership offer for your website.
 * Version: 0.1
 * Author: Nicolas GILLE
 * Author URI:
 * Licence: GPL2
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
         */
        public function __construct() {
            add_action('admin_menu', array($this, 'init'));
            add_action('wp_enqueue_scripts', array($this, 'enqueueStyleSheet'));
        }
        
        /**
         * This function initialize Job Offer plugin.
         */
        public function init() {
            if (function_exists('add_options_page')) {
                add_options_page('Job Offer Options', 
                                 'Job Offer', 
                                 'administrator', 
                                 __FILE__, 
                                 array($this, 'createAdminFormPage'));
            }
        }
        
        /**
         * Function called when you activate the plugin.
         * When your activate this plugin, you create a new table in database using by the plugin.
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
         * Fucntion called when you desactivate the plugin.
         * When you deactivate this plugin, you drop the table in database for save many resource.
         * @global object $wpdb
         *  Global Object present on WrodPress Core.
         */
        public function uninstall() {
            global $wpdb;
            $tableName = $wpdb->prefix . 'job_offer';
            if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName) {
                $sql = "DROP TABLE `$tableName`";
                $wpdb->query($sql);
            }
        }
        
        public function createAdminFormPage() {
            $page = $_GET['p'];
            switch($page) {
                case 'view' :
                    require_once('template-update-offer-admin-page.php');
                    break;
                
                default:
                    require_once('template-add-offer-admin-page.php');
                    break;
            }
            
            /** SECTION INSERT **/
            if ($_GET['action'] == 'addoffer') {
                if ((trim($_POST['jo_title']) != '') &&
                    (trim($_POST['jo_content']) != '') &&
                    (trim($_POST['jo_type']) != '')) {
                    
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
                        echo "Fix it, problem from WAMP (option not activated) ?";
                        //header('Location:' . get_bloginfo('url') . '/wp-admin/options-general.php?page=job-offer/job-offer.php&offer=ok');               
                    } else {
                        echo '<span class="error-job-offer bold-job-offer">An error occured, please contact a developper for fix it.</span>';     
                    }
                }
            } 
            
            /** SECTION UPDATE **/
            else if ($_GET['action'] == 'updateoffer') {
                
            }
            
            /** SECTION DELETE **/
            else if ($_GET['action'] == 'deleteoffer') {
                
            }
            
            if ($_GET['offer'] == 'addok') {
                echo '<span class="success-job-offer bold-job-offer">Your offer was correctly inserted.</span>';
            } else if ($_GET['offer'] == 'deleteok') {
                echo '<span class="success-job-offer bold-job-offer">Your offer was correctly deleted.</span>';
            }
       }
       
       /**
        * This function register and enqueue plugin stylesheet to the wordpress stylesheet queue.
        */
       public function enqueueStyleSheet() {
           wp_register_script('job-offer-style', plugins_url('css/style.css', __FILE__));
           wp_enqueue_style('job-offer-style');
       }
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
    register_deactivation_hook(__FILE__, array($jobOffer, 'uninstall'));         
}

