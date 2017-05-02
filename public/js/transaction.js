
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

    var resp =    '<form id="editTransaction'+transID+'" method="post">\
                        <td><input type="text" name="Merchant" value="'+Merchant+'"></td>\
                        <td><input type="text" name="Amount" value="'+Amount+'"></td>\
                        <td><select name="Category">\
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
                            <select name="accountID">\
                                    <option value="'+BankAccount+'" selected>'+BankAccount+'</option>\
                            </select>\
                        </td>\
                        <td><button type="submit" data-transID="'+transID+'" class="btn btn-success right save-transaction">SAVE</button></td>\
                    </form>';
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
          var resp =    '<td>'+data.name+'</td>\
                        <td class="amount_red">'+data.amount+'</td>\
                        <td>'+data.category+'</td>\
                        <td>Piggy Bank - Daily Checking</td>\
                        <td>\
                            <form class="" action="transaction/delete/'+transID+'" method="get">\
                                <button class="btn btn-danger" style="float: right; margin-left: 5px;">DEL</button>\
                            </form>\
                                <button class="btn btn-success editJSOperation" data-transid="'+transID+'" style="float: right;">EDIT</button>\
                        </td>';
          object.parent().parent().html(resp);
        },
        error: function(){
            $('.response').text("An Error Occurred");
        },
        type:'GET'          // not working with type: POST
    });
});
