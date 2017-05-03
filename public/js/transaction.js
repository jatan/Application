
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

$('tbody').on('click','button.editJSOperation', function (event) {
    console.log("Edit Clicked");
    var object = $(this);
    var transID = object.data('transid');
    var tr = $(this).parent().parent();
    var alltds = tr.children();
    var Merchant = alltds.html();
    var Amount = alltds.next().html();
    var Category = alltds.next().next().html();
    var BankAccount = alltds.next().next().next().html();
    var resp =  '<form id="editTransaction'+transID+'" method="post"></form>\
                <td><input type="text" name="Merchant" value="'+Merchant+'" form="editTransaction'+transID+'"></td>\
                <td><input type="text" name="Amount" value="'+Amount+'" form="editTransaction'+transID+'"></td>\
                <td>\
                    <select name="Category" form="editTransaction'+transID+'">\
                        <option value="Shops" selected>Shopping</option>\
                        <option value="Food and Drink">Food & Drinks</option>\
                        <option value="Gan and Fuel">Gan & Fuel</option>\
                        <option value="Bank Fees">Bank Fees</option>\
                        <option value="Cash Advance">Cash Advance</option>\
                        <option value="Community">Community</option>\
                        <option value="Healthcare">Healthcare</option>\
                        <option value="Interest">Interest</option>\
                        <option value="Payment">Payment</option>\
                        <option value="Recreation">Recreation</option>\
                        <option value="Service">Service</option>\
                        <option value="Tax">Tax</option>\
                        <option value="Transfer">Transfer</option>\
                        <option value="Travel">Travel</option>\
                    </select>\
                </td>\
                <td>\
                    <select name="accountID" form="editTransaction'+transID+'">\
                            <option value="'+BankAccount+'" selected>'+BankAccount+'</option>\
                    </select>\
                </td>\
                <td><button type="submit" data-transID="'+transID+'" class="btn btn-success right save-transaction" form="editTransaction'+transID+'"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button></td>';
    tr.html(resp);

});

$('tbody').on('click','button.save-transaction', function (event) {
    event.preventDefault();
    var object = $(this);
    var transID = object.data('transid');
    var formData = $("#editTransaction"+transID).serializeArray();
    formData = JSON.stringify(formData);
    formData = JSON.parse(formData);
    console.log(formData);

    $.ajax({
        url: 'transaction/edit/'+transID,
        data: formData,
        success: function(data){
            console.log(data);
          var resp =    '<td>'+data.name+'</td>\
                        <td class="amount_red">'+data.amount+'</td>\
                        <td>'+data.category+'</td>\
                        <td>Piggy Bank - Daily Checking</td>\
                        <td>\
                            <form class="" action="transaction/delete/'+transID+'" method="get">\
                                <button class="btn btn-danger" style="float: right; margin-left: 5px;"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>\
                            </form>\
                                <button class="btn btn-success editJSOperation" data-transid="'+transID+'" style="float: right;"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>\
                        </td>';
          object.parent().parent().html(resp);
        },
        error: function(){
            $('.response').text("An Error Occurred");
        },
        type:'GET'          // not working with type: POST
    });
});

var $rows = $('#maintable tr');

$('#search').keyup(function() {
    console.log("search triggered");
    var val = '^(?=.*\\b' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b') + ').*$',
        reg = RegExp(val, 'i'),
        text;

    $rows.show().filter(function() {
        text = $(this).text().replace(/\s+/g, ' ');
        return !reg.test(text);
    }).hide();
});
