<?php
/**
 * Plugin Name: Our Team Widget for Elementor
 * Description: The Only Team Member Element you'll ever need for Elementor. 
 * Plugin URI: https://flickdevs.com/elementor/
 * Version:     1.3.1
 * Elementor tested up to: 3.19.0
 * Author:      FlickDevs
 * Author URI: https://flickdevs.com 
 * Text Domain: fd-teambox-widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


define('FD_TEAM_WIDGET_URL', plugins_url('/', __FILE__));
define('FD_TEAM_WIDGET_PATH', plugin_dir_path(__FILE__));
if ( ! function_exists( 'fdteam_is_elementor_installed' ) ) {

	function fdteam_is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
/**
 * Main Elementor Countdown Timer Pro Widget Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Fd_Team_Widget_For_Elementor {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const REQUIRED_MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const REQUIRED_MINIMUM_PHP_VERSION = '5.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_Countdown_Timer_Pro The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Countdown_Timer_Pro An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'fd_teambox_widget_textdomain' ] );
		add_action( 'plugins_loaded', [ $this, 'fd_teambox_pro_init' ] );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function fd_teambox_widget_textdomain() {
		load_plugin_textdomain( 'fd-teambox-widget' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	 
	public function fd_teambox_pro_init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'fd_teambox_admin_notice_missing' ] );
			
			return;
		}
		
		// Review_feedback
		add_action( 'admin_notices', [ $this, 'fd_teambox_admin_feedback' ] );
		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::REQUIRED_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'fd_teambox_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::REQUIRED_MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'fd_teambox_minimum_php_version' ] );
			return;
		}
		// Register Widget File
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'fd_teambox_pro_widgets' ] );		
		// Register Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'fd_teambox_styles' ) );	
		
		//Load the plugin Category 
		require_once FD_TEAM_WIDGET_PATH . 'includes/fdelementor-loader.php';				
	}
	

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function fd_teambox_admin_feedback() {
		if ( ! is_admin() ) {
			return;
		}
		else if ( ! is_user_logged_in() ) {
			return;
		}
		else if ( ! current_user_can( 'update_core' ) ) {
			return;
		}
		if ( is_plugin_active( 'our-team-widget-for-elementor/our-team-widget-for-elementor.php' ) ) {
			echo sprintf('<div class="notice notice-info is-dismissible"><p>Give Best <a href="https://wordpress.org/support/plugin/our-team-widget-for-elementor/reviews/" target="_blank">Feedback And Ratings</a> to our  <strong> Our Team Widget for Elementor Plugin </strong></p></div>','fd-teambox-widget');
		}
		
	}
	public function fd_teambox_admin_notice_missing() {

		$screen = get_current_screen();
		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return;
		}

		$plugin = 'elementor/elementor.php';

		if ( fdteam_is_elementor_installed() ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

			$message = '<p>' . __( 'Our Team Widget for Elementor not working because you need to activate the Elementor plugin.', 'fd-teambox-widget' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'fd-teambox-widget' ) ) . '</p>';
		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

			$message = '<p>' . __( 'Our Team Widget for Elementor not working because you need to install the Elemenor plugin', 'fd-teambox-widget' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'fd-teambox-widget' ) ) . '</p>';
		}

		echo '<div class="error notice notice-warning is-dismissible"><p>' . $message . '</p></div>';
	
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function fd_teambox_minimum_elementor_version() {
		
		if ( ! current_user_can( 'update_plugins' ) ) {
		return;
		}

		$file_path = 'elementor/elementor.php';

		$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
		$message = '<p>' . __( 'Our Team Widget for Elementor may be not working because you are using an old version of Elementor.', 'fd-teambox-widget' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'fd-teambox-widget' ) ) . '</p>';
		echo '<div class="error">' . $message . '</div>';
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function fd_teambox_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"Our Team Widget for Elementor required higher version for php', 'fd-teambox-widget' ),			
			'<strong>' . esc_html__( 'PHP', 'fd-teambox-widget' ) . '</strong>',
			 self::REQUIRED_MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */	
	
	public function fd_teambox_pro_widgets() {
		// Include Widget files
		require_once( __DIR__ . '/widgets/fdteam-terrago-widget.php' );		
	}
	
	/**
	 *
	 * Include countdown timer pro widget style
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function fd_teambox_styles() {
		wp_enqueue_style( 'fd-teampro-style' , FD_TEAM_WIDGET_URL .'assets/css/fd-team-box.css', true ); 
		
	}
	
	
}
Fd_Team_Widget_For_Elementor::instance();