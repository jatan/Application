<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DashBoard</title>
</head>
<body>
    <h1><a href="logout">Log Out</a></h1><br><br>
    <a href="addAccount">Add Account</a><br><br>
    Hi,{{Auth::user()->email}}
    @foreach(Auth::user()->accounts as $account)
        <h1>{{$account->bank_name}}</h1>
        <h2>{{$account->account_type}}</h2>
        <h2>{{$account->current_balance}}</h2>
        <h2>{{$account->name}}</h2>

        <h2>{{$account->id}}</h2>
        @foreach($account->transaction as $transaction)
            <label>{{$transaction->name}}</label>:<label>{{$transaction -> amount}}</label><br>
            {{dump($transaction->toArray())}}

        @endforeach
    @endforeach

</body>
</html>