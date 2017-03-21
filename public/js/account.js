$(document).ready(function() {

    // Master Ajax setup for CSRF protection - took from Stackoverflow.
    $.ajaxSetup({
        headers:{
			'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });

    $('.response').on('change', '#Bank', function (event) {
        //event.preventDefault();

        if( $(this).val() == "cust" ){
            //console.log($(this).val());
            $('#autoSyncBank').hide();
            $('#custBank').show();
        }
        else {
            //console.log($(this).val());
            $('#custBank').hide();
            $('#autoSyncBank').show();
        }
    });

	// This is called while pressing either - GET ALL / CREATE / REFRESH ALL
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

    // This is called during STEP-1 of Create account process (MFA only)
	// User will provide username/password
    $('.response').on('submit','#register' ,function (event) {
        event.preventDefault();
        var formData = $("#register").serializeArray();
        formData = JSON.stringify(formData);
        formData = JSON.parse(formData);
        //console.log(formData);

        $.ajax({
            url: 'account/create',
            data: formData,
            success: function (data) {
                //console.log(data);
                $('.response').html(data);
            },
            error: function (data) {
                //console.log(data);
                $('body').html(data.responseText);    // This  will print error stack message on body
            },
            type: 'POST'
        });
    });

    // This is called during STEP-2 of Create account process (MFA only)
	// User will provide MFA response.
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

	// This is to read individual account details. route - user/account/getbyId/{id}
	// accessible from GET ALL screen
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

	// Hide-Unhide toggle - accessible from GET ALL screen
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

	// Handle syncAccount request for single accounts. route - user/account/sync/{id}
	// accessible from GET ALL screen
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

	// Handle SyncAll request to sync all accounts
	$('.response').on('click', '.syncAllAccount', function (event){
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

    $('.response').on('show.bs.modal', '#EditAccountForm', function (event){
        console.log("Here");
        var accountName = $('.getAccountDetails a').text();
        accountName = accountName.substr(0, accountName.indexOf('-')).trim();
        console.log(accountName);
        $('#AccountName').val(accountName);
    });

});
