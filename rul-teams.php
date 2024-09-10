<?php
/*
Plugin Name: RUL Teams
Description: This plugin is developed for Rise Up Labs Interview 
Version: 1.0
Author: Amit Kollol Dey
*/

 
if ( ! defined( 'ABSPATH' ) ) {
    exit;  
}

define( 'RUL_TEAMS_VERSION', '1.0' );
define( 'RUL_TEAMS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RUL_TEAMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 
require_once RUL_TEAMS_PLUGIN_DIR . 'includes/class-team-list-table.php';
require_once RUL_TEAMS_PLUGIN_DIR . 'includes/class-team-crud.php';
require_once RUL_TEAMS_PLUGIN_DIR . 'includes/class-team-ajax-handler.php';

 
register_activation_hook( __FILE__, 'rul_teams_create_table' );

function rul_teams_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rul_teams';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        designation varchar(255) NOT NULL,
        member_id bigint(8) NOT NULL,
        email varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
 
add_action( 'admin_menu', 'rul_teams_menu' );

function rul_teams_menu() {
    add_menu_page( 'RUL Teams', 'RUL Teams', 'manage_options', 'rul-teams', 'rul_teams_page', 'dashicons-groups' );
    add_submenu_page( 'rul-teams', 'Add Team Member', 'Add Team Member', 'manage_options', 'add-team-member', 'rul_add_team_member_page' );
}
 
add_action( 'admin_enqueue_scripts', 'rul_teams_enqueue_scripts' );

function rul_teams_enqueue_scripts() {
    wp_enqueue_style( 'rul-teams-css', RUL_TEAMS_PLUGIN_URL . 'assets/css/team-styles.css', [], RUL_TEAMS_VERSION );
    wp_enqueue_script( 'rul-teams-js', RUL_TEAMS_PLUGIN_URL . 'assets/js/team-ajax.js', ['jquery'], RUL_TEAMS_VERSION, true );
    wp_localize_script( 'rul-teams-js', 'RULTeamsAjax', [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'rul_teams_nonce' ),
    ]);
}
 
function rul_teams_page() { 
    $add_new_url = admin_url('admin.php?page=add-team-member');  

    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Team Members</h1>';
    echo '<a href="' . esc_url($add_new_url) . '" class="page-title-action">Add New</a>';
    echo '<form method="post">';
    $team_table = new RUL_Team_List_Table();
    $team_table->prepare_items();
    $team_table->search_box('search', 'search_id');
    $team_table->display();
    echo '</form>';
    echo '</div>';
}

 
function rul_add_team_member_page() {
    include RUL_TEAMS_PLUGIN_DIR . 'includes/add-team-member-form.php';
}
