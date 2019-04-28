<?php
/**
 * Trigger this file on Plugin uninstall
 *
 * @package  UFF_MOBILE_APP_MENUS
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}


//deletando posts como exemplo
global $wpdb;
$wpdb->query("delete from wp_posts where post_type = 'book'");
$wpdb->query("delete from wp_postmeta where post_id not in (select id from wp_posts)");
$wpdb->query("delete from wp_term_relationships where object not in (select id from wp_posts)");