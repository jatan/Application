{!! Form::open(array('id' => "register", 'method' => "post")) !!}
	<div class="form-inline">
		<label for="Bank" style="margin-right: 20px;">BANK</label>
		<select id="Bank" name="Bank" class="form-control">
			<option selected>Choose...</option>
			<option value="amex">American Express</option>
			<option value="bofa">Bank Of America</option>
			<option value="cust">Other</option>
		</select>
	</div>
	<br>
	<div id="custBank" style="display: none;">
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
		<button type="submit" class="btn btn-primary">ADD</button>
	</div>

	<div id="autoSyncBank" style="display: none;">
		<div class="form-inline">
			{!! Form::label('username',"USERNAME: ") !!}
			{!! Form::text('username',null,['class' => 'form-control']) !!}
			<br>
			<br>
			{!! Form::label('password',"PASSWORD: ") !!}
			{!! Form::password('password', array('class' => 'form-control')) !!}
			<br>
			<br>
			{!! Form::submit('LINK',['class' => 'btn btn-primary']) !!}
		</div>
	</div>
{!! Form::close() !!}
