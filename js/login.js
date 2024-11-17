$(document).ready(function() {
    $('#togglePassword').on('click', function() {
        const passwordField = $('#password');
        const passwordState = $('[data-password]');

        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            passwordState.attr('data-password', 'true');
            $('#togglePassword').hide();
            $('#togglePasswordSlash').show();
        } else {
            passwordField.attr('type', 'password');
            passwordState.attr('data-password', 'false');
            $('#togglePassword').show();
            $('#togglePasswordSlash').hide();
        }
    });

    $('#togglePasswordSlash').on('click', function() {
        const passwordField = $('#password');
        const passwordState = $('[data-password]');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            passwordState.attr('data-password', 'true');
            $('#togglePassword').hide();
            $('#togglePasswordSlash').show();
        } else {
            passwordField.attr('type', 'password');
            passwordState.attr('data-password', 'false');
            $('#togglePassword').show();
            $('#togglePasswordSlash').hide();
        }
    });
});
