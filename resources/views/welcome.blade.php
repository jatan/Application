
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Money Tracker-Gemma</title>

    <!-- CSS -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Montserrat:400,700">
    <link rel="stylesheet" href="css/bootstrap.css">


    <link rel="stylesheet" href="css/main.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media-queries.css">


    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="img/checkbox.png">

</head>

<body>

<!-- Top menu -->
<nav class="navbar navbar-fixed-top navbar-no-bg" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">Money Tracker</a>
        </div>
        <div class="collapse navbar-collapse" id="top-navbar-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a class="scroll-link" href="#what-we-do">Features</a></li>
                <li><a class="scroll-link" href="#pricing">Prices-package</a></li>
                <li><a class="scroll-link" href="#about-us">About</a></li>
                <li><a class="scroll-link" href="#contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Top content -->
<div class="top-content">
    <div class="top-content-text">


        <h1 class="wow fadeInLeftBig" data-wow-duration="1.5s">
            Welcome to our site
        </h1>
        <p class="wow fadeInLeftBig" data-wow-duration="1.5s" data-wow-delay="0.5s">
            Budget your finances to keep track of where your money is going.
            <strong>MONEY TRACKER</strong> is a complete online money management tool designed to keep track of all
            your transactions and bank accounts from your computer, mobile phone, or iPad. Receive reminders to your
            phone or email when your bills are due. Get started for Free!
        </p>
        <div class="top-content-bottom-link wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="1.5s">

            <a class="big-link-1 btn scroll-link" data-toggle="modal" data-target="#Modaltest">Sign Up</a>

            <a class="big-link-2 btn scroll-link" >Login</a>
        </div>
    </div>
</div>


<div class="container ">
    <div class="modal" id="Modaltest" tabindex="-1" role="dialog" arial-labeledby="modalLabel">
    <div class="modal-dialog modal-sm" role="form">
        <div class="modal-content">
            <div class="modal-header">
                <h1>SIGN UP</h1>
            </div>
            <div class="modal-body text-right">
                {!! Form::open(['url'=>"/register"]) !!}

                    {!! Form::label('email',"Email:") !!}
                    {!! Form::text('email',null, ['id' => "email"]) !!}

                    {!! Form::label('password',"Password:") !!}
                    {!! Form::password('password',['id' => "password"]) !!}

                    {!! Form::label('password_confirmation',"Re-Enter:") !!}
                    {!! Form::password('password_confirmation',['id' => "password_confirmation"]) !!}

                <div class="text-center">
                    {!! Form::submit('Submit',['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
                @if($errors->any())
                    <ul class="alert alert-danger">
                        @foreach($errors -> all() as $error)
                            <li class="alert-danger">{!! $error !!}</li>
                        @endforeach
                    </ul>
                @endif
                {{--<form>
                    <label>Email ID: </label>
                    <input id ='email' type="email" placeholder="Valid email required" name="email"><br><br>
                    <label>Password: </label>
                    <input id='password' type="password" maxlength='8' placeholder="8 characters max" name="password"><br><br>
                    <label>Re-Enter: </label>
                    <input id='repassword' type="password" maxlength='8' placeholder="Enter password again" name="rePassword"><br><br>

                </form>--}}
            </div>
        </div>
    </div>
    </div>
</div>
<!-- Javascript -->
<script src="js/jquery-1.12.0.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/backstretch.js"></script>
<script src="js/wow.js"></script>
<script src="js/retina.js"></script>
<script src="js/jquery.magnific-popup.js"></script>
<script src="js/waypoint.js"></script>
<script src="js/masonry.pkgd.js"></script>
<script src="js/scripts.js"></script>

<!--[if lt IE 10]>
<script src="assets/js/placeholder.js"></script>
<![endif]-->

</body>

</html>