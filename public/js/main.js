var service = {
    makeRequest: function () {
        $.ajax({
            url: "api/action",
            method: "POST",
            data: JSON.stringify({}),
            processData: false,
            contentType: 'application/json'
        }).done(function (data) {
            return;
        });
    },
};