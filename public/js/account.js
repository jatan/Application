$(document).ready(function() {

    // CSRF protection
    $.ajaxSetup(
        {
            headers:
            {
                'X-CSRF-Token': $('input[name="_token"]').val()
            }
        }
    );

    $('.testing a').click(function (event) {
        event.preventDefault();

        $.ajax({
            url: this.href,
            data: {
                format: 'json'
            },
            success: function (data) {
                $('.response').html(data);
            },
            error: function (data) {
                console.log(data);
                $('.response').text("An Error Occurred");
            },
            type: 'GET'          // not working with type: POST
        });
    });

    // Create account STEP-1
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
                $('body').html(data.responseText);    // This  will print error stack message on body
            },
            type: 'POST'
        });
    });
    // Create account STEP-2
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
                $('body').html(data.responseText);    // This  will print error stack message on body
            },
            type: 'POST'
        });
    });

    $('.response').on('click', '.getAccountDetails a', function (event){
        event.preventDefault();
        console.log($(this).attr('href'));
        $.ajax({
            url: $(this).attr('href'),
            success: function (data) {
                console.log(data);
                $('.response').html(data);
            },
            error: function (data) {
                console.log(data);
                $('body').html(data.responseText);    // This  will print error stack message on body
            },
            type: 'POST'
        });
    });

    $('.response').on('click', '.hide_toggle', function (event){
        event.preventDefault();
        console.log($(this).attr('href'));
        $.ajax({
            url: $(this).attr('href'),
            success: function (data) {
                console.log(data);
                $('.response').html(data);
            },
            error: function (data) {
                console.log(data);
                $('body').html(data.responseText);    // This  will print error stack message on body
            },
            type: 'POST'
        });
    });

    $('.response').on('click', '.syncAccount', function (event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            success: function (data) {
                $('.response').html(data);
            },
            error: function (data) {
                $('body').html(data.responseText);    // This  will print error stack message on body
            },
            type: 'POST'
        });
    });
});
