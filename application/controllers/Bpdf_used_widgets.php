<?php
defined('WINTER_MVC_PATH') OR exit('No direct script access allowed');

class Bpdf_used_widgets extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}

    public function index()
	{
        global $wpdb;

        // prepare post

        $this->data['show_categories'] = array();
        $this->data['show_widgets'] = array();
        $this->data['show_post_types'] = array();

        if(isset($_GET['show_categories']))
            $this->data['show_categories'] = sanitize_text_field($_GET['show_categories']);

        if(isset($_GET['show_widgets']))
            $this->data['show_widgets'] = sanitize_text_field($_GET['show_widgets']);

        if(isset($_GET['show_post_types']))
            $this->data['show_post_types'] = sanitize_text_field($_GET['show_post_types']);

        // get existing widgets

        $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
        ksort($block_types);

        // get existing plugins

        $existing_plugins = array();

        if ( function_exists( 'get_plugins' ) ) {
            $existing_plugins = get_plugins();
        }

        $existing_plugin_keys = array();
        foreach($existing_plugins as $key => $row)
        {
            $key = substr( $key, 0, strpos( $key, '/' ) );
            $existing_plugin_keys[$key] = $row;
        }

        $blocks_list = array();

        foreach($block_types as $key=>$block)
        {
            $blocks_list[$block->name] = $block;
        }

        ksort($blocks_list);

        $this->data['block_types'] = $block_types;
        $this->data['blocks_list'] = $blocks_list;

        // block categories

        $block_categories = array();

        foreach($block_types as $key=>$block_type)
        {
            $category = $block_type->category;

            if(isset($category))
            {
                $block_categories[$category] = ucfirst($category);
            }  
        }

        ksort($block_categories);

        $this->data['block_categories'] = $block_categories;

        // category colors

        $colors = array();
        $colors['widgets'] = '#20639B';
        $colors['design'] = '#20639B';
        $colors['theme'] = '#3CAEA3';
        $colors['reusable'] = '#ED553B';

        $this->data['category_colors'] = $colors;

        // get post types

        $post_types = array();
        $post_types_all = get_post_types( array(), 'objects' );
        $post_types_available = array();
        foreach($post_types_all as $post_type_obj)
        {
            $post_types[$post_type_obj->name] = $post_type_obj->label;
        }

        ksort($post_types);

        $this->data['post_types'] = $post_types;

        $sql = "SELECT DISTINCT post_type FROM $wpdb->posts 
        JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id
        WHERE  $wpdb->postmeta.meta_key = '_wp_page_template' OR $wpdb->postmeta.meta_key = 'origin' 
        ORDER BY $wpdb->posts.ID
        LIMIT 1000"; 

        $posts = $wpdb->get_results($sql);

        foreach ( $posts as $key => $page ) 
        {
            // save post type

            if(isset($post_types[$page->post_type]))
            {
                $post_types_available[$page->post_type] = $post_types[$page->post_type];
            }
            else
            {
                $post_types_available[$page->post_type] = ucfirst($page->post_type);
            }
        }

        $this->data['post_types_available'] = $post_types_available;

        // get all posts

        $where_in_post_type = '';
        if(isset($this->data['show_post_types'][0]))
        {
            $pt_join_text = array();
            foreach($this->data['show_post_types'] as $post_type_value)
            {
                $pt_join_text[] = '\''.esc_sql($post_type_value).'\'';
            }

            $where_in_post_type = ' AND '.$wpdb->posts.'.post_type IN ('.join(',', $pt_join_text).') ';
        }

        $sql = "SELECT COUNT(*) AS total_related_posts FROM $wpdb->posts 
                JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id
                WHERE $wpdb->posts.post_status = 'publish' AND ($wpdb->postmeta.meta_key = '_wp_page_template' OR $wpdb->postmeta.meta_key = 'origin') $where_in_post_type 
                ORDER BY $wpdb->posts.ID"; 

        $results = $wpdb->get_results($sql);

        $total_related_posts = 0;
        if(isset($results[0]->total_related_posts))
            $total_related_posts = $results[0]->total_related_posts;

        $current_page = 1;

        if(isset($_GET['paged']))
            $current_page = intval($_GET['paged']);

        $per_page = 100;
        $offset = $per_page*($current_page-1);

        $this->data['pagination_output'] = '';

        if(function_exists('wmvc_wp_paginate'))
            $this->data['pagination_output'] = wmvc_wp_paginate($total_related_posts, $per_page);

        $sql = "SELECT * FROM $wpdb->posts 
                            JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id
                            WHERE $wpdb->posts.post_status = 'publish' AND ($wpdb->postmeta.meta_key = '_wp_page_template' OR $wpdb->postmeta.meta_key = 'origin') $where_in_post_type 
                            ORDER BY $wpdb->posts.ID
                            LIMIT $offset,$per_page"; 

        $posts = $wpdb->get_results($sql);

        $posts_list = array();
        $plugins_list = array();

        foreach ( $posts as $key => $page ) 
        {
            // parse and found all blocks in content for specific page
            $content_data = $page->post_content;

            $regExp = '/- wp:([^ ]*)/i';
            $outputArray = array();
            
            if ( preg_match_all($regExp, $content_data, $outputArray, PREG_SET_ORDER) ) {
            }

            $posts_list[$key]['post_data'] = $page;

            $blocks_list_page = array();

            foreach($outputArray as $found)
            {
                if(!isset($found[1]))continue;

                $widget_key = $found[1];

                $block = NULL;
                if(isset($blocks_list[$widget_key]))
                {
                    $block = $blocks_list[$widget_key];
                }
                elseif(isset($blocks_list['core/'.$widget_key]))
                {
                    $block = $blocks_list['core/'.$widget_key];
                }

                // get plugins list

                $plugin_name = '';

                $block_type = $block;

                if(empty($block_type))
                {
                    $plugin_name = $plugin_slug = 'Mot Installed';
                }
                elseif(substr($block_type->name, 0, 4) == 'core')
                {
                    $plugin_name = $plugin_slug = 'core';
                }
                elseif(is_array($block_type->render_callback))
                {
                    $reflection = new \ReflectionClass( $block_type->render_callback[0] );
                    $block_path = plugin_basename( $reflection->getFileName() );
        
                    $plugin_slug = substr( $block_path, 0, strpos( $block_path, '/' ) );
        
                    $plugin_name = $plugin_slug;

                    if(isset($existing_plugin_keys[$plugin_slug]["Name"]))
                    {
                        $plugin_name = $existing_plugin_keys[$plugin_slug]["Name"];
                    }
                }
                elseif(!empty($block_type->render_callback))
                {
                    $details = new ReflectionFunction($block_type->render_callback);

                    $block_path = plugin_basename( $details->getFileName() );

                    $plugin_slug = substr( $block_path, 0, strpos( $block_path, '/' ) );
        
                    $plugin_name = $plugin_slug;

                    if(isset($existing_plugin_keys[$plugin_slug]["Name"]))
                    {
                        $plugin_name = $existing_plugin_keys[$plugin_slug]["Name"];
                    }
                }
                else
                {
                    $plugin_name = $plugin_slug = '';
                }

                if(empty($block_type))
                {
                    $plugins_list[$widget_key] = $plugin_name;
                }
                else
                {
                    $plugins_list[$block_type->name] = $plugin_name;
                }

                $blocks_list_page[$widget_key] = $block;
            } 

            ksort($blocks_list_page);

            $posts_list[$key]['blocks_list'] = $blocks_list_page;                
        }

        $this->data['posts_list'] = $posts_list;
        $this->data['plugins_list'] = $plugins_list;

        // export url generate

        $url ='admin.php';
        $qs_parameters = array();
        $qs_parameters['function'] = 'export_csv_used_widgets';
        
        $qs_part = http_build_query($qs_parameters);
        $url.='?'.$qs_part;

        $this->data['export_url'] = $url;

        // Load view
        $this->load->view('bpdf_used_widgets/index', $this->data);
    }

}
