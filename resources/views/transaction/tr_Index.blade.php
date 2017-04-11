@extends('main')

@section('css')
    <link rel="stylesheet" href="../css/trans-1.css">
@stop

@section('content')

        <div class="transactions-main">
            <div class="utility-buttons">
                <a href="#" style="float:left; margin-left:10px;" class="btn-floating btn-large waves-effect waves-light teal" data-toggle="modal" data-target="#AddTransaction">
                    <i class="material-icons">+</i>
                </a>
                <a href="#" style="float:right; margin-right:10px;" class="btn-floating btn-large waves-effect waves-light teal">
                    <i class="material-icons">I/O</i>
                </a>
            </div>
            <div id="table-container">
                <table id="maintable" class="table table-hover table-responsive">
                    <thead class="table__header-bg-color">
                    <tr>
                        <th>Merchant</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Account</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $date = ''; ?>
                    @foreach($transactions as $transaction)
	                    <?php $Carbondate = \Carbon\Carbon::parse($transaction -> date) ?>
	                    <?php $now = \Carbon\Carbon::now() ?>
                        @if($date == '')
	                        <?php $date = $Carbondate ?>
	                        <td style="float: left; margin-left: -40px; border: none; background-color: #e6e6e6;">{{($date->diffInDays($now)<1) ? "Today" : $date->toDateString()}}</td>
                        @elseif($date != $Carbondate)
	                        <td style="float: left; margin-left: -40px; border: none; background-color: #e6e6e6;">{{($Carbondate->diffInDays($now)<1) ? "Today" : $Carbondate->toDateString()}}</td>
	                        <?php $date = $Carbondate ?>
                        @endif
                        <tr style='color:{{($transaction->pending == 1) ? "blue;" : "black;"}}'>
                            <td>{{substr($transaction->name,0,60)}}</td>
                            <td class='{{($transaction->amount > 0) ? "amount_red" : "amount_green"}}'>{{($transaction->amount > 0) ? (-1.0)*($transaction -> amount) : abs($transaction -> amount)}}</td>
                            <td>{{$transaction -> category}}</td>
                            <?php $key = array_search($transaction->bank_accounts_id, array_column($accounts, 'id')) ?>
                            <td>{{$accounts[$key]['bank_name']}} - {{$accounts[$key]['name']}}</td>
                            <td>
                                <button class="btn btn-danger" style="float: right; margin-left: 5px;">DEL</button>
                                <button class="btn btn-success" style="float: right;">EDIT</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div id="bottom_anchor"></div>
            </div>
	        {{ $transactio->links() }}
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
				        {!! Form::open(array('id' => "register", 'method' => "post", 'url' => 'user/transaction/create')) !!}
				        {{ csrf_field() }}
				        <div id="">

					        <div class="form-inline">
						        <label for="TransDate" style="margin-right: 20px;">DATE</label>
						        <input id="TransDate" name="TransDate" class="form-control" type="date" style="margin-right: 10px;">
						        <label for="Merchant" style="margin-right: 20px;">MERCHANT</label>
						        <input id="Merchant" name="Merchant" class="form-control" type="text" style="margin-right: 10px;">
						        <label for="Category" style="margin-right: 20px;">CATEGORY</label>
						        <select id="Category" name="Category" class="form-control" style="margin-right: 10px;">
							        <option value="Shops" selected>Shopping</option>
							        <option value="Food and Drink">Food & Drinks</option>
							        <option value="Gan and Fuel">Gan & Fuel</option>
                                    <option value="Bank Fees">Bank Fees</option>
                                    <option value="Cash Advance">Cash Advance</option>
                                    <option value="Community">Community</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Interest">Interest</option>
                                    <option value="Payment">Payment</option>
                                    <option value="Recreation">Recreation</option>
                                    <option value="Service">Service</option>
                                    <option value="Tax">Tax</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Travel">Travel</option>
						        </select>
						        <label for="amount" style="margin-right: 20px;">Amount</label>
						        <input id="amount" name="amount" class="form-control" type="text" style="margin-right: 10px;">
                                <label for="bankAccount" style="margin-right: 20px;">BANK ACCT</label>
						        <select id="bankAccount" name="accountID" class="form-control" style="margin-right: 10px;">
							        @foreach($accounts as $bank_account)
                                        <option value="{{$bank_account['id']}}" selected>{{$bank_account['bank_name']}} - {{$bank_account['name']}}</option>
                                    @endforeach
						        </select>
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
