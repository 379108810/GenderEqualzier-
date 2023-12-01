jQuery(document).ready(function($) {
    $('#check-button').on('click', function() {
        var textToCheck = $('#text-input').val();
        $.post(genderDecoderAjax.ajax_url, {
            action: 'gender_decoder',
            text_to_check: textToCheck
        }, function(response) {
            $('#result-display').html(response);
        });
    });
});
