<?php
defined('WINTER_MVC_PATH') OR exit('No direct script access allowed');

class Bpdf_index extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}
    
	public function index()
	{
        global $wpdb;

        // get existing widgets

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

        // Load view
        $this->load->view('bpdf/index', $this->data);
    }
    
}
