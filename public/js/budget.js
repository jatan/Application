$(document).ready(function() {
    $('#deleteBudget').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var BudgetName = button.data('budgetname') ;// Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

        modal.find('.modal-title h4').text('Delete Budget: ' + BudgetName);
        modal.find('.modal-body input').val(BudgetName);
    });

    $('#editBudget').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var BudgetName = button.data('budgetname');// Extract info from data-* attributes
        var SetValue = button.data('setvalue');// Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

        modal.find("#cat option[value='"+ BudgetName +"']").attr('selected', true);
        modal.find("#amount").val(SetValue);
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
