<!DOCTYPE html5>
<html>

<head lang="en">
    <title>Account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <!--CSS-->
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/Application/public/materialize/css/materialize.css"  media="screen,projection"/>

    <link rel="stylesheet" href="/Application/public/css/bootstrap.css">
    <link rel="stylesheet" href="/Application/public/css/trans.css">

</head>

<body>
<div class="nav-container">
    <ul class="flex__container">
        <li class="flex__item"><a href="../dashboard">MT</a></li>
        <li class="flex__item"><a href="../dashboard">DashBoard</a></li>
        <li class="flex__item"><a href="../account">Transactions</a></li>
        <li class="flex__item"><a href="">Budgets</a></li>
        <li class="flex__item"><a href="">Reports</a></li>
        <li class="flex__item"><a href="">Bills</a></li>
        <li class="flex__item"><a href="">Goal</a></li>
        <li class="flex__item"><a href="">User</a></li>
        <li class="flex__item"><a href="logout">Logout</a></li>
    </ul>
</div>

<div class="transactions-main">
    <h1 class="my-h1">Display</h1>
    <div class="utility-buttons">
        <a href="../{{$id}}/sync" style="float:left; margin-left:10px;" class="btn-floating btn-large waves-effect waves-light teal"><i class="material-icons">sync</i></a>


        <a style="float:right; margin-right:10px;" class="btn-floating btn-large waves-effect waves-light teal"><i class="material-icons">import_export</i></a>
    </div>


    <div id="table-container">
        <table id="maintable" class="table table-hover table-responsive">
            <thead class="table__header-bg-color">
            <tr>
                <th>Merchant</th>
                <th>Amount</th>
                <th>Location</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
                @foreach($display as $transaction)
                    @if($transaction ['pending'] == 1)
                        <tr class="white">
                    @endif
                        <td>{{$transaction['name']}}</td>
                        <td>{{$transaction['amount']}}</td>
                        <td>{{$transaction['location']}}</td>
                        <td>{{$transaction['date']}}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="bottom_anchor"></div>
    </div>
</div>

<!--Javascript-->
<script src="../js/jquery-1.12.0.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../css/stopscroll.js"></script>
</body>

</html>

