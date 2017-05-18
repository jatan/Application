@extends('main')

@section('content')

    <div class="user-panel" style="width: 50%; margin: auto">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">USER PROFILE</h3>
            </div>
            <div class="panel-body">
	            <form>
		            <div class="form-group">
		                <label for="fName">First Name</label>
		                <input id="fName" name="fName" type="text" value="{{ $finalResponse['Fname'] }}">
		            </div>
		            <div class="form-group">
		                <label for="lName">Last Name</label>
		                <input id="lName" name="lName" type="text" value="{{ $finalResponse['Lname'] }}">
		            </div>
	                <input type="submit" class="btn btn-success" value="SAVE">
	            </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="../js/profile.js"></script>
@stop