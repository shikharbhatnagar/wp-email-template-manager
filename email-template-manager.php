<?php
/*
Plugin Name: Email Template Manager
Description: Create and manage different HTML email templates with TinyMCE, live preview, export/import, and integration with wp_mail.
Version: 1.1
Author: Shikhar Bhatnagar
*/

if (!defined('ABSPATH')) exit;

include_once plugin_dir_path(__FILE__) . 'includes/functions.php';
include_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';

// Activation hook to create DB table
register_activation_hook(__FILE__, 'etm_create_templates_table');
?>
