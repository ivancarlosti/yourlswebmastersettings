<?php
/*
Plugin Name: ICC Webmaster Settings
Plugin URI: https://github.com/ivancarlosti/yourlswebmastersettings
Description: Change Logo, Title, Page Footer, add custom CSS, and customize favicon lines
Version: 1.01
Author: Ivan Carlos
Author URI: https://ivancarlos.com.br/
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Register unified config page
yourls_add_action( 'plugins_loaded', 'icc_config_add_page' );
function icc_config_add_page() {
    yourls_register_plugin_page( 'icc_logo_title_footer_favicon_config', 'Webmaster Settings', 'icc_config_do_page' );
}

// Handle and display unified config page
function icc_config_do_page() {
    if( isset( $_POST['icc_submit'] ) ) icc_config_update_option();

    // Options
    $icc_logo_imageurl = yourls_get_option( 'icc_logo_imageurl' );
    $icc_logo_imageurl_tag = yourls_get_option( 'icc_logo_imageurl_tag' );
    $icc_logo_imageurl_title = yourls_get_option( 'icc_logo_imageurl_title' );
    $icc_title_custom = yourls_get_option( 'icc_title_custom' );
    $icc_footer_text = yourls_get_option( 'icc_footer_text' );
    if ($icc_footer_text === false) $icc_footer_text = '';
    $footer_text_escaped = htmlspecialchars($icc_footer_text);

    // Custom CSS option
    $icc_custom_css = yourls_get_option( 'icc_custom_css' );
    if ($icc_custom_css === false) $icc_custom_css = '';
    $custom_css_escaped = htmlspecialchars($icc_custom_css);

    $defaults = [
        'favicon_icon32' => '',
        'favicon_icon16' => '',
        'favicon_shortcut_icon' => '',
    ];
    $favicon_options = [];
    foreach ($defaults as $key => $default_value) {
        $val = yourls_get_option($key);
        if ($val === false) $val = $default_value;
        $favicon_options[$key] = $val;
    }
    $escape_attr = function($str) {
        return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5);
    };

    echo <<<HTML
<h2>Webmaster Settings</h2>
<form method="post">
    <h3>Logo Settings</h3>
    <p><label for="icc_logo_imageurl" style="display: inline-block; width: 200px;">Image URL</label>
    <input type="text" id="icc_logo_imageurl" name="icc_logo_imageurl" value="{$escape_attr($icc_logo_imageurl)}" size="80" /></p>
    <p><label for="icc_logo_imageurl_tag" style="display: inline-block; width: 200px;">Image ALT tag</label>
    <input type="text" id="icc_logo_imageurl_tag" name="icc_logo_imageurl_tag" value="{$escape_attr($icc_logo_imageurl_tag)}" size="80" /></p>
    <p><label for="icc_logo_imageurl_title" style="display: inline-block; width: 200px;">Image Title</label>
    <input type="text" id="icc_logo_imageurl_title" name="icc_logo_imageurl_title" value="{$escape_attr($icc_logo_imageurl_title)}" size="80" /></p>

    <h3>Title Settings</h3>
    <p><label for="icc_title_custom" style="display: inline-block; width: 200px;">Custom Title</label>
    <input type="text" id="icc_title_custom" name="icc_title_custom" value="{$escape_attr($icc_title_custom)}" size="80" /></p>

    <h3>Footer Settings</h3>
    <p><label for="icc_footer_text" style="display: inline-block; width: 200px; vertical-align: top;">Footer Text (HTML allowed)</label>
    <textarea id="icc_footer_text" name="icc_footer_text" rows="5" cols="80" style="vertical-align: top;">{$footer_text_escaped}</textarea></p>

    <h3>Custom CSS</h3>
    <p><label for="icc_custom_css" style="display: inline-block; width: 200px; vertical-align: top;">Custom CSS<br>
	<span style="color:#666;font-size:90%;">(Enter raw CSS. It will be added inside a <code>&lt;style&gt;</code> tag.)</span></label>
    <textarea id="icc_custom_css" name="icc_custom_css" rows="5" cols="80" style="vertical-align: top;">{$custom_css_escaped}</textarea></p>

    <h3>Favicon Lines Settings</h3>
    <p><label for="favicon_icon32" style="display: inline-block; width: 200px;">Icon PNG 32x32 URL</label>
    <input type="text" id="favicon_icon32" name="favicon_icon32" value="{$escape_attr($favicon_options['favicon_icon32'])}" size="80" /></p>
    <p><label for="favicon_icon16" style="display: inline-block; width: 200px;">Icon PNG 16x16 URL</label>
    <input type="text" id="favicon_icon16" name="favicon_icon16" value="{$escape_attr($favicon_options['favicon_icon16'])}" size="80" /></p>
    <p><label for="favicon_shortcut_icon" style="display: inline-block; width: 200px;">Shortcut Icon (favicon.ico) URL</label>
    <input type="text" id="favicon_shortcut_icon" name="favicon_shortcut_icon" value="{$escape_attr($favicon_options['favicon_shortcut_icon'])}" size="80" /></p>

    <p><input type="submit" name="icc_submit" value="Update values" /></p>
</form>
<hr style="margin-top: 40px" />
<p><strong><a href="https://ivancarlos.me/" target="_blank">Ivan Carlos</a></strong>  &raquo; 
<a href="http://github.com/ivancarlosti/" target="_blank">GitHub</a> &raquo; 
<a href="https://buymeacoffee.com/ivancarlos" target="_blank">Buy Me a Coffee</a></p>
HTML;
}

// Update options
function icc_config_update_option() {
    $fields_logo = ['icc_logo_imageurl', 'icc_logo_imageurl_tag', 'icc_logo_imageurl_title'];
    foreach ($fields_logo as $key) {
        if (isset($_POST[$key])) yourls_update_option($key, strval($_POST[$key]));
    }
    if (isset($_POST['icc_title_custom'])) yourls_update_option('icc_title_custom', strval($_POST['icc_title_custom']));
    if (isset($_POST['icc_footer_text'])) yourls_update_option('icc_footer_text', $_POST['icc_footer_text']);
    if (isset($_POST['icc_custom_css'])) yourls_update_option('icc_custom_css', $_POST['icc_custom_css']);
    $fields_favicon = ['favicon_icon32','favicon_icon16','favicon_shortcut_icon'];
    foreach ($fields_favicon as $key) {
        if (isset($_POST[$key])) yourls_update_option($key, strval($_POST[$key]));
    }
}

// Show custom logo
yourls_add_filter( 'pre_html_logo', 'icc_hideoriginallogo' );
function icc_hideoriginallogo() {
    echo '<span id="hideYourlsLogo" style="display:none">';
}
yourls_add_filter( 'html_logo', 'icc_logo' );
function icc_logo() {
    echo '</span>';
    echo '<h1 id="yourls.logo">';
    echo '<a href="'.yourls_admin_url( 'index.php' ).'" title="'.yourls_get_option( 'icc_logo_imageurl_title' ).'"><span>';
    echo '<img src="'.yourls_get_option( 'icc_logo_imageurl' ).'" alt="'.yourls_get_option( 'icc_logo_imageurl_tag' ).'" title="'.yourls_get_option( 'icc_logo_imageurl_title' ).'" border="0" style="border: 0px; max-width: 100px;" /></a>';
    echo '</h1>';
}

// Show custom title
yourls_add_filter( 'html_title', 'icc_change_title' );
function icc_change_title( $value ) {
    $custom = yourls_get_option( 'icc_title_custom' );
    if ($custom !== '') return $custom;
    return $value;
}

// Replace footer text with custom footer from option
yourls_add_filter( 'html_footer_text', 'icc_change_footer' );
function icc_change_footer( $value ) {
    $custom_footer = yourls_get_option( 'icc_footer_text' );
    if ( !empty($custom_footer) ) return $custom_footer;
    return $value;
}

// Output favicon lines (only if set)
yourls_add_filter('shunt_html_favicon', 'icc_plugin_favicon');
function icc_plugin_favicon() {
    $opts = [
        'favicon_icon32' => yourls_get_option('favicon_icon32'),
        'favicon_icon16' => yourls_get_option('favicon_icon16'),
        'favicon_shortcut_icon' => yourls_get_option('favicon_shortcut_icon'),
    ];
    if (!empty($opts['favicon_icon32'])) {
        echo '<link rel="icon" type="image/png" sizes="32x32" href="' . htmlspecialchars($opts['favicon_icon32'], ENT_QUOTES | ENT_HTML5) . '">'."\n";
    }
    if (!empty($opts['favicon_icon16'])) {
        echo '<link rel="icon" type="image/png" sizes="16x16" href="' . htmlspecialchars($opts['favicon_icon16'], ENT_QUOTES | ENT_HTML5) . '">'."\n";
    }
    if (!empty($opts['favicon_shortcut_icon'])) {
        echo '<link rel="shortcut icon" href="' . htmlspecialchars($opts['favicon_shortcut_icon'], ENT_QUOTES | ENT_HTML5) . '">'."\n";
    }
    return true;
}

// Output custom CSS if set
yourls_add_action('html_head', 'icc_print_custom_css');
function icc_print_custom_css() {
    $css = yourls_get_option('icc_custom_css');
    if ($css !== false && trim($css) !== '') {
        echo "<style>\n" . $css . "\n</style>\n";
    }
}
