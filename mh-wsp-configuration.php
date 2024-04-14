<?php

add_action('admin_init', 'mh_wsp_settings_init');

function mh_wsp_settings_init() {
    register_setting('mh-wsp-settings', 'mh_wsp_settings');

    add_settings_section(
        'mh_wsp_settings_section', 
        __('Configuration', 'mh-whatsapp-button'), 
        'mh_wsp_settings_section_cb', 
        'mh-wsp-settings'
    );

    add_settings_field(
        'mh_wsp_status', 
        __('Status', 'mh-whatsapp-button'), 
        'mh_wsp_status_field_cb', 
        'mh-wsp-settings', 
        'mh_wsp_settings_section'
    );

}

// Callback for configuration section
function mh_wsp_settings_section_cb() {
    echo '<p>' . esc_html__('Set your preferences.', 'mh-whatsapp-button') . '</p>';
}

// Callback for status field
function mh_wsp_status_field_cb() {
    $options = get_option('mh_wsp_settings', ['mh_wsp_status' => 'active']); 
    ?>
    <select id="mh_wsp_status" name="mh_wsp_settings[mh_wsp_status]">
        <option value="active" <?php selected(isset($options['mh_wsp_status']) ? $options['mh_wsp_status'] : 'inactive', 'active'); ?>><?php echo esc_html__('Active', 'mh-whatsapp-button'); ?></option>
        <option value="inactive" <?php selected(isset($options['mh_wsp_status']) ? $options['mh_wsp_status'] : 'inactive', 'inactive'); ?>><?php echo esc_html__('Inactive', 'mh-whatsapp-button'); ?></option>
    </select>
    <?php
}

// Add plugin page and subpage
function mh_wsp_add_admin_menu() {
    add_menu_page(
        'MH WhatsApp Button Configuration',
        'WhatsApp Button',
        'manage_options',
        'mh-wsp-configuration',
        'mh_wsp_settings_page',
        'dashicons-whatsapp',
        6
    );

    add_submenu_page(
        'mh-wsp-configuration', 
        'Configuration', 
        'Configuration', 
        'manage_options', 
        'mh-whatsapp-settings', 
        'mh_wsp_settings_subpage' 
    );
}

add_action('admin_menu', 'mh_wsp_add_admin_menu');

function mh_wsp_settings_page() {
    echo '<h1>MH WhatsApp Button Configuration</h1>';
}

function mh_wsp_settings_subpage() { ?>
    <h1><?php echo esc_html__( 'MH Whatsapp Button', 'mh-whatsapp-button' ); ?></h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'mh-wsp-settings' ); ?>
        <?php do_settings_sections( 'mh-wsp-settings' ); ?>
        <?php submit_button(); ?>
    </form>
    <?php
}

