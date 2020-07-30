let requestQueue = 0;
$(() => {
    $('.matchCalculate').on('click', function () {
        $(this).hide();
        const $td = $(this).closest('td');
        $td.text('loading');
        requestQueue++;
        let reloadInCaseOfNeed = function () {
            requestQueue--;
            $td.text('done');

            if (requestQueue === 0) {
                window.location.reload();
            }
        };

        $.ajax($(this).data('action'), {
            'success': reloadInCaseOfNeed,
            'error': reloadInCaseOfNeed,
            'accepts': 'application/json'
        });
    });

    $('.calculateAll').on('click', function () {
        $(this).attr('class', 'btn btn-danger').off('click').text('loading');
        $(this).closest('div').find('.matchCalculate:visible').trigger('click');
    });
});