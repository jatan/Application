<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Landing Page</title>

    <!--CSS-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/landingpage-style.css">
    <link rel="stylesheet" type="text/css" href="css/landingpage-media-queries.css">

    <link rel="stylesheet" type="text/css" href="css/landingpage.css">

    <link rel="shortcut icon" href="img/checkbox.png">
</head>
<body>

<!--Top Navigation Bar start-->
<nav class="navbar navbar-fixed-top navbar-no-bg" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <!--Button will go on the right side-->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!--This link will go on the left side-->
            <a class="navbar-brand" href="">Landing Page</a>
        </div>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="nav navbar-nav navbar-right">
                <li><a class="scroll-link" href="">Features</a></li>
                <li><a class="scroll-link" href="">Prices</a></li>
                <li><a class="scroll-link" href="">About</a></li>
                <li><a class="scroll-link" href="">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
<!--Top Navigation Bar end-->

<div class="top-content">
    <div class="top-content-text">
        <h1 class="wow fadeInLeftBig" data-wow-duration="1.5s">
            Welcome to our site
        </h1>
        <p class="wow fadeInLeftBig" data-wow-duration="1.5s" data-wow-delay="0.5s">
            Budget your finances to keep track of where your money is going.
            <strong>MONEY TRACKER</strong> is a complete online money management tool designed to keep track of all your transactions and bank accounts from your computer, mobile phone, or iPad. Receive reminders to your phone or email when your bills are due. Get started for Free!
        </p>
        <div class="top-content-bottom-link wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="1.5s">
            <a class="big-link-1 btn scroll-link" data-toggle="modal" data-target="#Modaltest">Sign Up</a>
            <a class="big-link-2 btn scroll-link" data-toggle="modal" data-target="#Modaltest"><span class="glyphicon glyphicon-lock"></span> Log In</a>
        </div>
    </div>
</div>

<div class="modal" id="Modaltest" tabindex="-1" role="dialog" arial-labeledby="modalLabel">
    <div class="modal-dialog modal-sm" role="form">
        <div class="modal-content">
            <div class="modal-header">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a data-toggle="tab"href="#su"><h1>SIGN UP</h1></a></li>
                    <li><a data-toggle="tab"href="#li"><h1>LOG IN</h1></a></li>
                </ul>
            </div>
            <div class="modal-body text-center">
                <div class="tab-content">
                    <div id="su" class="tab-pane fade in active">
                        {!! Form::open(['url'=>"/register"]) !!}

                        {!! Form::label('email',"Email ID: ") !!}
                        {!! Form::email('email',null, ['id' => "email", 'placeholder' => "Valid email required"]) !!}<br><br>

                        {!! Form::label('password',"Password:") !!}
                        {!! Form::password('password',['id' => "password", 'placeholder' => "8 character minimum"]) !!}<br><br>

                        {!! Form::label('password_confirmation',"Re-Enter:") !!}
                        {!! Form::password('password_confirmation',['id' => "password_confirmation", 'placeholder' => "Enter password again"]) !!}<br><br>

                        <div class="top-content-bottom-link wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="1.5s">
                        {!! Form::submit('Sign Up',['class' => 'big-link-1 btn scroll']) !!}
                        </div>
                        {!! Form::close() !!}
                        @if($errors->any())
                        <ul class="alert alert-danger">
                        @foreach($errors -> all() as $error)
                        <li class="alert-danger">{!! $error !!}</li>
                        @endforeach
                        </ul>
                        @endif
                    </div>
                    <div id="li" class="tab-pane fade">

                        {!! Form::open(['url'=>"/login"]) !!}
                            {!! Form::label('email',"Email ID:") !!}
                            {!! Form::text('email',null, ['id' => "email", 'placeholder' => "Valid email required"]) !!}<br><br>

                            {!! Form::label('password',"Password:") !!}
                            {!! Form::password('password') !!}

                        <div class="top-content-bottom-link wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="1.5s"><br><br>
                            {!! Form::submit('LogIn',['class' => 'big-link-1 btn scroll']) !!}
                            {!! Form::close() !!}
                        @if($errors->any())
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div><!-- tab-content -->
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->

<!--Javascript-->
<script type="text/javascript" src="js/jquery-1.12.0.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/backstretch.js"></script>
<script type="text/javascript" src="js/landingpage.js"></script>

</body>
</html>