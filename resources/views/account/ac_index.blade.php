@extends('main')

@section('titleText')
    ACCOUNTS
@stop

@section('content')

    <div class="testing">

        <a href="account/getAll"><button class="btn btn-info" style="margin: 0 10px;">GET ALL</button></a>
        <a href="account/create"><button class="btn btn-primary" style="margin: 0 10px;">ADD NEW</button></a>
        <a href="account/syncMaster"><button class="btn btn-success" style="margin: 0 10px;">REFRESH ALL</button></a>
    </div>
    <div class="response" style="border: 1px solid red; width: 90%; margin: auto; margin-top: 20px; margin-bottom: 50px; padding: 30px;">
        Response Message will be displayed here

    </div>
@stop

@section('js')
    <script src="../js/account.js"></script>
@stop
