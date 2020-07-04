<?php
/**
 * Plugin Name: WPDistro Plugin Options
 * Plugin URI: https://wpdistro.com
 * Description: WordPress Distribution
 * Version: 0.1.0
 * Author: David Strejc 
 * Author URI: http://davidstrejc.cz/
**/


require_once dirname( __FILE__ ) . '/wpdistro-class.php';
include 'userOptions.php';
$a = new WPUserOptions();
add_action( 'tgmpa_register', 'WPDistro_register_required_plugins' );
function getPluginName()
{
    $e = new Exception();
    $trace = explode("\n", $e->getTraceAsString());
    // reverse array to make steps line up chronologically
    $trace = array_reverse($trace);
    array_shift($trace); // remove {main}
    array_pop($trace); // remove call to this method
    $length = count($trace);
    $results = array();
	$result = null;
    for ($i = 0; $i < $length; $i++)
    {
		$results[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
		$pieces = explode(DIRECTORY_SEPARATOR, $results[$i]);
		$pluginIndex = array_search("plugins",$pieces,true);
		if($pluginIndex != false){
		$result = $pieces[$pluginIndex + 1];
		break;
		}
    }
   
    return $result;
}

function assign_plugin($value, $option, $plugin_name){
	global $wpdb;
	$wpdb->insert("wpd_options", array(
		'option_name' => $option,
		'option_value' => (string)"Check wp_options",
		'autoload' => TRUE,
		'optionPlugin' => $plugin_name,
	));
	$wpdb->update("wpd_options", array('optionPlugin' => $plugin_name), array('option_name' => $option));
}

function check_wpd_database(){
	global $wpdb;
	$query = $wpdb->get_row("SELECT COUNT(1) FROM information_schema.tables WHERE table_schema='wordpress' AND table_name='wpd_options';");
	foreach ( $query as $tableExists) 
	{
		if($tableExists == 0){
			$wpdb->get_results("CREATE TABLE wpd_options LIKE wp_options;");
			$wpdb->get_results("ALTER TABLE wpd_options ADD COLUMN optionPlugin VARCHAR(191) NOT NULL DEFAULT '';");
		} 
	}
}

register_activation_hook(__FILE__, "onActivateWPD");

function onActivateWPD(){
    $request = getUrl('https://conf.wpdistro.cz/getPluginList');
    if(!$request['content']){
        echo "</br><h1>Nejsou zde zatím nastavení.</h1>";
        return;
    }
    $plugins = json_decode($request['content'], true);
    register_setting("WPDistro", "wpdPluginList");
    update_option("wpdPluginList", $plugins);
}

function WPDistro_register_required_plugins() {
	add_filter( 'pre_update_option', function($value, $option, $old_value) {

		//Do something before returning the new value to be saved in database
		//echo $option;
		check_wpd_database();
		assign_plugin($value, $option, getPluginName());
		return $value;
	 
	 }, 10, 3);
	 add_filter( 'register_setting_args', function($args, $defaults, $option_group, $option_name) {

		//Do something before returning the new value to be saved in database
		//echo $option;
		check_wpd_database();
		assign_plugin("Check wp_options", $option_name, getPluginName());
		return $args;
	 
	 }, 10, 4);

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
    );
    $plugins = get_option("wpdPluginList");
	tgmpa( $plugins, $config );
}
