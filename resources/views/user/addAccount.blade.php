<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Account</title>
</head>
<body>

{!! Form::open() !!}

{!! Form::label('bank',"Bank: ") !!}
{!! Form::text('bank',null) !!}

{!! Form::label('username',"Username: ") !!}
{!! Form::text('username',null) !!}

{!! Form::label('password',"Pin: ") !!}
{!! Form::password('passworrd',null) !!}

{!! Form::submit('FetchMeSomeData',['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}

</body>
</html>