@foreach($accounts as $access_token => $allAccounts)
	<table class="table table-hover">
		<thead>
		<tr>
			<th style="text-align: center;">{{strtoupper($allAccounts[0]->bank_name)}}
				<button class="btn btn-warning" style="margin: 0 10px;" data-toggle="modal" data-target="#EditAccountForm">Edit</button>
				<a class="accountUpdate" href="account/delete/{{$access_token}}"><button class="btn btn-danger" style="margin: 0 10px;">Delete</button></a>
				<a class="syncAllAccount" href="account/syncAll/{{$access_token}}"><button class="btn btn-success" style="margin: 0 10px;">Sync All</button></a>
			</th>

		</tr>
		<tr>
			<td>Account Name</td>
			<td>Type</td>
			<td>Current Balance</td>
			<td>Available Balance</td>
		</tr>

		</thead>
		<tbody>

		@foreach($allAccounts as $account)
			<tr>
				<td class="getAccountDetails"><a href='account/getbyId/{{$account->id}}'>{{$account->name.'  -  '}}{{$account->number}}</a>
				<br /><span class="disabled">Last Sync: {{\Carbon\Carbon::parse($account->LastSynced_at)->diffForHumans(\Carbon\Carbon::now())}}</span></td>
				<td>{{($account->account_subtype == NULL) ? $account->account_type : $account->account_subtype}}</td>
				<td><b>{{$account->current_balance}}</b></td>
				<td>{{$account->available_balance}}</td>
				<td><a class="syncAccount" href="account/sync/{{$account->id}}"><button class="btn btn-success" style="margin: 0;">Sync</button></a></td>
				<td><a class="hide_toggle" href="account/hide_toggle/{{$account->id}}"><button class="btn btn-primary" style="margin: 0;">{{($account->hidden_flag) ? 'UNHIDE' : 'HIDE'}}</button></a></td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endforeach

<div id="EditAccountForm" class="modal fade" role="dialog" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<button type="button" class="btn close" data-dismiss="modal">×</button>
					<h4>Edit Account Details</h4>
				</div>
			</div>
			<div class="modal-body">
				{!! Form::open(array('id' => "register", 'method' => "post")) !!}
				{{ csrf_field() }}
				<div id="custBank">
					<div class="form-inline">
						<label for="BankName" style="margin-right: 20px;">BANK NAME</label>
						<input id="BankName" name="BankName" class="form-control" type="text" value="" placeholder="My Piggy Bank">
					</div>
					<br>
					<div class="form-inline">
						<label for="AccountSubType" style="margin-right: 20px;">ACCOUNT TYPE</label>
						<select id="AccountSubType" name="AccountSubType" class="form-control" style="margin-right: 10px;">
							<option value="Checking" selected>Checking</option>
							<option value="Savings">Savings</option>
							<option value="Credit">Credit Card</option>
						</select>
						<label for="AccountName" style="margin-right: 20px;">ACCOUNT NAME</label>
						<input id="AccountName" name="AccountName" class="form-control" type="text" value="" placeholder="My Savings" style="margin-right: 10px;">
						<label for="AccountNumber" style="margin-right: 20px;">ACCOUNT NUMBER</label>
						<input id="AccountNumber" name="AccountNumber" class="form-control" type="text" value="" style="margin-right: 10px;">
					</div>
					<br>
					<div class="form-inline">
						<label for="currentBalance" style="margin-right: 20px;">CURRENT BALANCE</label>
						<input id="currentBalance" name="currentBalance" class="form-control" type="text" value="">
					</div>
					<br>
					<div class="form-inline">
						<label for="AvailableBalance" style="margin-right: 20px;">AVAILABLE BALANCE</label>
						<input id="AvailableBalance" name="AvailableBalance" class="form-control" type="text" value="">
					</div>
					<br>
					<div class="form-inline">
						<label for="CreditLimit" style="margin-right: 20px;">CREDIT LIMIT</label>
						<input id="CreditLimit" name="CreditLimit" class="form-control" type="text" value="">
					</div>
					<br>
					<button type="submit" class="btn btn-success left">EDIT</button>
					<button type="button" class="btn btn-danger right" data-dismiss="modal">CANCEL</button>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>