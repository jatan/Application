<!DOCTYPE html>
<html>
<head lang="en">
    <title>Money Tracker</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/main.css">
    @yield('css')
</head>
<body>
<div class="nav-container">
    <nav class="navbar" role="navigation">
        <div class="">
            <ul class="nav navbar-nav navbar-nav_floatNone flex__container">
                <li class="flex__item"><a href="dashboard">Home</a></li>
                <li class="flex__item"><a href="transaction">Transactions</a></li>
                <li class="flex__item"><a href="budget">Budgets</a></li>
                <!-- <li class="flex__item"><a href="bill">Bills</a></li> -->
                <li class="flex__item"><a href="account">Accounts</a></li>
                <!-- <li class="flex__item"><a href="profile">Profile</a></li> -->
                <li class="flex__item"><a href="logout">Logout</a></li>
            </ul>
        </div>
    </nav>
</div>

<div class="wrapper">
    <div class="title">
        @yield('titleText')
    </div>
    @yield('content')
</div>

<!--Javascript-->
<script src="../js/jquery-1.12.0.js"></script>
<script src="../js/bootstrap.js"></script>
<script>

  var user = {!! json_encode(auth()->user()->visible_accounts()->toArray()) !!};

</script>
@yield('js')

</body>
</html>
