<?php
/*
Plugin Name: Superfero Courses Widget
Plugin URI: http://wordpress.org/plugins/superfero-courses/
Description: Superfero Courses Widget grabs the latest online courses from superfero.com to display on your sidebar
Author: Lan Nguyen
Version: 3.2
Author URI: http://wordpress.org/plugins/superfero-courses/
*/
/*  Copyright 2014 Lan Nguyen (email: lan.nguye at superfero.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define( 'SUPERFERO_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

require_once SUPERFERO_PLUGIN_DIR . '/settings.php';

require_once SUPERFERO_PLUGIN_DIR . '/superfero-campaign-widget.php';

add_action( 'widgets_init', create_function( '', 'return register_widget("Superfero_Campaign_Widget");' ) );
?>