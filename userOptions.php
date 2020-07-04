<?php
class WPUserOptions {

    public $optionMenuName = "userOptions";
    public function __construct()
    {
        add_action('admin_init', array( $this,'callback_for_setting_up_scripts'), 10, 0);
        add_action('admin_menu', array( $this, 'mynewtheme_add_admin'), 10, 0);

    }
    public function callback_for_setting_up_scripts() {
        wp_register_style( 'namespace', plugins_url( 'jsoneditor.min.css', __FILE__ ));
        wp_enqueue_style( 'namespace' );
        wp_enqueue_script( 'namespaceformyscript', plugins_url( 'jsoneditor.min.js', __FILE__ ) );
        wp_enqueue_script( 'asdf', plugins_url( 'jsonQ.js', __FILE__ ) );
        wp_enqueue_script( 'asd', plugins_url( 'settingsUI.js', __FILE__ ) );

    }

    public function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
    public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
    public function mynewtheme_add_admin() {
        global $themename, $shortname, $options, $spawned_options, $pluginName, $wpdb;

        //if ( $_GET['page'] == basename(__FILE__) ) {
            if ( '' != $_REQUEST['formaction'] ) {
                foreach($_REQUEST as $key => $value){
                    if($key == "page" || $key == "formaction"){
                    } else {
                        if (startsWith($value, "{"))
                        {
                            if (isAssoc(get_option($key, "")))
                            {
                                $wpdb->update("wp_options", array(
                                    'option_value' => serialize(json_decode(stripslashes($value) , true))
                                ) , array(
                                    'option_name' => $key
                                ));
                            }
                            else
                            {

                                $wpdb->update("wp_options", array(
                                    'option_value' => serialize(json_decode(stripslashes($value) , false))
                                ) , array(
                                    'option_name' => $key
                                ));
                            }
                        }
                        elseif (startsWith($value, "["))
                        {
                            $wpdb->update("wp_options", array(
                                'option_value' => serialize(json_decode(stripslashes($value) , true))
                            ) , array(
                                'option_name' => $key
                            ));

                        }
                        else
                        {
                            $wpdb->update("wp_options", array(
                                'option_value' => $value
                            ) , array(
                                'option_name' => $key
                            ));
                        }
                    }
                }

                header("Location: themes.php?page=" . $this->optionMenuName . "&saved=true&plugin=" . $_GET['plugin']);
                die;

            } else {
                $pluginName = $_GET["plugin"];
            }
        //}
        add_theme_page("Plugin options", "Plugin options",
            'edit_themes', $this->optionMenuName, array($this, 'mynewtheme_admin'));
    }



    public function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function getUrl($url) {
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));
        $content = @file_get_contents($url);//, false, $context);
        // you can add some code to extract/parse response number from first header.
        // For example from "HTTP/1.1 200 OK" string.
        return array(
            'headers' => $http_response_header,
            'content' => $content
        );
    }

    public function mynewtheme_admin() {
        //var_dump(getallheaders());
        global $themename, $shortname, $options, $spawned_options, $theme_name, $pluginName;

        $pluginList = get_option("wpdPluginList");
        $object = ["plugins" => $pluginList];
        $url = 'https://conf.wpdistro.cz/getPluginConf.php';
        echo http_build_query($pluginList);
// use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($pluginList)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */ }

        var_dump($result);
        $result = json_decode($result);
        foreach ($result as $plugin) {
            if (isset($plugin->settings)) {
                foreach ($plugin->settings as $setting) {
                    //echo '<input type="hidden" name="' . $setting->name . '" value="default" />';
                    $setting->value = get_option($setting->name, '');
                }
            }
        }
        $resultingJson = addslashes(json_encode($result));
        if ($_REQUEST['saved']) {
            echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved for this page.</strong></p></div>';
        }
        if ($_REQUEST['reset_all']) {
            echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
        }
        ?>
        <script>
            let WPDistroConfInit = JSON.parse(`<?php echo $resultingJson;?> `)
        </script>
        <div class="wrap">
            <h1>Settings for <?php echo $themename; ?></h1>
            <div class="mnt-options">
                <?php
                echo "<form id='options_form' method='post' name='form' >\n";
                foreach ($result as $plugin) {
                    if(isset($plugin->settings)) {
                        foreach ($plugin->settings as $setting) {
                            echo '<input type="hidden" name="' . $setting->name . '" value="default" />';
                            //$setting->value = get_option($setting->name, '');
                        }
                    }
                }
                ?>
                <div id="actualOptions"></div>
                <input name="save" type="button" value="Save" class="button" onclick="submit_form(this, document.forms['form'])" />
                <input name="reset_all" type="button" value="Reset to default values" class="button" onclick="onButtonClick(this, document.forms['form'])" />
                <input type="hidden" name="formaction" value="default" />

                <script> function submit_form(element, form){
                        form['formaction'].value = element.name;
                        form.submit();
                    } </script>

                </form>
            </div>
        </div>
    <?php }
}
?>
