$('.toggle-password').on('click', function () {
    var input = $(this).closest('.position-relative').find('input');
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
    } else {
        input.attr('type', 'password');
    }
});