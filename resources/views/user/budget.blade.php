<!DOCTYPE html>
<html>
    <head lang="en">
        <title>Budgets</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <!--CSS-->
        <!--Import Google Icon Font-->
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">

        <link rel="stylesheet" href="../css/bootstrap.css">
        <link rel="stylesheet" href="../css/budget.css">
    </head>
    <body>
    <div class="nav-container">
        <nav class="navbar navbar-fixed-top navbar-no-bg brown lighten-1" role="navigation">
            <div class="container">

                <div class="collapse navbar-collapse" id="top-navbar-1">
                    <ul class="nav navbar-nav navbar-right">

                        <li><a class="scroll-link reset-fontsize" href="dashboard">Home</a></li>

                        <li><a class="scroll-link reset-fontsize" href="dashboard">DashBoard</a></li>
                        <li><a class="scroll-link reset-fontsize" href="transactions">Transactions</a></li>
                        <li><a class="scroll-link reset-fontsize" href="budget">Budgets</a></li>
                        <li><a class="scroll-link reset-fontsize" href="reports">Reports</a></li>
                        <li><a class="scroll-link reset-fontsize" href="bills">Bills</a></li>
                        <li><a class="scroll-link reset-fontsize" href="goal">Goal</a></li>
                        <li><a class="scroll-link reset-fontsize" href="user">User</a></li>
                        <li><a class="scroll-link reset-fontsize" href="logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="wrapper">
        This is main wrapper class.
        <div class="months">
            <div class="row">
                <div class="col-md-1 col-sm-1 custom"><a data-toggle="tab" href="#jan" aria-expanded="true">JAN</a></div>
                <div class="col-md-1 col-sm-1 custom"><a data-toggle="tab" href="#feb" aria-expanded="false">FEB</a></div>
                <div class="col-md-1 col-sm-1 custom">MAR</div>
                <div class="col-md-1 col-sm-1 custom">APR</div>
                <div class="col-md-1 col-sm-1 custom">MAY</div>
                <div class="col-md-1 col-sm-1 custom">JUN</div>
                <div class="col-md-1 col-sm-1 custom">JUL</div>
                <div class="col-md-1 col-sm-1 custom">AUG</div>
                <div class="col-md-1 col-sm-1 custom">SEP</div>
                <div class="col-md-1 col-sm-1 custom">OCT</div>
                <div class="col-md-1 col-sm-1 custom">NOV</div>
                <div class="col-md-1 col-sm-1 custom">DEC</div>
            </div>
        </div>
        <div class="tab-content">
            <div id="jan" class="panel panel-default tab-pane fade active in">
                <div class="panel-heading">
                    <div class="panel-title">January month's Budget</div>
                </div>
                <div class="panel-body">
                    <div class="progress-container">
                        <div class="item-border">
                            <div class="card">
                                <div class="card-content black-text">
                                    <span class="card-title">Home Rent</span>
                                    <div class="progress test">
                                        <div class="progress-bar progress-bar-danger" style="width:90%;">
                                            90%
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <a href="#">EDIT</a>
                                    <a class="right" href="#">DELETE</a>
                                </div>
                            </div>
                        </div>
                        <div class="item-border">
                            <h3>Entertainment</h3>
                            <div class="progress animated-bar">
                                <div class="determinate white-text center" style="width:75%;">
                                    75%
                                </div>
                            </div>
                        </div>
                        <h4>Travel</h4>
                        <div class="progress test">
                            <div class="progress-bar progress-bar-info" style="width:60%;">
                                60%
                            </div>
                        </div>
                        <h4>Utilities</h4>
                        <div class="progress test">
                            <div class="progress-bar progress-bar-success" style="width:40%;">
                                40%
                            </div>
                        </div>
                        <h4>Auto Premium</h4>
                        <div class="progress test">
                            <div class="progress-bar progress-bar-success" style="width:45%;">
                                45%
                            </div>
                        </div>
                        <h4>Gas and Fuel</h4>
                        <div class="progress test">
                            <div class="progress-bar progress-bar-success" style="width:35%;">
                                35%
                            </div>
                        </div>
                        <h4>Shopping</h4>
                        <div class="progress test">
                            <div class="progress-bar progress-bar-success" style="width:10%;">
                                10%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="feb" class="panel panel-default tab-pane fade">
                <div class="panel-heading">
                    <div class="panel-title">February month's Budget</div>
                </div>
                <div class="panel-body">
                    <div class="progress-container">
                        <h4>Food and Dining</h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-default" style="width:60%;">
                                60%
                            </div>
                        </div>
                        <h4>Entertainment</h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" style="width:85%;">
                                85%
                            </div>
                        </div>
                        <h4>Travel</h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" style="width:40%;">
                                40%
                            </div>
                        </div>
                        <h4>Utilities</h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" style="width:33%;">
                                33%
                            </div>
                        </div>
                        <h4>Auto Premium</h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" style="width:40%;">
                                40%
                            </div>
                        </div>
                        <h4>Gas and Fuel</h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" style="width:55%;">
                                55%
                            </div>
                        </div>
                        <h4>Shopping</h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" style="width:68%;">
                                68%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js"></script>

    <!--Javascript-->
    <script src="../js/jquery-1.12.0.js"></script>
    <script src="../js/bootstrap.js"></script>
    </body>

</html>