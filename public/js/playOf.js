$(() => {
    $('.matchStart').on('click', function () {
        $.ajax($(this).data('action'), {
            'accepts': 'application/json',
            'success': function () {
                window.location.reload();
            },
            'error': responseErrorHandle
        })
    });
});