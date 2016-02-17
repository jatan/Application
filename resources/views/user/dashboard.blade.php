<!DOCTYPE html5>
<html>

<head lang="en">
    <title>Transactions</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!--CSS-->
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../materialize/css/materialize.css"  media="screen,projection"/>

    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/trans.css">

</head>

<body>
<div class="nav-container">
    <ul class="flex__container">
        <li class="flex__item"><a href="">MT</a></li>
        <li class="flex__item"><a href="">DashBoard</a></li>
        <li class="flex__item"><a href="">Transactions</a></li>
        <li class="flex__item"><a href="">Budgets</a></li>
        <li class="flex__item"><a href="">Reports</a></li>
        <li class="flex__item"><a href="">Bills</a></li>
        <li class="flex__item"><a href="">Goal</a></li>
        <li class="flex__item"><a href="">User</a></li>
        <li class="flex__item"><a href="logout">Logout</a></li>
    </ul>
</div>

<div class="content-wrapper">
    <div class="all-accounts">


        <h1 class="my-h1">Accounts</h1>
        <div class="accounts-list">
            @foreach(Auth::user()->accounts as $account)
            <div class="well">{{$account->bank_name}}</div>
            <div class="h4 float__left">{{$account->name}}</div>
            <?php $color = "Fontred";?>
            @if($account->current_balance >= 0)
                <?php $color = "Fontgreen"; ?>
                @endif

            <div class="h4 float__right {{$color}}">{{$account->current_balance}}</div>
            <div class="float__clear"></div>
            @endforeach
            {{--<div class="h4 float__left">Savings Account</div>
            <div class="h4 float__right Fontgreen">$ 20</div>
            <div class="float__clear"></div>
            <div class="h4 float__left">Credit Card</div>
            <div class="h4 float__right Fontred">$ -3200</div>
            <div class="float__clear"></div>
            <div class="well">Chase</div>
            <div class="h4 float__left">Checking Account</div>
            <div class="h4 float__right Fontgreen">$ 700</div>
            <div class="float__clear"></div>
            <div class="well">Wells Fargo</div>
            <div class="h4 float__left">Auto Loan</div>
            <div class="h4 float__right Fontred">$ -5000</div>
            <div class="float__clear"></div>--}}
        </div>
    </div>
    <div class="transactions-main">
        <h1 class="my-h1">TRANSACTIONS</h1>
        <div class="utility-buttons">
            <a style="float:left; margin-left:10px;" class="btn-floating btn-large waves-effect waves-light teal"><i class="material-icons">add</i></a>

            <input type="text" id="search" placeholder="SEARCH..">

            <a style="float:right; margin-right:10px;" class="btn-floating btn-large waves-effect waves-light teal"><i class="material-icons">import_export</i></a>
        </div>


        <div id="table-container">
            <table id="maintable" class="table table-hover table-responsive">
                <thead class="table__header-bg-color">
                <tr>
                    <th>Date</th>
                    <th>Merchant</th>
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Account</th>
                </tr>
                </thead>
                <tbody>

                @foreach(Auth::user()->accounts as $account)
                    @foreach($account->transaction as $transaction)
                <tr>
                    <td>{{$transaction -> date}}</td>
                    <td>{{$transaction->name}}</td>
                    <td>{{$transaction -> amount}}</td>
                    <td>{{$transaction -> category_id}}</td>
                    <td>{{$account->name}}</td>
                </tr>
                    @endforeach
                @endforeach

                </tbody>
            </table>
            <div id="bottom_anchor"></div>
        </div>
    </div>
</div>

<!--Javascript-->
<script src="../js/jquery-1.12.0.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../css/stopscroll.js"></script>
</body>

</html>



{{--

    <a href="addAccount">Add Account</a><br><br>

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
--}}
