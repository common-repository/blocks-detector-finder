<?php
defined('WINTER_MVC_PATH') OR exit('No direct script access allowed');

class Bpdf_used_missing extends Winter_MVC_Controller {

	public function __construct(){
		parent::__construct();
	}
    
	public function index()
	{
        global $wpdb;

        // get existing blocks

        $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();

        $blocks_missing = array();

        // get all posts

        $sql = "SELECT $wpdb->posts.* FROM $wpdb->posts 
        JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id
        WHERE  $wpdb->postmeta.meta_key = '_wp_page_template' OR $wpdb->postmeta.meta_key = 'origin' 
        ORDER BY $wpdb->posts.ID
        LIMIT 1000";

        $posts = $wpdb->get_results($sql);

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
                if(isset($block_types[$widget_key]))
                {
                    $block = $block_types[$widget_key];
                }
                elseif(isset($block_types['core/'.$widget_key]))
                {
                    $block = $block_types['core/'.$widget_key];
                }

                if(empty($block))
                {
                    $blocks_missing[$widget_key] = $block ;
                }

            }
        }

        $this->data['blocks_missing'] = $blocks_missing;

        // Load view
        $this->load->view('bpdf_used_missing/index', $this->data);
    }
    
}