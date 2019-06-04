<?php
/*
Plugin Name: EasyFinance
Plugin URI: https://github.com/Duende13/easyFinance
Description: This plugin allows to create budgets and invoices for clients and autos.
Version: 1.0
Author: Susana Ruiz Cuñado
Author URI: http://programandoconrupert.blogspot.com
License: GPL2
*/

function ea_install () {
	
	//Top menu
/* 	function sd_register_top_level_menu(){
		add_menu_page(
			'easyFinance',
			'easyFinance',
			'add_users',
			'mymenupage',
			'sd_display_top_level_menu_page',
			'',
			6
		);
	}
	add_action( 'admin_menu', 'sd_register_top_level_menu' );
	
	function sd_display_top_level_menu_page(){
	echo 'This is my page content';
	}	 */

	// Create tables
	global $wpdb;

	$table_name_clients = $wpdb->prefix . "clients"; 
	$table_name_addresses = $wpdb->prefix . "addresses"; 
	$table_name_itv = $wpdb->prefix . "itv_logs"; 
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name_clients (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		create_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name varchar(200) NOT NULL,
		surname varchar(200) NOT NULL,
		telephone1 varchar(15),
		telephone2 varchar(15),
		dni varchar(15),
		email varchar(200) NOT NULL,
		PRIMARY KEY  (id),
		KEY fk_address(address_id)
		) $charset_collate;

		CREATE TABLE $table_name_addresses (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		create_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		address1 varchar NOT NULL,
		address2 varchar,
		address3 varchar,
		city varchar,
		postcode varchar(10),
		other text,
		PRIMARY KEY  (id)
		) $charset_collate;
		
		
		CREATE TABLE $table_name_itv (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		create_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		last_date datetime DEFAULT '0000-00-00',
		frequency int,
		next_date datetime DEFAULT '0000-00-00',
		status varchar,
		PRIMARY KEY  (id),
		KEY fk_auto(auto_id)
		) $charset_collate;
	";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}
?>	