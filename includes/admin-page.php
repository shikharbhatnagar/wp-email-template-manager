<?php

add_action('admin_menu', function() {
    add_menu_page('Email Templates', 'Email Templates', 'manage_options', 'email-templates', 'etm_render_list_page', 'dashicons-email');
    
    add_submenu_page('email-templates', 'Add New', 'Add New', 'manage_options', 'email-template-add', 'etm_render_add_page');

    // Edit page does not need to be listed in the menu
    add_submenu_page(null, 'Edit Template', 'Edit Template', 'manage_options', 'email-template-edit', 'etm_render_edit_page');
});

function etm_render_list_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_templates';

    if (isset($_GET['delete'])) {
        $wpdb->delete($table_name, ['id' => (int)$_GET['delete']]);
        echo '<div class="updated"><p>Template deleted.</p></div>';
    }

    $templates = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <div class="wrap">
            <h1>Email Templates <a href="?page=email-template-add" class="page-title-action">Add New</a></h1>

            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th>Template Name</th>
                        <th>Category</th>
                        <th>Subject</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($templates)) : ?>
                        <tr><td colspan="4">No templates found.</td></tr>
                    <?php else : ?>
                        <?php foreach ($templates as $template): ?>
                            <tr>
                                <td><strong><?php echo esc_html($template->name); ?></strong></td>
                                <td><?php echo esc_html($template->category); ?></td>
                                <td><?php echo esc_html($template->subject); ?></td>
                                <td>
                                    <a href="?page=email-template-edit&id=<?php echo $template->id; ?>">Edit</a> | 
                                    <a href="?page=email-templates&delete=<?php echo $template->id; ?>" onclick="return confirm('Delete this template?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

function etm_render_add_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etm_submit'])) {
        global $wpdb;
        $table = $wpdb->prefix . 'email_templates';
        $wpdb->insert($table, [
            'name' => sanitize_text_field($_POST['name']),
            'category' => sanitize_text_field($_POST['category']),
            'subject' => sanitize_text_field($_POST['subject']),
            'body' => wp_kses_post($_POST['body']),
        ]);
        echo '<div class="updated"><p>Template added.</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Add New Email Template</h1>
        <form method="post">
            <p><input type="text" name="name" required placeholder="Template Name" class="regular-text" /></p>
            <p><input type="text" name="category" placeholder="Category" class="regular-text" /></p>
            <p><input type="text" name="subject" required placeholder="Subject" class="regular-text" /></p>
            <p><?php wp_editor('', 'body', ['textarea_rows' => 10]); ?></p>
            <p><input type="submit" name="etm_submit" class="button button-primary" value="Save Template"></p>
        </form>
    </div>
    <?php
}

function etm_render_edit_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'email_templates';
    $id = (int)$_GET['id'];
    $template = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etm_submit'])) {
        $wpdb->update($table, [
            'name' => sanitize_text_field($_POST['name']),
            'category' => sanitize_text_field($_POST['category']),
            'subject' => sanitize_text_field($_POST['subject']),
            'body' => wp_kses_post($_POST['body']),
        ], ['id' => $id]);

        echo '<div class="updated"><p>Template updated.</p></div>';
        // reload updated data
        $template = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
    }

    ?>
    <div class="wrap">
        <h1>Email Templates <a href="?page=email-template-add" class="page-title-action">Add New</a></h1>
        
        <h1>Edit Email Template</h1>
        <form method="post">
            <p><input type="text" name="name" required value="<?php echo esc_attr($template->name); ?>" class="regular-text" /></p>
            <p><input type="text" name="category" value="<?php echo esc_attr($template->category); ?>" class="regular-text" /></p>
            <p><input type="text" name="subject" required value="<?php echo esc_attr($template->subject); ?>" class="regular-text" /></p>
            <p><?php wp_editor($template->body, 'body', ['textarea_rows' => 10]); ?></p>
            <p><input type="submit" name="etm_submit" class="button button-primary" value="Update Template"></p>
        </form>
    </div>
    <?php
}


////////////////////////////////////////////////////

// add_action('admin_menu', function() {
//     add_menu_page('Email Templates', 'Email Templates', 'manage_options', 'email-templates', 'etm_render_admin_page', 'dashicons-email');
// });


add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'toplevel_page_email-templates') return;
    wp_enqueue_script('jquery');
    wp_enqueue_script('etm-preview', plugin_dir_url(__FILE__) . 'preview.js', ['jquery'], null, true);
    wp_enqueue_editor();
});

