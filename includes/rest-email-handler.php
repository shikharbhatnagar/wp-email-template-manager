<?php
function etm_send_email($request) {
    $params = $request->get_json_params();

    $to       = sanitize_email($params['to']);
    $subject  = sanitize_text_field($params['subject']);
    $template = wp_kses_post($params['template']);
    $vars     = $params['variables'];

    // foreach ($vars as $key => $value) {
    //     $template = str_replace('{{' . $key . '}}', esc_html($value), $template);
    //     $subject  = str_replace('{{' . $key . '}}', esc_html($value), $subject);
    // }

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($to, $subject, $template, $headers)) {
        return new WP_REST_Response(['status' => 'sent'], 200);
    } else {
        return new WP_REST_Response(['status' => 'error'], 500);
    }
}
