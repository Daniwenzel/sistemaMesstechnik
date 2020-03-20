window.togglePasswordType = function () {
    var senha = $('#password');
    var confirmarSenha = $('#password-confirm');
    var iconeSenha = $('#passIcon');

    var type = senha.attr('type');

    if (type === 'password') {
        senha.attr('type', 'text');
        confirmarSenha.attr('type', 'text');
        iconeSenha.removeClass('mdi-lock').addClass('mdi-lock-open');
    }
    else {
        senha.attr('type', 'password');
        confirmarSenha.attr('type', 'password');
        iconeSenha.removeClass('mdi-lock-open').addClass('mdi-lock');
    }
};