let responseErrorHandle = function (errorData) {
    if (errorData.responseJSON) {
        if (errorData.responseJSON.message) {
            alert(errorData.responseJSON.message);
            return;
        }
    }

    alert('something gone wrong');
};