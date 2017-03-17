<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" >

</head>
<body>
{!! Form::open() !!}
<div class="form-group">
    {!! Form::label('email',"Email:") !!}
    {!! Form::text('email',null, ['class' => "form-control"]) !!}
</div>

<div class="form-group">
    {!! Form::label('password',"Password:") !!}
    {!! Form::password('password',['class' => "form-control"]) !!}
</div>

{!! Form::submit('LogIn',['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}
@if($errors->any())
    <ul class="alert alert-danger">
    @foreach($errors->all() as $error)
        <li>{!! $error !!}</li>
    @endforeach
    </ul>
@endif
</body>
</html>