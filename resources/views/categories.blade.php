<!doctype html>
<html lang="en">
<head lang="en">
    <title>Categories</title>
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
    <h1>List of categories</h1>

    <input type="text" id="search" placeholder="SEARCH..">

    <div id="table-container">
        <table id="maintable" class="table table-hover table-responsive">
            <thead class="table__header-bg-color">
            <tr>
                <th>ID</th>
                <th>c1</th>
                <th>c2</th>
                <th>c3</th>
                <th>Type</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $data1)
                <tr>
                    <td>{!! $data1['id'] !!}</td>
                    <td>{!! $data1['hierarchy']['0'] !!}</td>
                    @if(isset($data1['hierarchy']['1']))
                        <td>{!! $data1['hierarchy']['1'] !!}</td>
                    @endif
                    @if(isset($data1['hierarchy']['2']))
                        <td>{!! $data1['hierarchy']['2'] !!}</td>
                    @endif
                    <td>{!! $data1['type'] !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div id="bottom_anchor"></div>
    </div>

<!--Javascript-->
<script src="../js/jquery-1.12.0.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../css/stopscroll.js"></script>
</body>
</html>