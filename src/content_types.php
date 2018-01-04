<?php

namespace Vendi\RotaryFlyer;

class content_types {
    public static function init(){
        add_action(
        'init',
        function() {

            $labels = array(
                'name'                  => _x( 'Vendi rotary flyers', 'Post Type General Name', 'vendi-rotary-flyer' ),
                'singular_name'         => _x( 'Vendi rotary flyer', 'Post Type Singular Name', 'vendi-rotary-flyer' ),
                'menu_name'             => __( 'Vendi rotary flyers', 'vendi-rotary-flyer' ),
                'name_admin_bar'        => __( 'Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'archives'              => __( 'Vendi rotary flyer Archives', 'vendi-rotary-flyer' ),
                'attributes'            => __( 'Vendi rotary flyer Attributes', 'vendi-rotary-flyer' ),
                'parent_item_colon'     => __( 'Parent Vendi rotary flyer:', 'vendi-rotary-flyer' ),
                'all_items'             => __( 'All Vendi rotary flyers', 'vendi-rotary-flyer' ),
                'add_new_item'          => __( 'Add New Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'add_new'               => __( 'Add New', 'vendi-rotary-flyer' ),
                'new_item'              => __( 'New Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'edit_item'             => __( 'Edit Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'update_item'           => __( 'Update Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'view_item'             => __( 'View Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'view_items'            => __( 'View Vendi rotary flyers', 'vendi-rotary-flyer' ),
                'search_items'          => __( 'Search Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'not_found'             => __( 'Not found', 'vendi-rotary-flyer' ),
                'not_found_in_trash'    => __( 'Not found in Trash', 'vendi-rotary-flyer' ),
                'featured_image'        => __( 'Featured Image', 'vendi-rotary-flyer' ),
                'set_featured_image'    => __( 'Set featured image', 'vendi-rotary-flyer' ),
                'remove_featured_image' => __( 'Remove featured image', 'vendi-rotary-flyer' ),
                'use_featured_image'    => __( 'Use as featured image', 'vendi-rotary-flyer' ),
                'insert_into_item'      => __( 'Insert into Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'uploaded_to_this_item' => __( 'Uploaded to this Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'items_list'            => __( 'Vendi rotary flyers list', 'vendi-rotary-flyer' ),
                'items_list_navigation' => __( 'Vendi rotary flyers list navigation', 'vendi-rotary-flyer' ),
                'filter_items_list'     => __( 'Filter Vendi rotary flyers list', 'vendi-rotary-flyer' ),
            );
            $args = array(
                'label'                 => __( 'Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'description'           => __( 'Vendi rotary flyer', 'vendi-rotary-flyer' ),
                'labels'                => $labels,
                'supports'              => array( 'title', 'page-attributes', 'author'),
                'taxonomies'            => array( 'category', 'post_tag' ),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 5,
                'show_in_rest'          => true,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => false,
                'exclude_from_search'   => true,
                'publicly_queryable'    => true,
                'capability_type'       => 'page',
            );
        register_post_type( 'vendi-rotary-flyer', $args );

        // Register Custom Post Type

        $placeholder_labels = array(
            'name'                  => _x( 'Rotary Placeholders', 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( 'Rotary Placeholder', 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( 'Rotary Placeholders', 'text_domain' ),
            'name_admin_bar'        => __( 'Rotary Placeholder', 'text_domain' ),
            'archives'              => __( 'Rotary Placeholder Archives', 'text_domain' ),
            'attributes'            => __( 'Rotary Placeholder Attributes', 'text_domain' ),
            'parent_item_colon'     => __( 'Parent Rotary Placeholder:', 'text_domain' ),
            'all_items'             => __( 'All Rotary Placeholders', 'text_domain' ),
            'add_new_item'          => __( 'Add New Rotary Placeholder', 'text_domain' ),
            'add_new'               => __( 'Add New', 'text_domain' ),
            'new_item'              => __( 'New Rotary Placeholder', 'text_domain' ),
            'edit_item'             => __( 'Edit Rotary Placeholder', 'text_domain' ),
            'update_item'           => __( 'Update Rotary Placeholder', 'text_domain' ),
            'view_item'             => __( 'View Rotary Placeholder', 'text_domain' ),
            'view_items'            => __( 'View Rotary Placeholders', 'text_domain' ),
            'search_items'          => __( 'Search Rotary Placeholder', 'text_domain' ),
            'not_found'             => __( 'Not found', 'text_domain' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
            'featured_image'        => __( 'Featured Image', 'text_domain' ),
            'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
            'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
            'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
            'insert_into_item'      => __( 'Insert into Rotary Placeholder', 'text_domain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Rotary Placeholder', 'text_domain' ),
            'items_list'            => __( 'Rotary Placeholders list', 'text_domain' ),
            'items_list_navigation' => __( 'Rotary Placeholders list navigation', 'text_domain' ),
            'filter_items_list'     => __( 'Filter Rotary Placeholders list', 'text_domain' ),
        );
        $placeholder_args = array(
            'label'                 => __( 'Rotary Placeholder', 'text_domain' ),
            'description'           => __( 'Rotary Placeholder Description', 'text_domain' ),
            'labels'                => $placeholder_labels,
            'supports'              => array( 'title', 'editor' ),
            'taxonomies'            => array( 'category', 'post_tag' ),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        );
        register_post_type( 'Rotary Placeholder', $placeholder_args );


        }, 0 );
    }

    // Register Custom Post Type

}
