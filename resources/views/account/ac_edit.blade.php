
{!! Form::open(array('id' => "register", 'method' => "post")) !!}
<br>
{!! Form::hidden("access_token",$access_token) !!}
{!! Form::label('username',"Username: ") !!}
{!! Form::text('username',null) !!}
<br>
{!! Form::label('password',"Password: ") !!}
{!! Form::password('password',null) !!}
<br>
{!! Form::submit('Edit',['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}
