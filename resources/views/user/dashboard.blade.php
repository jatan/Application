<!DOCTYPE html>
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
        <li class="flex__item"><a href="dashboard">Home</a></li>
        <li class="flex__item"><a href="transaction">Transactions</a></li>
        <li class="flex__item"><a href="budget">Budgets</a></li>
        <!-- <li class="flex__item"><a href="bill">Bills</a></li> -->
        <li class="flex__item"><a href="account">Accounts</a></li>
        <!-- <li class="flex__item"><a href="profile">Profile</a></li> -->
        <li class="flex__item"><a href="logout">Logout</a></li>
    </ul>
</div>

<div class="content-wrapper">
    <div class="all-accounts">


        <h1 class="my-h1">Accounts</h1>
        <div class="accounts-list">
            @foreach($accounts as $account)
                <div class="well">{{strtoupper($account->bank_name)}}</div>
                <a href="account/getbyId/{{$account->id}}"><div class="h4 float__left">{{$account->name}}</div>
                <?php $color = "Fontred";?>
                @if($account->current_balance >= 0)
                    <?php $color = "Fontgreen"; ?>
                @endif

                <div class="h4 float__right {{$color}}">{{$account->current_balance}}</div></a>
                <div class="float__clear"></div>
            @endforeach
        </div>
    </div>
    <div class="transactions-main">
        <h1 class="my-h1">TRANSACTIONS</h1>
        <div class="utility-buttons">
            <a href="addAccount" style="float:left; margin-left:10px;" class="btn-floating btn-large waves-effect waves-light teal"><i class="material-icons">add</i></a>


            <a style="float:right; margin-right:10px;" class="btn-floating btn-large waves-effect waves-light teal"><i class="material-icons">import_export</i></a>
        </div>


        <div id="table-container">
            <table id="maintable" class="table table-hover table-responsive">
                <thead class="table__header-bg-color">
                <tr>
                    <th>Date</th>
                    <th>Merchant</th>
                    <th>Amount</th>
                    <th>Cat 1</th>
                    <th>Cat 2</th>
                    <!--th>Account</th-->
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
					<tr style='color:{{($transaction->pending == 1) ? "blue" : "black"}}'>
						<td style="min-width: 100px;">{{$transaction -> date}}</td>
						<td>{{substr($transaction->name,0,60)}}</td>
						<td class='{{($transaction->amount > 0) ? "amount_red" : "amount_green"}}'>{{($transaction->amount > 0) ? (-1.0)*($transaction -> amount) : abs($transaction -> amount)}}</td>
						<!--{{$cat = App\Categories::find($transaction -> category_id)}}-->
						<td>{{isset($cat['c1']) ? $cat['c1'] : $transaction -> category_id}}</td>
						<td>{{$cat['c2']}}</td>
						<!--td>{{$transaction->bank_accounts_id}}</td-->
					</tr>

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
