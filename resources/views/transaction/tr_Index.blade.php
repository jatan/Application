@extends('main')

@section('css')
    <link rel="stylesheet" href="../css/trans-1.css">
@stop

@section('content')

        <div class="transactions-main">
            <h1 class="my-h1">TRANSACTIONS</h1>
            <div class="utility-buttons">
                <a href="addAccount" style="float:left; margin-left:10px;" class="btn-floating btn-large waves-effect waves-light teal" data-toggle="modal" data-target="#AddTransaction"><i class="material-icons">add</i></a>
                <a style="float:right; margin-right:10px;" class="btn-floating btn-large waves-effect waves-light teal"><i class="material-icons">import_export</i></a>
            </div>
            <div id="table-container">
                <table id="maintable" class="table table-hover table-responsive">
                    <thead class="table__header-bg-color">
                    <tr>
                        <th>Date</th>
                        <th>Merchant</th>
                        <th>Amount</th>
                        <th>Cat 1</th>
                        <th>Cat 2</th>
                        <!--th>Account</th-->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr style='color:{{($transaction->pending == 1) ? "blue" : "black"}}'>
                            <td style="min-width: 100px;" contenteditable="true" onclick="$(this).css('color', 'red');">{{$transaction -> date}}</td>
                            <td>{{substr($transaction->name,0,60)}}</td>
                            <td class='{{($transaction->amount > 0) ? "amount_red" : "amount_green"}}'>{{($transaction->amount > 0) ? (-1.0)*($transaction -> amount) : abs($transaction -> amount)}}</td>
                            <!--{{$cat = App\Categories::find($transaction -> category_id)}}-->
                            <td>{{isset($cat['c1']) ? $cat['c1'] : $transaction -> category_id}}</td>
                            <td>{{$cat['c2']}}</td>
                            <!--td>{{$transaction->bank_accounts_id}}</td-->
                        </tr>

                    @endforeach
                    </tbody>
                </table>
                <div id="bottom_anchor"></div>
            </div>
        </div>
        <div id="AddTransaction" class="modal fade" role="dialog" style="display: none;">
	        <div class="modal-dialog">
		        <div class="modal-content">
			        <div class="modal-header">
				        <div class="modal-title">
					        <button type="button" class="btn close" data-dismiss="modal">×</button>
					        <h4>Add Transaction Details</h4>
				        </div>
			        </div>
			        <div class="modal-body">
				        {!! Form::open(array('id' => "register", 'method' => "post")) !!}
				        {{ csrf_field() }}
				        <div id="">

					        <div class="form-inline">
						        <label for="TransDate" style="margin-right: 20px;">DATE</label>
						        <input id="TransDate" name="TransDate" class="form-control" type="date" style="margin-right: 10px;">
						        <label for="Merchant" style="margin-right: 20px;">MERCHANT</label>
						        <input id="Merchant" name="Merchant" class="form-control" type="text" style="margin-right: 10px;">
						        <label for="Category" style="margin-right: 20px;">CATEGORY</label>
						        <select id="Category" name="Category" class="form-control" style="margin-right: 10px;">
							        <option value="Shop" selected>Shopping</option>
							        <option value="FoodnDrink">Food & Drinks</option>
							        <option value="AutoFuel">Gan & Fuel</option>
						        </select>
						        <label for="TransTag" style="margin-right: 20px;">TAG</label>
						        <input id="TransTag" name="TransTag" class="form-control" type="text" style="margin-right: 10px;">
					        </div>
					        <br>

					        <button type="submit" class="btn btn-success left">ADD</button>
					        <button type="button" class="btn btn-danger right" data-dismiss="modal">CANCEL</button>
				        </div>
				        {!! Form::close() !!}
			        </div>
		        </div>
	        </div>
        </div>
@stop

@section('js')
    <script src="../js/transaction.js"></script>
@stop
