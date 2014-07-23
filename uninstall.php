<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   SuperferoCampaignWidget
 * @author    Lan Nguyen <nhlan82@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.superfero.com/
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
* @since   2.0
*/
// remove settings
delete_option( 'widget_superfero' );

