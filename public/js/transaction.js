$(document).ready(function() {

    // Master Ajax setup for CSRF protection - took from Stackoverflow.
    $.ajaxSetup({
        headers:{
			'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });

    $('tbody').on('click','button.editJSOperation', function (event) {

        var object = $(this);
        var transID = object.data('transid');
        var tr = $(this).parent().parent();
        var alltds = tr.children();
        var Merchant = alltds.html();
        var Amount = alltds.next().html();
        var Category = alltds.next().next().html();
        var BankAccount = alltds.next().next().next().html();

        var obj = {                         //      declare key: value pair analysis
            'Shops': "Shopping",
            'Food and Drink': "Food & Drinks",
            'Gas and Fuel': "Gas & Fuel",
            'Bank Fees': "Bank Fees",
            'Cash Advance': "Cash Advance",
            'Community': "Community",
            'Healthcare': "Healthcare",
            'Interest': "Interest",
            'Payment': "Payment",
            'Recreation': "Recreation",
            'Service': "Service",
            'Tax': "Tax",
            'Transfer': "Transfer",
            'Travel': "Travel"
        }
        var final = [];
        var selectStart = '<select name="Category" form="editTransaction'+transID+'">';
        var selectEnd = '</select>';
        for (var key in obj) {          // Loop though JSON object
            if (obj.hasOwnProperty(key)) {
                var val = obj[key];
                if (key == Category){
                    final.push('<option value="'+key+'" selected>'+val+'</option>');
                }
                else {
                    final.push('<option value="'+key+'">'+val+'</option>');
                }
            }
        }
        var accountSelectStart = '<select name="accountID" form="editTransaction'+transID+'">';
        var banks = [];
        $.each(user, function(i, val){
            var curVal = val.bank_name+' - '+val.name;
            if (curVal == BankAccount){
                banks.push('<option value="'+'" selected>'+val.name+' - '+val.bank_name+'</option>');
            }
            else {
                banks.push('<option value="'+'">'+val.name+' - '+val.bank_name+'</option>');
            }
        });

        var resp =  '<form id="editTransaction'+transID+'" method="post"></form>\
                    <td><input type="text" name="Merchant" value="'+Merchant+'" form="editTransaction'+transID+'"></td>\
                    <td><input type="text" name="Amount" value="'+Amount+'" form="editTransaction'+transID+'"></td>\
                    <td>'+selectStart+final.join("")+selectEnd+'</td>\
                    <td>'+accountSelectStart+banks.join("")+selectEnd+'</td>\
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

    $(':file').on('change', function() {
        var file = this.files[0];

        console.log(this.value);
        if (file.size > 1048576) {
            alert('max upload size is 1MB');
            this.value = "";                // Do not attach the file.
        }
        if (file.name.substr(file.name.indexOf('.')) !== '.csv') {
            alert('Only CSV files are accepted');
            this.value = "";                // Do not attach the file.
        }
        // Also see .name, .type
    });

    $('.uploadbutton').on('click', function (event) {
        event.preventDefault();

        var obj = $("#file").prop('files')[0];
        console.log(obj);
        var form_data = new FormData();
        form_data.append('file', obj);
        console.log(form_data);

        $.ajax({
            url: 'transaction/import',
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function(data){
                // var FileHeaders = Object.keys(data[0])       // To get all keys from a Object
                data.forEach(function(element){
                    $('.result select[name="MintAccounts"]').append("<option value='"+element+"'>"+element+"</option>");
                })
                $('.result').show();

            },
            error: function(){
                $('.response').text("An Error Occurred");
            },
            type:'POST'          // not working with type: POST
        });
    });
});
