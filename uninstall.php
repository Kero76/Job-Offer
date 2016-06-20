<?php

/**
 * If WP_UNINSTALL_PLUGIN not defined,
 * we stop directly the desinstallation.
 * If this constant exists, then removed table from Database.
 */
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

/**
 * Fucntion called when you desactivate the plugin.
 * When you deactivate this plugin, you drop the table in database for save many resource.
 * 
 * @access public
 * @global object $wpdb
 *  Global Object present on WrodPress Core.
 */
function uninstall() {
    global $wpdb;
    $tableName = $wpdb->prefix . 'job_offer';
    if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName) {
        $sql = "DROP TABLE `$tableName`";
        $wpdb->query($sql);
    }
}

uninstall();