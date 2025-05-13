jQuery(document).ready(function($) {
    $('textarea').on('input', function() {
        var body = tinyMCE.activeEditor.getContent();
        $.post(ajaxurl, { action: 'etm_preview_template', body: body }, function(response) {
            $('#preview').html(response);
        });
    });
});
