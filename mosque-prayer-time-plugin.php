<?php
/**
 * Plugin Name: Mosque Prayer Time Plugin
 * Plugin URI: https://wordpress.org/plugins/muslim-prayer-time/
 * Description: Accurate prayer timings for all world timezones, ensuring seamless scheduling for users worldwide.
 * Version: 1.0.0
 * Requires at least: 6.4.1
 * Requires PHP: 7.2
 * Author: Masjid Solutions
 * Author URI: https://masjidsolutions.net/
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mpt
 * Update URI: #Github-URL
 */

class Mosque_Prayer_Time_Plugin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_init', array($this, 'initialize_settings'));
    
        // Check if the plugin is activated
        if (get_option('mosque_prayer_time_activated_geo')) {
            add_action('wp_footer', array($this, 'activate_sticky_footer_widget'));
        }
    
        if (get_option('mosque_prayer_time_activated_secure_api')) {
            add_action('wp_footer', array($this, 'activate_content_secure_api'));
        }
    }

    public function add_menu_page() {
        add_menu_page('Mosque Prayer Time Admin Page', 'Mosque Prayer Time', 'manage_options', 'mosque-prayer-time-settings', array($this, 'settings_page'));
    }

    public function initialize_settings() {
        register_setting('mosque_prayer_time_settings', 'mosque_prayer_time_activated_geo');
        register_setting('mosque_prayer_time_settings', 'mosque_prayer_time_activated_secure_api');
        register_setting('mosque_prayer_time_settings', 'mosque_prayer_time_mosque_name');
        register_setting('mosque_prayer_time_settings', 'mosque_prayer_time_mosque_slug');


        add_settings_field('mosque_prayer_time_mosque_name', 'Name of the Mosque', array($this, 'text_input_callback'), 'mosque_prayer_time_settings', 'mosque_prayer_time_section', array('field_name' => 'mosque_prayer_time_mosque_name'));
        add_settings_field('mosque_prayer_time_mosque_slug', 'Slug of the Mosque', array($this, 'text_input_callback'), 'mosque_prayer_time_settings', 'mosque_prayer_time_section', array('field_name' => 'mosque_prayer_time_mosque_slug'));
    
        add_settings_section('mosque_prayer_time_section', 'Plugin Settings', array($this, 'section_callback'), 'mosque_prayer_time_settings');
    
        // First settings field for "Activate Footer Widget - GEO Location"
        add_settings_field('mosque_prayer_time_activated_geo', 'Footer Widget - GEO Location', array($this, 'activated_callback'), 'mosque_prayer_time_settings', 'mosque_prayer_time_section', array('field_name' => 'mosque_prayer_time_activated_geo'));
    
        // Second settings field for "Activate Footer Widget - SECURE API"
        add_settings_field('mosque_prayer_time_activated_secure_api', 'Footer Widget - SECURE API', array($this, 'activated_callback'), 'mosque_prayer_time_settings', 'mosque_prayer_time_section', array('field_name' => 'mosque_prayer_time_activated_secure_api'));
    }

    public function section_callback() {
        echo '<p>Activate or deactivate the plugin.</p>';
    }

    public function activated_callback($args) {
        $field_name = $args['field_name'];
        $activated = get_option($field_name);
        echo '<label><input type="checkbox" name="' . esc_attr($field_name) . '" value="1" ' . checked(1, $activated, false) . '>Activate</label>';
    }
    public function text_input_callback($args) {
        $field_name = $args['field_name'];
        $field_value = get_option($field_name);
        echo '<input type="text" name="' . esc_attr($field_name) . '" value="' . esc_attr($field_value) . '">';
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>Mosque Prayer Time Admin Page</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('mosque_prayer_time_settings');
                do_settings_sections('mosque_prayer_time_settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }


    public function activate_sticky_footer_widget() {
        echo 'Activating sticky footer widget';
        include plugin_dir_path(__FILE__) . 'includes/class-mosque-prayer-time-display.php';
        wp_enqueue_script('mosque-prayer-time-sticky-footer', plugin_dir_url(__FILE__) . 'assets/js/mosque-prayer-time-sticky-footer.js', array('jquery'), '', true);
        wp_enqueue_style('mosque-prayer-time-sticky-footer', plugin_dir_url(__FILE__) . 'assets/css/mosque-prayer-time-sticky-footer.css');
        wp_enqueue_script('prayer-time-generation-script', plugin_dir_url(__FILE__) . 'assets/js/prayer-time-generation-script.js', array('jquery'), null, true);
        wp_enqueue_script('utils-script', plugin_dir_url(__FILE__) . 'assets/js/utils-prayer-times.js', array('jquery'), null, true);
        wp_enqueue_script('prayer-times-calculator-script', plugin_dir_url(__FILE__) . 'assets/js/prayerTimesCalculator.js', array('jquery'), null, true);
        wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCRIIew-eQp2QjI5mRLFOE-qoUnl-qKC38&libraries=places', array(), null, true);
        wp_enqueue_script('moment-js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js', array(), null, true);
        wp_enqueue_script('moment-timezone-js', 'https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.31/moment-timezone-with-data.min.js', array('moment-js'), null, true);
    }
    public function activate_content_secure_api() {
        include plugin_dir_path(__FILE__) . 'includes/class-mosque-prayer-time-display-secure-api.php';
        wp_enqueue_style('secure-api-footer-widget', plugin_dir_url(__FILE__) . 'assets/css/secure-api-footer-widget.css');
        wp_enqueue_script('mosque-prayer-time-sticky-footer', plugin_dir_url(__FILE__) . 'assets/js/mosque-prayer-time-secure-api-sticky-widget.js', array('jquery'), '', true);

      
    }
}

$mosque_prayer_time_plugin = new Mosque_Prayer_Time_Plugin();

// AJAX actions for activation and deactivation
add_action('wp_ajax_activate_mosque_prayer_time_plugin', array($mosque_prayer_time_plugin, 'activate_mosque_prayer_time_plugin'));
add_action('wp_ajax_deactivate_mosque_prayer_time_plugin', array($mosque_prayer_time_plugin, 'deactivate_mosque_prayer_time_plugin'));

function activate_mosque_prayer_time_plugin() {
    update_option('mosque_prayer_time_activated', isset($_POST['mosque_prayer_time_activated']) ? 1 : 0);
    wp_die();
}

function deactivate_mosque_prayer_time_plugin() {
    delete_option('mosque_prayer_time_activated');
    wp_die();
}
