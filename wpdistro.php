<?php
/**
 * Plugin Name: WPDistro
 * Plugin URI: https://wpdistro.com
 * Description: WordPress Distribution
 * Version: 0.1.0
 * Author: David Strejc 
 * Author URI: http://davidstrejc.cz/
**/


require_once dirname( __FILE__ ) . '/wpdistro-class.php';
add_action( 'tgmpa_register', 'WPDistro_register_required_plugins' );

//if ( ! function_exists( 'PostCreator' ) ) {
//
//    function PostCreator(
//        $name      = 'AUTO POST',
//        $type      = 'post',
//        $content   = 'DUMMY CONTENT',
//        $category  = array(1,2),
//        $template  = NULL,
//        $author_id = '1',
//        $status    = 'publish'
//    ) {
//
//        define( POST_NAME, $name );
//        define( POST_TYPE, $type );
//        define( POST_CONTENT, $content );
//        define( POST_CATEGORY, $category );
//        define( POST_TEMPLATE, '' );
//        define( POST_AUTH_ID, $author_id );
//        define( POST_STATUS, $status );
//
//        if ( $type == 'page' ) {
//            $post      = get_page_by_title( POST_NAME, 'OBJECT', $type );
//            $post_id   = $post->ID;
//            $post_data = get_page( $post_id );
//            define( POST_TEMPLATE, $template );
//        } else {
//            $post      = get_page_by_title( POST_NAME, 'OBJECT', $type );
//            $post_id   = $post->ID;
//            $post_data = get_post( $post_id );
//        }
//
//        function hbt_create_post() {
//            $post_data = array(
//                'post_title'    => wp_strip_all_tags( POST_NAME ),
//                'post_content'  => POST_CONTENT,
//                'post_status'   => POST_STATUS,
//                'post_type'     => POST_TYPE,
//                'post_author'   => POST_AUTH_ID,
//                'post_category' => POST_CATEGORY,
//                'page_template' => POST_TEMPLATE
//            );
//            wp_insert_post( $post_data, $error_obj );
//        }
//
//        if ( ! isset( $post ) ) {
//            add_action( 'admin_init', 'hbt_create_post' );
//            return $error_obj;
//        }
//
//    }
//}
//
//PostCreator( 'My Lorem Ipsum', 'page', 'With a sizable serving of Dolor. This was created using Harri Bell-Thomas\'s PostCreator function.' );

function WPDistro_register_required_plugins() {
	$plugins = array(

		// This is an example of how to include a plugin from the WordPress Plugin Repository.
		array(
			'name'      => 'Sucuri Security – Auditing, Malware Scanner and Security Hardening',
			'slug'      => 'sucuri-scanner',
		),
		array(
			'name'      => '404page - your smart custom 404 error page',
			'slug'      => '404page',
		),
		array(
			'name'      => 'Asset CleanUp: Page Speed Booster',
			'slug'      => 'wp-asset-clean-up',
		),
		array(
			'name'      => 'Async JavaScript',
			'slug'      => 'async-javascript',
		),
		array(
			'name'      => 'Autoptimize',
			'slug'      => 'autoptimize',
		),
		array(
			'name'      => 'Blog2Social: Social Media Auto Post & Scheduler',
			'slug'      => 'blog2social',
		),
		array(
			'name'      => 'Code Snippets',
			'slug'      => 'code-snippets',
		),
		array(
			'name'      => 'WP Mail SMTP',
			'slug'      => 'wp-mail-smtp',
		),
		array(
			'name'      => 'WP Mail Logging',
			'slug'      => 'wp-mail-logging',
		),
		array(
			'name'      => 'Disable Comments',
			'slug'      => 'disable-comments',
		),
		array(
			'name'      => 'External Links – nofollow, noopener & new window',
			'slug'      => 'wp-external-links',
		),
		array(
			'name'      => 'Disable REST API',
			'slug'      => 'disable-wp-rest-api',
		),
		array(
			'name'      => 'Duplicate Post',
			'slug'      => 'duplicate-post',
		),
		array(
			'name'      => 'Easy Updates Manager',
			'slug'      => 'stops-core-theme-and-plugin-updates',
		),
		array(
			'name'      => 'GDPR Cookie Consent',
			'slug'      => 'cookie-law-info',
		),
		array(
			'name'      => 'EWWW Image Optimizer',
			'slug'      => 'ewww-image-optimizer',
		),
		array(
			'name'      => 'Classic Editor',
			'slug'      => 'classic-editor',
		),
		array(
			'name'      => 'Loginizer',
			'slug'      => 'loginizer',
		),
		array(
			'name'      => 'One Signal Free Push Notifications',
			'slug'      => 'onesignal-free-web-push-notifications',
		),
		array(
			'name'      => 'Permalink Manager',
			'slug'      => 'permalink-manager',
		),
		array(
			'name'      => 'Rank Math',
			'slug'      => 'seo-by-rank-math',
		),
		array(
			'name'      => 'Elementor',
			'slug'      => 'elementor',
		),
		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
		),
		array(
			'name'      => 'Lazy Load WP Rocket',
			'slug'      => 'rocket-lazy-load',
		),
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'WPDistro',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => true,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		/*
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'WPDistro' ),
			'menu_title'                      => __( 'Install Plugins', 'WPDistro' ),
			/* translators: %s: plugin name. * /
			'installing'                      => __( 'Installing Plugin: %s', 'WPDistro' ),
			/* translators: %s: plugin name. * /
			'updating'                        => __( 'Updating Plugin: %s', 'WPDistro' ),
			'oops'                            => __( 'Something went wrong with the plugin API.', 'WPDistro' ),
			'notice_can_install_required'     => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'WPDistro'
			),
			'notice_can_install_recommended'  => _n_noop(
				/* translators: 1: plugin name(s). * /
				'This theme recommends the following plugin: %1$s.',
				'This theme recommends the following plugins: %1$s.',
				'WPDistro'
			),
			'notice_ask_to_update'            => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'WPDistro'
			),
			'notice_ask_to_update_maybe'      => _n_noop(
				/* translators: 1: plugin name(s). * /
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'WPDistro'
			),
			'notice_can_activate_required'    => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'WPDistro'
			),
			'notice_can_activate_recommended' => _n_noop(
				/* translators: 1: plugin name(s). * /
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'WPDistro'
			),
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'WPDistro'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'WPDistro'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'WPDistro'
			),
			'return'                          => __( 'Return to Required Plugins Installer', 'WPDistro' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'WPDistro' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'WPDistro' ),
			/* translators: 1: plugin name. * /
			'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'WPDistro' ),
			/* translators: 1: plugin name. * /
			'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'WPDistro' ),
			/* translators: 1: dashboard link. * /
			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'WPDistro' ),
			'dismiss'                         => __( 'Dismiss this notice', 'WPDistro' ),
			'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'WPDistro' ),
			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'WPDistro' ),

			'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
		),
		*/
	);

	tgmpa( $plugins, $config );
}
