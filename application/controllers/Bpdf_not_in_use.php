<?php
defined('WINTER_MVC_PATH') OR exit('No direct script access allowed');

class Bpdf_not_in_use extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}
    
	public function index()
	{
        global $wpdb;

        // get existing blocks

        $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();

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
        $plugins_list = array();

        foreach($block_types as $block_type)
        {
            $category = '';
            if(isset($block_type->category))
                $category = $block_type->category;

            $title = $block_type->name;
            if(isset($block_type->title))
                $title = $block_type->title;


            $plugin_name = $plugin_slug = '';
            if(isset($block_type->render_callback) || substr($block_type->name, 0, 4) == 'core')
            {
                if(substr($block_type->name, 0, 4) == 'core')
                {
                    $plugin_name = $plugin_slug = 'core';

                    $block_type->plugin_name = $plugin_name;
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

                    $block_type->plugin_name = $plugin_name;
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

                    $block_type->plugin_name = $plugin_name;
                }
                else
                {
                    $plugin_name = $plugin_slug = '';

                    $block_type->plugin_name = $plugin_name;
                }
            }

            if(isset($existing_plugin_keys[$plugin_slug]["Name"]))
            {
                $plugin_name = $existing_plugin_keys[$plugin_slug]["Name"];
            }

            $plugins_list[$block_type->name] = $plugin_name;

            $blocks_list[$block_type->name] = $block_type;

        }

        ksort($blocks_list);

        $this->data['existing_blocks'] = $blocks_list;
        $this->data['plugins_list'] = $plugins_list;

        // get all posts

        $sql = "SELECT $wpdb->posts.* FROM $wpdb->posts 
        JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id
        WHERE  $wpdb->postmeta.meta_key = '_wp_page_template' OR $wpdb->postmeta.meta_key = 'origin' 
        ORDER BY $wpdb->posts.ID
        LIMIT 1000"; 

        $posts = $wpdb->get_results($sql);


        $posts_list = array();
        $plugins_list = array();
        $blocks_list_all = array();

        foreach ( $posts as $key => $page ) 
        {
            // parse and found all blocks in content for specific page
            $content_data = $page->post_content;

            $regExp = '/- wp:([^ ]*)/i';
            $outputArray = array();
            
            if ( preg_match_all($regExp, $content_data, $outputArray, PREG_SET_ORDER) ) {
            }

            $posts_list[$key]['post_data'] = $page;

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
                    $blocks_list_all[$widget_key] = $block;
                }
                else
                {
                    $plugins_list[$block_type->name] = $plugin_name;

                    $blocks_list_all[$block_type->name] = $block;
                }

            }                
        }

        $this->data['posts_list'] = $posts_list;
        $this->data['plugins_list'] = $plugins_list;

        // filter only blocks not in use

        $blocks_not_used = array();
        $plugins_list = array();

		foreach ( $blocks_list as  $block_key => $block )
        {
            if(!isset($blocks_list_all[$block_key]))
            {
                if (isset($block))
                {
                    $blocks_not_used[$block_key] = $block;
                }
            }
		}

        ksort($blocks_not_used);

        $this->data['blocks_not_used'] = $blocks_not_used;
        $this->data['plugins_list'] = $plugins_list;

        // Load view
        $this->load->view('bpdf_not_in_use/index', $this->data);
    }
    
}