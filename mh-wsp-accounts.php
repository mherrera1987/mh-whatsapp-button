<?php
function mh_wsp_register_post_type() {
    $args = array(
        'public' => true,
        'label'  => __('WhatsApp Accounts', 'mh-whatsapp-button'),
        'supports' => array('title'),
        'show_in_rest' => true, 
        'show_ui' => true,
        'show_in_menu' => 'mh-wsp-configuration', 
    );
    register_post_type('mh-whatsapp-accounts', $args);
}

add_action('init', 'mh_wsp_register_post_type');

// Register metaboxes
add_action('add_meta_boxes', 'mh_wsp_add_meta_boxes');

function mh_wsp_add_meta_boxes() {
    add_meta_box(
        'mh_wsp_details', 
        __('WhatsApp Account Details', 'mh-whatsapp-button'),
        'mh_wsp_display_meta_box', 
        'mh-whatsapp-accounts'
    );
    add_meta_box(
        'mh_wsp_shortcode', 
        __('Shortcode', 'mh-whatsapp-button'),
        'mh_wsp_display_shortcode_meta_box', 
        'mh-whatsapp-accounts'
    );
}

// Show custom fields
function mh_wsp_display_meta_box($post) {
    // Generates a nonce for security.
    wp_nonce_field('mh_wsp_save_meta_box_data', 'mh_wsp_meta_box_nonce');

    // Retrieves current values of the fields to use them as default values.
    $account_number = get_post_meta($post->ID, '_mh_wsp_account_number', true);
    $predefined_text = get_post_meta($post->ID, '_mh_wsp_predefined_text', true);

    // Field's HTML
    echo '<label for="mh_wsp_account_number">' . __('Phone number', 'mh-whatsapp-button') . '</label>';
    echo '<input type="text" id="mh_wsp_account_number" name="mh_wsp_account_number" value="' . esc_attr($account_number) . '" class="widefat" />';
    echo '<small>' . __('Here you must enter the telephone number of the WhatsApp or WhatsApp Business account.', 'mh-whatsapp-button') . '</small>';

    echo '<label for="mh_wsp_predefined_text">' . __('Predefined text', 'mh-whatsapp-button') . '</label>';
    echo '<textarea id="mh_wsp_predefined_text" name="mh_wsp_predefined_text" class="widefat">' . esc_textarea($predefined_text) . '</textarea>';
    echo '<small>' . __('This text will be sent as the first message to start the conversation.', 'mh-whatsapp-button') . '</small>';
}

// Display the generated shortcode
function mh_wsp_display_shortcode_meta_box($post) {
    echo '<label for="mh_wsp_shortcode">' . __('Generated shortcode', 'mh-whatsapp-button') . '</label>';
    echo '<input type="text" id="mh_wsp_shortcode" name="mh_wsp_shortcode" value="[mh_wsp_button id=' . $post->ID . ']" class="widefat" disabled />';
}

//Save values for each field
add_action('save_post', 'mh_wsp_save_meta_box_data');

function mh_wsp_save_meta_box_data($post_id) {
    // Verify the nonce for security.
    if (!isset($_POST['mh_wsp_meta_box_nonce']) || !wp_verify_nonce($_POST['mh_wsp_meta_box_nonce'], 'mh_wsp_save_meta_box_data')) {
        return;
    }

    // Check if autosave is enabled and stop the process if it is.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions.
    if (isset($_POST['post_type']) && 'mh-whatsapp-accounts' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save data
    if (isset($_POST['mh_wsp_account_number'])) {
        update_post_meta($post_id, '_mh_wsp_account_number', sanitize_text_field($_POST['mh_wsp_account_number']));
    }
    if (isset($_POST['mh_wsp_predefined_text'])) {
        update_post_meta($post_id, '_mh_wsp_predefined_text', sanitize_textarea_field($_POST['mh_wsp_predefined_text']));
    }
}