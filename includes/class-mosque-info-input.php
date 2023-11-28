<?php
    class Mosque_Info_Input {

        public static function initialize_settings() {
            // Register mosque info specific settings and fields
            register_setting('mosque_prayer_time_settings', 'mosque_prayer_time_mosque_name');
            register_setting('mosque_prayer_time_settings', 'mosque_prayer_time_mosque_slug');

            add_settings_field('mosque_prayer_time_mosque_name', 'Name of the Mosque', array('Mosque_Info_Input', 'text_input_callback'), 'mosque_prayer_time_settings', 'mosque_prayer_time_section', array('field_name' => 'mosque_prayer_time_mosque_name'));
            add_settings_field('mosque_prayer_time_mosque_slug', 'Slug of the Mosque', array('Mosque_Info_Input', 'text_input_callback'), 'mosque_prayer_time_settings', 'mosque_prayer_time_section', array('field_name' => 'mosque_prayer_time_mosque_slug'));
        }

        public static function text_input_callback($args) {
            $field_name = $args['field_name'];
            $field_value = get_option($field_name);
            echo '<input type="text" name="' . esc_attr($field_name) . '" value="' . esc_attr($field_value) . '">';
        }
    }
?>