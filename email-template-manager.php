<?php
/*
Plugin Name: Email Template Manager
Description: Create and manage different HTML email templates with TinyMCE, live preview and send email using SMTP settings.
Version: 1.1
Author: Shikhar Bhatnagar
*/

if (!defined('ABSPATH')) exit;

include_once plugin_dir_path(__FILE__) . 'includes/functions.php';
include_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';

// Activation hook to create DB table
register_activation_hook(__FILE__, 'etm_create_templates_table');

add_action('phpmailer_init', function ($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'smtp.yourserver.com';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = 587;
    $phpmailer->Username   = 'your@email.com';
    $phpmailer->Password   = 'yourpassword';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From       = 'your@email.com';
    $phpmailer->FromName   = 'Your Name or Site';
});

add_action('rest_api_init', function () {
    // error_log('Registering ETM route'); // Debug
    register_rest_route('etm/v1', '/send', [
        'methods'  => 'POST',
        'callback' => 'etm_send_email',
        'permission_callback' => function () {
	        $headers = getallheaders();

	        if (!isset($headers['Authorization'])) {
	            return new WP_Error('rest_forbidden', 'Missing Authorization header.', ['status' => 403]);
	        }

	        // Extract token from header
	        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
	            $token = $matches[1];

	            if ($token === ETM_API_KEY) {
	                return true;
	            }
	        }

	        return new WP_Error('rest_forbidden', 'Invalid token.', ['status' => 403]);
	    }
    ]);
});

function etm_send_email($request) {
    return new WP_REST_Response(['message' => 'Email sent!'], 200);
}
?>
