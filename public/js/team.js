$(() => {
    $('#addTeam').on('click', function() {
        let form = $(this).closest('form');
        let formData = form.serializeArray();

        try {
            let sendingData = validate(formData);

            $.ajax(form.attr('action')
                .replace('%24name', sendingData['name'])
                .replace('%24strength', sendingData['strength']),
                {
                    'accepts': 'application/json',
                    'success': function () {
                        window.location.reload();
                    },
                    'error': function (errorData) {
                        if (errorData.responseJSON) {
                            if (errorData.responseJSON.message) {
                                alert(errorData.responseJSON.message);
                                return;
                            }
                        }

                        alert('something gone wrong');
                    }
                }
            )
            console.info(sendingData);
        } catch (e) {
            alert(e.message);
        }
    });
});

function validate(formData) {
    let result = {}

    for (let i in formData) {
        let item = formData[i];
        result[item['name']] = item['value'];
    }

    if (! result['name']) {
        throw new Error('name is mandatory');
    }

    if (! result['strength']) {
        throw new Error('strength is mandatory');
    }

    if (result['strength'] < 1) {
        throw new Error('strength should be more than 0');
    }

    return result;
}