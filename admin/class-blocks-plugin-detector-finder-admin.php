<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://elementinvader.com
 * @since      1.0.0
 *
 * @package    Blocks_Plugin_Detector_Finder
 * @subpackage Blocks_Plugin_Detector_Finder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blocks_Plugin_Detector_Finder
 * @subpackage Blocks_Plugin_Detector_Finder/admin
 * @author     ElementInvader <support@elementinvader.com>
 */
class Blocks_Plugin_Detector_Finder_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blocks_Plugin_Detector_Finder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blocks_Plugin_Detector_Finder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blocks-plugin-detector-finder-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blocks_Plugin_Detector_Finder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blocks_Plugin_Detector_Finder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blocks-plugin-detector-finder-admin.js', array( 'jquery' ), $this->version, false );

	}

    	/**
	 * Admin AJAX
	 */

	public function blockdetector_action()
	{
		global $Winter_MVC;

		$page = '';
		$function = '';

		if(isset($_GET['page']))$page = sanitize_text_field($_GET['page']);
		if(isset($_GET['function']))$function = sanitize_text_field($_GET['function']);

		if(isset($_POST['page']))$page = sanitize_text_field($_POST['page']);
		if(isset($_POST['function']))$function = sanitize_text_field($_POST['function']);

		$Winter_MVC = new MVC_Loader(plugin_dir_path( __FILE__ ).'../');
		$Winter_MVC->load_helper('basic');
		$Winter_MVC->load_controller($page, $function, array());
	}

    /**
	 * Admin Page Display
	 */
	public function admin_page_display() {
		global $Winter_MVC, $submenu, $menu;

		$page = '';
        $function = '';

		if(isset($_GET['page']))$page = sanitize_text_field($_GET['page']);
		if(isset($_GET['function']))$function = sanitize_text_field($_GET['function']);

		$Winter_MVC = new MVC_Loader(plugin_dir_path( __FILE__ ).'../');
		$Winter_MVC->load_helper('basic');
        $Winter_MVC->load_controller($page, $function, array());
	}

    /**
     * To add Plugin Menu and Settings page
     */
    public function plugin_menu() {

        ob_start();

        add_menu_page(__('Blocks Detector','blocks-detector-finder'), __('Blocks Detector','blocks-detector-finder'), 
            'manage_options', 'bpdf', array($this, 'admin_page_display'),
            //plugin_dir_url( __FILE__ ) . 'resources/logo.png',
            'dashicons-block-default',
            30 );
        
        add_submenu_page('bpdf', 
            __('Installed Blocks','blocks-detector-finder'), 
            __('Installed Blocks','blocks-detector-finder'),
            'manage_options', 'bpdf', array($this, 'admin_page_display'));

        add_submenu_page('bpdf', 
                        __('Blocks used','blocks-detector-finder'), 
                        __('Blocks used','blocks-detector-finder'),
                        'manage_options', 'bpdf_used_widgets', array($this, 'admin_page_display'));
                        
        add_submenu_page('bpdf', 
                        __('Blocks not in use','blocks-detector-finder'), 
                        __('Blocks not in use','blocks-detector-finder'),
                        'manage_options', 'bpdf_not_in_use', array($this, 'admin_page_display'));
        
        add_submenu_page('bpdf', 
                        __('Blocks used but deactivated','blocks-detector-finder'), 
                        __('Blocks used but deactivated','blocks-detector-finder'),
                        'manage_options', 'bpdf_used_missing', array($this, 'admin_page_display'));

    }

}
