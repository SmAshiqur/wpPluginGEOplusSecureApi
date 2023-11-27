<?php
class Mosque_Prayer_Time {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu() {
        add_menu_page(
            'Mosque Prayer Time',
            'Mosque Prayer Time',
            'manage_options',
            'mosque-prayer-time',
            array($this, 'display_admin_page')
        );
    }

    public function display_admin_page() {
        // Display the main admin page content here
        include_once(plugin_dir_path(__FILE__) . 'class-mosque-prayer-time-admin.php');
        $admin = new Mosque_Prayer_Time_Admin();
        $admin->display_admin_page();
    }
}
