function reloadCaptcha() {
    jQuery.ajax({
        dataType: 'json',
        url: '?system=reloadCaptcha',
        success: function(data) {
            jQuery('#captcha').attr('src', data);
        }
    })
}
jQuery(document).ready(function() {
    jQuery('form').on('click', '#captcha', function() {
        reloadCaptcha();
    })
});
