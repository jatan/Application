@foreach($accounts as $bank_name => $allAccounts)
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th style="text-align: center;">{{strtoupper($bank_name)}}
		      <a href="account/update/{{$bank_name}}"><button class="btn btn-warning" style="margin: 0 10px;">Update</button></a>
		      <a href="account/delete/{{$bank_name}}"><button class="btn btn-danger" style="margin: 0 10px;">Delete</button></a></th>
	    </tr>

	  </thead>
	  <tbody>

	@foreach($allAccounts as $account)
	    <tr>
	        <td class="getAccountDetails"><a href='account/getbyId/{{$account->id}}'>{{$account->name}}</a></td>
	        <td>{{($account->account_subtype == NULL) ? $account->account_type : $account->account_subtype}}</td>
	        <td>{{$account->current_balance}}</td>
	        <td>{{$account->available_balance}}</td>
		    <td><a href="account/hide/{{$account->id}}"><button class="btn btn-primary" style="margin: 0;">Hide</button></a></td>
	    </tr>
	@endforeach
	  </tbody>
	</table>
@endforeach