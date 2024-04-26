<?php
// CSS Frontend
function mh_wsp_enqueue_styles() {
    wp_enqueue_style( 'mh-wsp-style', plugins_url( 'assets/css/mh-wsp.css', __FILE__ ), array(), '1.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'mh_wsp_enqueue_styles' );

// CSS backend
function mh_wsp_enqueue_admin_styles() {
    wp_enqueue_style('mh-wsp-admin-style', plugins_url('assets/css/mh-wsp-admin.css', __FILE__), array(), '1.0', 'all');
}
add_action('admin_enqueue_scripts', 'mh_wsp_enqueue_admin_styles');

// Function to create the shortcode and render the button in the view
function mh_wsp_button_shortcode($atts) {
    // Shortcode atts
    $atts = shortcode_atts(array(
        'id' => '', 
        'position' => 'relative',
        'icon' => 'white', // New attribute for icon
        'layout' => 'full', // New attribute for layout
    ), $atts, 'mh_wsp_button');

    if (empty($atts['id'])) {
        return ''; 
    }

    // Gets the values of custom fields based on the post ID.
    $title = get_the_title($atts['id']);
    $phone = get_post_meta($atts['id'], '_mh_wsp_account_number', true);
    $text = get_post_meta($atts['id'], '_mh_wsp_predefined_text', true);
    $text = urlencode($text); 

    // Get plugin settings
    if (empty($phone) || empty($text)) {
        return ''; 
    }

    $button_classes = 'mh-wsp';

    // Add additional class based on position attribute value
    if ($atts['position'] == 'fixed_bottom_right') {
        $button_classes .= ' mh-wsp-float-bottom-right';
    } elseif ($atts['position'] == 'fixed_bottom_left') {
        $button_classes .= ' mh-wsp-float-bottom-left';
    }

    // Determine icon based on the 'icon' attribute
    $icon = $atts['icon'];
    $icon_filename = '';
    switch ($icon) {
        case 'black':
            $icon_filename = 'mh-wsp-black.png';
            break;
        case 'dark-green':
            $icon_filename = 'mh-wsp-dark-green.png';
            break;
        case 'green':
            $icon_filename = 'mh-wsp-green.png';
            break;
        default:
            $icon_filename = 'mh-wsp-white.png'; // Default to white
            break;
    }

    // Determine layout based on the 'layout' attribute
    $layout = $atts['layout'];
    $show_layout = ($layout == 'full') ? true : false;

    // Prepare button HTML
    $button_html = '<a href="https://api.whatsapp.com/send?phone=' . esc_attr($phone) . '&text=' . esc_attr($text) . '" class="' . esc_attr($button_classes) . '" target="_blank">
                        <img src="' . esc_url( plugins_url( "assets/images/$icon_filename", __FILE__ ) ) . '" alt="' . esc_html__('Whatsapp Button', 'mh-whatsapp-button') . '"/>';
    
    // Add layout elements if needed
    if ($show_layout) {
        $button_html .= '<div class="info">
                            <span class="title">' . esc_html($title) . '</span>
                            <span class="phone">' . esc_html($phone) . '</span>    
                        </div>';
    }

    $button_html .= '</a>';

    return $button_html;
}
add_shortcode('mh_wsp_button', 'mh_wsp_button_shortcode');