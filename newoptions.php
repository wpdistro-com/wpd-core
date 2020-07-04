<?php
class WPOptionsViewer{
public function __construct() {
    add_action('admin_menu', array($this, 'mynewtheme_add_admin'));
}
public function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

public function isAssoc(array $arr)
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}

public function mynewtheme_add_admin()
{
    global $themename, $shortname, $options, $spawned_options, $wpdb;

    if ($_GET['page'] == basename(__FILE__))
    {
        if ('' != $_REQUEST['formaction'])
        {
            foreach ($_REQUEST as $key => $value)
            {
                if ($key == "page" || $key == "formaction")
                {
                }
                else
                {
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

            header("Location: themes.php?page=newoptions.php&saved=true");
            die;

        }
    }
    $my_page = add_theme_page("Options viewer", "Options viewer", 'edit_themes', basename(__FILE__) , array($this, 'mynewtheme_admin'));
    add_action('load-' . $my_page, array($this, 'load_admin_js'));
    add_action( 'admin_print_styles-' . $my_page, array($this, 'enqueue_admin_styles') );
}

// This function is only called when our plugin's page loads!
public function load_admin_js()
{
    add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_js'));
}

public function enqueue_admin_js()
{
    wp_enqueue_script('namespaceformyscript', plugins_url('jsoneditor.min.js', __FILE__));
    wp_enqueue_script('optionsTracker', plugins_url('optionsTracker.js', __FILE__));

}

public function enqueue_admin_styles(){
    wp_enqueue_style('namespace', plugins_url('jsoneditor.min.css', __FILE__));
}

public function generateOption($optionName)
{
    $option = get_option($optionName, '');
    if (!is_string($option))
    {
        $option = esc_attr((string)json_encode(get_option($optionName, '')));

    }
    else
    {
        $option = esc_attr(get_option($optionName, ''));
    }
    return '<tr valign="top">
            <th scope="row">' . $optionName . '</th>
            <td>
               <input type="text" id="' . $optionName . '" style="width:50%" name="' . $optionName . '" value="' . $option . '" onclick="editor.set(JSON.parse(document.getElementById(\'' . $optionName . '\').value));lastElement=document.activeElement;jQuery(this).data(\'changed\', true);"/>
               <br/>
            </td>
          </tr>';
}

public function mynewtheme_admin()
{
global $themename, $shortname, $options, $spawned_options, $theme_name;

if ($_REQUEST['saved'])
{
    echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' settings saved for this page.</strong></p></div>';
}
if ($_REQUEST['reset_all'])
{
    echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' settings reset.</strong></p></div>';
}
?>
<div id="jsoneditor" style="width: 400px; height: 400px;position: fixed;z-index: 9999;background-color: white;bottom: 0;right: 0;">
    <button type="button" onclick=" lastElement.value = editor.getText();">Apply</button>
</div>

<script>
    // create the editor
    const container = document.getElementById("jsoneditor")
    const options = {}
    const editor = new JSONEditor(container, options)

    // set json
    const initialJson = {
        "Array": [1, 2, 3],
        "Boolean": true,
        "Null": null,
        "Number": 123,
        "Object": {"a": "b", "c": "d"},
        "String": "Hello World"
    }
    editor.set(initialJson)

    // get json
    const updatedJson = editor.get()
</script>
<div class="wrap">
    <h1>Settings for <?php
        echo $themename;
        ?></h1>
    <div class="mnt-options">
        <?php
        echo "<form id='options_form' method='post' name='form' >\n";
        global $wpdb;
        $categories = $wpdb->get_results("SELECT DISTINCT optionPlugin
                        FROM wpd_options;");
        foreach ($categories as $category)
        {
            ?><h1><?php
            echo $category->optionPlugin;
            ?></h1></br><?php
            $options = $wpdb->get_results("SELECT * FROM wpd_options WHERE optionPlugin = '" . $category->optionPlugin . "';");
            foreach ($options as $option)
            {
                echo $this->generateOption($option->option_name);
            }
        }
        ?>
        <input name="save" type="button" value="Save" class="button" onclick="submit_form(this, document.forms['form'])" />
        <input name="reset_all" type="button" value="Reset to default values" class="button" onclick="submit_form(this, document.forms['form'])" />
        <input type="hidden" name="formaction" value="default" />
        </form>
    </div>
    <?php
    }
    }
    ?>
