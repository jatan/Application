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
</body>
</html>