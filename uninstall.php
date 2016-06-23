<?php

/**
 * This function allow to uninstall properly the plugin from WordPress.
 * In fact, when you uninstall the plugin, the Database table will delete for save many resources.
 * 
 * @since Job Offer 1.0.0
 * @version 1.0.0
 */

/**
 * If WP_UNINSTALL_PLUGIN not defined,
 * we stop directly the desinstallation.
 * If this constant exists, then removed table from Database.
 */
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

/**
 * Fucntion called when you desactivate the plugin.
 * When you deactivate this plugin, you drop the table in database for save many resources.
 * 
 * @access public
 * @global object $wpdb
 *  It's a represent of the Database access create by WordPress.
 */
function uninstall() {
    global $wpdb;
    $tableName = $wpdb->prefix . 'job_offer';
    if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName) {
        $sql = "DROP TABLE `$tableName`";
        $wpdb->query($sql);
    }
}

/**
 * Called uninstall for initialize desinstallation.
 */
uninstall();