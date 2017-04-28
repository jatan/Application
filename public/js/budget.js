$(document).ready(function() {

    // Master Ajax setup for CSRF protection - took from Stackoverflow.
    $.ajaxSetup({
        headers:{
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });

    $('[id^=deleteBudget]').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var BudgetName = button.data('budgetname') ;// Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

        modal.find('.modal-title h4').text('Delete Budget: ' + BudgetName);
        modal.find('.modal-body input').val(BudgetName);
    });

    $('[id^=editBudget]').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var BudgetName = button.data('budgetname');// Extract info from data-* attributes
        var SetValue = button.data('setvalue');// Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

        modal.find("#cat option[value='"+ BudgetName +"']").attr('selected', true);
        modal.find("#amount").val(SetValue);
    });

    $('[id^=listTrans]').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var BudgetName = button.data('budgetname');// Extract info from data-* attributes
        var Month = button.data('month');// Extract info from data-* attributes
        var Currentmonth = new Date().getMonth() + 1;
        var Year = Month <= Currentmonth ? 2017 : 2016;
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

        console.log(Month);
        console.log(Year);
        console.log(BudgetName);

        $.ajax({
            url: 'transaction/fetch',
            data: {
                'month': Month,
                'year': Year,
                'budgetName': BudgetName
            },
            success: function (data) {
                for (var variable in data) {
                    if (data.hasOwnProperty(variable)) {
                        var rowData = "";
                        var rowData = rowData + '<td>'+ data[variable].date +'</td>';
                        var rowData = rowData + '<td>'+ data[variable].name +'</td>';
                        var rowData = rowData + '<td>'+ data[variable].category +'</td>';
                        var rowData = rowData + '<td>'+ data[variable].amount +'</td>';
                    }
                    modal.find('tbody').append($("<tr></tr>").html(rowData));
                }
            },
            error: function (data) {
                console.log(data);
                $('body').html(data.responseText);    // This  will print error stack message on body
            },
            type: 'GET'
        });

    });

    var divs = $('.test div');
    divs.each(function(){
        var percentage = $(this).text().trim();
        var numbersOnly = percentage.substr(0,percentage.indexOf(' '));
        if (numbersOnly >= 90){
            $(this).addClass("bg-danger");
        } else if (numbersOnly <= 40){
        //  $(this).css('background-color', 'green');
            $(this).addClass("bg-success");
        } else {
            $(this).addClass("bg-warning");
        }
    });
});
function displayData(){
    console.log("It works");
    var element = $(this.relatedTarget);
    console.log(element.val());
}
