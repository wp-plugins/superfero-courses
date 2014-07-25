<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Superfero_Campaign_Widget
 * @author    Lan Nguyen <nhlan82@gmail.com>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/superfero-courses/
 * @copyright 2014 
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
* Delete options from the database while deleting the plugin files
* Run before deleting the plugin
*
* @since   1.5
*/
// remove settings
delete_option( 'widget_superfero_campaign_widget' );

