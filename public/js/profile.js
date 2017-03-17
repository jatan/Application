
$('.testing a').click(function(event){
    event.preventDefault();

    $.ajax({
        url: this.href,
        data: {
            format: 'json'
        },
        success: function(data){
            $('.response').html(data);
        },
        error: function(){
            $('.response').text("An Error Occurred");
        },
        type:'GET'          // not working with type: POST
    });
});