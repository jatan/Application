
{!! Form::open(array('id' => "register", 'method' => "post")) !!}

{!! Form::label('bank',"Bank: ") !!}
{!! Form::text('bank',null) !!}
<br>
{!! Form::label('username',"Username: ") !!}
{!! Form::text('username',null) !!}
<br>
{!! Form::label('password',"Pin: ") !!}
{!! Form::password('password',null) !!}
<br>
{!! Form::submit('Add Account',['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}
