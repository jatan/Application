<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Account-Step</title>
</head>
<body>
{!! Form::open() !!}
    @if (count($pass) == 1)
        {{ $pass }}
        {!! Form::hidden("access_token",$access_token) !!}
        {!! Form::label("ans","Answers:") !!}
        {!! Form::text("ans","tomato") !!}
    @endif
@if(count($pass) != 1)
    {!! Form::hidden("access_token",$access_token) !!}
    @foreach($pass as $radio)
            {!! Form::radio('radio',$radio['mask']) !!}
            {!! Form::label('radio',$radio['mask']) !!}
        @endforeach
    @endif


    {!! Form::submit('FetchMeSomeData',['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}
</body>
</html>