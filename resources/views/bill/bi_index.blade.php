@extends('main')

@section('titleText')
    BILLS
@stop

@section('content')

    <div class="testing">
        <a href="bill/getbyId/123"><button class="btn btn-primary" style="margin: 0 10px;">GET</button></a>
        <a href="bill/getAll"><button class="btn btn-info" style="margin: 0 10px;">GET ALL</button></a>
        <a href="bill/create"><button class="btn btn-success" style="margin: 0 10px;">CREATE</button></a>
        <a href="bill/update/123"><button class="btn btn-warning" style="margin: 0 10px;">UPDATE</button></a>
        <a href="bill/delete/123"><button class="btn btn-danger" style="margin: 0 10px;">DELETE</button></a>
    </div>
    <div class="response" style="border: 1px solid red; width: 50%; margin: auto; margin-top: 20px;">
        Response Message will be displayed here

    </div>
@stop

@section('js')
    <script src="../js/bill.js"></script>
@stop