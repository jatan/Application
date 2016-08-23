@foreach($accounts as $access_token => $allAccounts)
	<table class="table table-hover">
		<thead>
		<tr>
			<th style="text-align: center;">{{strtoupper($access_token)}}
				<a class="accountDelete" href="account/update/{{$access_token}}"><button class="btn btn-warning" style="margin: 0 10px;">Edit Login</button></a>

				<a class="accountUpdate" href="account/delete/{{$access_token}}"><button class="btn btn-danger" style="margin: 0 10px;">Delete</button></a>
				<span class="right">Last Refreshed: {{$allAccounts[0]->updated_at}}</span>
			</th>

		</tr>

		</thead>
		<tbody>

		@foreach($allAccounts as $account)
			<tr>
				<td class="getAccountDetails"><a href='account/getbyId/{{$account->id}}'>{{$account->name}}</a></td>
				<td>{{($account->account_subtype == NULL) ? $account->account_type : $account->account_subtype}}</td>
				<td>{{$account->current_balance}}</td>
				<td>{{$account->available_balance}}</td>
				<td><a class="syncAccount" href="account/sync/{{$account->id}}"><button class="btn btn-success" style="margin: 0;">Sync</button></a></td>
				<td><a class="hide_toggle" href="account/hide_toggle/{{$account->id}}"><button class="btn btn-primary" style="margin: 0;">{{($account->hidden_flag) ? 'UNHIDE' : 'HIDE'}}</button></a></td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endforeach
