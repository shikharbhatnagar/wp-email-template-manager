<?php

function etm_create_templates_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_templates';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        category varchar(255) DEFAULT '',
        subject varchar(255) NOT NULL,
        body longtext NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function etm_get_email_template($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_templates';
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
}

function etm_export_templates() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_templates';
    $templates = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    echo json_encode($templates);
    wp_die();
}

function etm_import_templates() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_templates';
    $data = json_decode(file_get_contents($_FILES['import_file']['tmp_name']), true);
    foreach ($data as $template) {
        $wpdb->insert($table_name, $template);
    }
    wp_redirect(admin_url('admin.php?page=email-templates&imported=1'));
    exit;
}

add_action('wp_ajax_etm_preview_template', function() {
    echo wp_kses_post($_POST['body']);
    wp_die();
});
