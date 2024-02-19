<?php
/**
 * Registered custom post type.
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 * @see  https://developer.wordpress.org/reference/functions/register_post_type/
 * @package    ISU_Directory_Client
 * @subpackage post_type
 */

/**
 * Registered custom post type.
 *
 * Uses mustache style post_type format to display profiles.
 *
 * @package    ISU_Directory_Client
 * @subpackage post_type
 * @author     Kevin Wickham <kwickham@iastate.edu>
 */
class Plugin_Name_Post_Type extends Abstract_Plugin_Name_Post_Type {

	/**
	 * Post Type name.
	 *
	 * @var string
	 */
	public static $name = 'post_type_name';

	/**
	 * The capability type.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_post_type
	 * @var string
	 */
	protected static $capability_type = 'edit_posts';

	/**
	 * @return array
	 */
	public static function get_args() {
		return array(
			'description'      => __( 'New Post Type', 'plugin-name' ),
			'public'           => false,
			'can_export'       => false,
			'show_ui'          => true,
			'show_in_menu'     => false,
			'query_var'        => false,
			'rewrite'          => false,
			'has_archive'      => false,
			'hierarchical'     => false,
			'delete_with_user' => false,
			'supports'         => array( 'title', 'author', 'revisions' ),
			'capability_type'  => static::$capability_type,
			'menu_icon'        => 'dashicons-welcome-widgets-menus',
			'labels'           => array(
				'name'                  => _x( 'Post_Types', 'Post type general name', 'plugin-name' ),
				'singular_name'         => _x( 'Post_Type', 'Post type singular name', 'plugin-name' ),
				'menu_name'             => _x( 'Post_Type', 'Admin Menu text', 'plugin-name' ),
				'name_admin_bar'        => _x( 'Post_Type', 'Add New on Toolbar', 'plugin-name' ),
				'add_new'               => __( 'Add New', 'plugin-name' ),
				'add_new_item'          => __( 'Add New Post_Type', 'plugin-name' ),
				'new_item'              => __( 'New Post_Type', 'plugin-name' ),
				'edit_item'             => __( 'Edit Post_Type', 'plugin-name' ),
				'view_item'             => __( 'View Post_Type', 'plugin-name' ),
				'all_items'             => __( 'All Post_Types', 'plugin-name' ),
				'search_items'          => __( 'Search Post_Types', 'plugin-name' ),
				'not_found'             => __( 'No post_types found.', 'plugin-name' ),
				'not_found_in_trash'    => __( 'No post_types found in Trash.', 'plugin-name' ),
				'insert_into_item'      => _x( /** @lang text */ 'Insert into post_type', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'plugin-name' ),
				'uploaded_to_this_item' => _x( 'Uploaded to this post_type', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'plugin-name' ),
				'filter_items_list'     => _x( 'Filter post_type list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'plugin-name' ),
				'items_list_navigation' => _x( 'Post_Types list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'plugin-name' ),
				'items_list'            => _x( 'Post_Types list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'plugin-name' ),
			),
		);
	}

}
