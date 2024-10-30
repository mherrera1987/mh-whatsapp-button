<?php
/**
     * Plugin Name: MH WhatsApp Button
     * Description: Add a floating WhatsApp button to your website.
     * Version: 1.0.0
     * Author: Marcelo Herrera
     * Author URI: https://marceloherrera.com.ar
     * Text Domain: mh-whatsapp-button
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'mh-wsp-accounts.php';
require_once plugin_dir_path( __FILE__ ) . 'mh-wsp-configuration.php';
require_once plugin_dir_path( __FILE__ ) . 'mh-wsp-button.php';