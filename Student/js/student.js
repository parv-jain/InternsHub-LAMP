document.addEventListener('DOMContentLoaded', function() {
    $('button').on('click', function() {
        $(this)
            .find('[data-fa-i2svg]')
            .toggleClass('fa-angle-down')
            .toggleClass('fa-angle-right');
    });
});