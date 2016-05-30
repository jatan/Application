<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Account</title>
</head>
<body>
@if(Auth::User()->accounts)
    <label><br/>Disclaimer:</label><br/>
    <label><br/>HIDE: It will stop dispaying transactions on your dashbord, we will keep updating your data related to that account</label><br/>
    <label><br/>DELETE: Deleting an account will delete all the accounts that can be accessed with username and password used for this account</label><br/>
    <ul>
        @foreach(Auth::User()->accounts as $accounts)
            <li>{{$accounts->name}}{!!($accounts->hidden_flag? '<a href="'.$accounts->id.'/unhide">UNHIDE</a>':'<a href="'.$accounts->id.'/hide">HIDE</a>')!!} <a href="{{$accounts->id}}/delete">DELETE</a> </li>
        @endforeach
    </ul>
@endif
{!! Form::open() !!}

{!! Form::label('bank',"Bank: ") !!}
{!! Form::text('bank',null) !!}

{!! Form::label('username',"Username: ") !!}
{!! Form::text('username',null) !!}

{!! Form::label('password',"Pin: ") !!}
{!! Form::password('password',null) !!}

{!! Form::submit('FetchMeSomeData',['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}

</body>
</html>