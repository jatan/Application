$(document).ready(function() {

    // CSRF protection
    $.ajaxSetup(
        {
            headers:
            {
                'X-CSRF-Token': $('input[name="_token"]').val()
            }
        });

    $('.testing a').click(function (event) {
        event.preventDefault();

        $.ajax({
            url: this.href,
            data: {
                format: 'json'
            },
            success: function (data) {
                console.log(data);
                $('.response').html(data);
            },
            error: function (data) {
                console.log(data);
                $('.response').text("An Error Occurred");
            },
            type: 'GET'          // not working with type: POST
        });
    });


    $('.response').on('submit','#register' ,function (event) {
        event.preventDefault();
        var formData = $("#register").serializeArray();
        formData = JSON.stringify(formData);
        formData = JSON.parse(formData);
        console.log(formData);

        $.ajax({
            url: 'account/create',
            data: formData,
            success: function (data) {
                console.log(data);
                $('.response').html(data);
            },
            error: function (data) {
                console.log(data);
                $('.response').text(data);
            },
            type: 'POST'          // not working with type: POST
        });
    });

    $('.response').on('submit','#registerStep' ,function (event) {
        event.preventDefault();
        var formData = $("#registerStep").serializeArray();
        formData = JSON.stringify(formData);
        formData = JSON.parse(formData);
        console.log(formData);

        $.ajax({
            url: 'account/create',
            data: formData,
            success: function (data) {
                console.log(data);
                $('.response').html(data);
            },
            error: function (data) {
                console.log(data);
                $('.response').text(data);
            },
            type: 'POST'          // not working with type: POST
        });
    });

});