@extends('main')

@section('css')
	<link rel="stylesheet" href="../css/budget.css">
@stop

{{--@section('titleText')--}}
    {{--BUDGETS--}}
{{--@stop--}}

@section('content')

	<div class="months">
		<div class="row">
			<div class="col-md-1 col-sm-1 custom"><a data-toggle="tab" href="#jan" aria-expanded="true">JAN</a></div>
			<div class="col-md-1 col-sm-1 custom"><a data-toggle="tab" href="#feb" aria-expanded="false">FEB</a></div>
			{{--            <div class="col-md-1 col-sm-1 custom">MAR</div>
							<div class="col-md-1 col-sm-1 custom">APR</div>
							<div class="col-md-1 col-sm-1 custom">MAY</div>
							<div class="col-md-1 col-sm-1 custom">JUN</div>
							<div class="col-md-1 col-sm-1 custom">JUL</div>
							<div class="col-md-1 col-sm-1 custom">AUG</div>
							<div class="col-md-1 col-sm-1 custom">SEP</div>
							<div class="col-md-1 col-sm-1 custom">OCT</div>
							<div class="col-md-1 col-sm-1 custom">NOV</div>
							<div class="col-md-1 col-sm-1 custom">DEC</div>
			--}}
		</div>
	</div>
	<div class="tab-content">
		<div id="jan" class="panel panel-info tab-pane fade active in">
			<div class="panel-heading">
				<div class="panel-title"><h2>January month's Budget</h2></div>
			</div>

			<div class="panel-body">
				<div style="float: left;">
					<button class="btn btn-success" data-toggle="modal" data-target="#newBudgetForm">New Budget</button>
				</div>
				<div class="right">
					<label>sort by</label>
					<select id="sortBudget">
						<option value="Stat">Status</option>
						<option value="Category">Category</option>
						<option value="HtoL" selected="selected">Amount: High to Low</option>
						<option value="LtoH">Amount: Low to High</option>
					</select>
				</div>
				@foreach($budgetLists as $budget)
				<div class="progress-container">
					<div class="card">
						<div class="card-content black-text">
							<span class="card-title">{{$budget['Name']}}</span>
							<div class="">Budgeted Amount: {{$budget['SetValue']}}</div>
							<div class="progress test">
								<div class="" style="width:{{ intval($budget['SpentValue'] * 100 / $budget['SetValue']) }}%;">
									{{intval($budget['SpentValue'] * 100 / $budget['SetValue'])}} %
								</div>
							</div>
							<div class="">Remaining Amount: {{$budget['SetValue'] - $budget['SpentValue']}}</div>
						</div>
						<div class="card-action">
							<button class="btn btn-success left" data-toggle="modal" data-target="#editBudget" data-budgetname="{{$budget['Name']}}" data-setvalue="{{$budget['SetValue']}}">EDIT</button>
							<button class="btn btn-success right" data-toggle="modal" data-target="#deleteBudget" data-budgetname="{{$budget['Name']}}">DELETE</button>
						</div>
					</div>
				</div>
				@endforeach

				<div id="newBudgetForm" class="modal fade" role="dialog" style="display: none;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<div class="modal-title">
									<button type="button" class="btn close" data-dismiss="modal">×</button>
									<h4>Create New Budget</h4>
								</div>
							</div>
							<div class="modal-body">
								<form class="form-inline" action="budget/create" method="post">
									<div class="form-group">
										<label for="cat">Category</label>
										<select id="cat" class="form-control" name="category">
											<option>Bank Fees</option>
											<option>Cash Advance</option>
											<option>Community</option>
											<option>Food and Drink</option>
											<option>Healthcare</option>
											<option>Interest</option>
											<option>Payment</option>
											<option>Recreation</option>
											<option>Service</option>
											<option>Shops</option>
											<option>Tax</option>
											<option>Transfer</option>
											<option>Travel</option>
										</select>
									</div>
									<br>
									<br>
									<div class="form-group">
										<h4>When will this happen?</h4>
										<div class="radio">
											<label>
												<input type="radio" name="frequency" id="option1" value="everyMonth" checked>
												Every Month
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="frequency" id="option2" value="everyFewMonth">
												Every Few Months
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="frequency" id="option3" value="once">
												Once
											</label>
										</div>
									</div>
									<br>
									<br>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="rollOverFlag">Start each new month with the previous month's leftover amount
										</label>
									</div>
									<br>
									<br>
									<div class="form-group">
										<label for="amount">Amount</label>
										<input type="number" class="form-control" id="amount" name="Setamount" value="">
									</div>
									<br>
									<br>
									<button type="submit" class="btn btn-success left">SAVE</button>
									<button type="button" class="btn btn-danger right" data-dismiss="modal">CANCEL</button>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div id="editBudget" class="modal fade" role="dialog" style="display: none;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<div class="modal-title">
									<button type="button" class="btn close" data-dismiss="modal">×</button>
									<h4>Edit Budget</h4>
								</div>
							</div>
							<div class="modal-body">
								<form class="form-inline" action="budget/update" method="post">
									<div class="form-group">
										<label for="cat">Category</label>
										<select id="cat" class="form-control" name="category">
											<option value="Bank Fees">Bank Fees</option>
											<option value="Cash Advance">Cash Advance</option>
											<option value="Community">Community</option>
											<option value="Food and Drink">Food and Drink</option>
											<option value="HealthCare">Healthcare</option>
											<option value="Interest">Interest</option>
											<option value="Payment">Payment</option>
											<option value="Recreation">Recreation</option>
											<option value="Service">Service</option>
											<option value="Shops">Shops</option>
											<option value="Tax">Tax</option>
											<option value="Transfer">Transfer</option>
											<option value="Travel">Travel</option>
										</select>
									</div>
									<br>
									<br>
									<div class="form-group">
										<h4>When will this happen?</h4>
										<div class="radio">
											<label>
												<input type="radio" name="frequency" id="option1" value="everyMonth" checked>
												Every Month
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="frequency" id="option2" value="everyFewMonth">
												Every Few Months
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="frequency" id="option3" value="once">
												Once
											</label>
										</div>
									</div>
									<br>
									<br>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="rollOverFlag">Start each new month with the previous month's leftover amount
										</label>
									</div>
									<br>
									<br>
									<div class="form-group">
										<label for="amount">Amount</label>
										<input type="number" class="form-control" id="amount" name="Setamount" value="">
									</div>
									<br>
									<br>
									<button type="submit" class="btn btn-success left">SAVE</button>
									<button type="button" class="btn btn-danger right" data-dismiss="modal">CANCEL</button>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div id="deleteBudget" class="modal fade" role="dialog" style="display: none;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<div class="modal-title">
									<button type="button" class="btn close" data-dismiss="modal">×</button>
									<h4>Delete Budget</h4>
								</div>
							</div>
							<div class="modal-body">
								<form class="form-inline" action="budget/delete" method="post">
									<input type="hidden" name="Name" value="" />
									<h3 class="h3">Are you sure you want to delete this budget?</h3>
									<br>
									<br>

									<button type="button" class="btn btn-success left" data-dismiss="modal">NO</button>
									<button type="submit" class="btn btn-danger right">YES</button>
								</form>
							</div>
						</div>
					</div>
				</div>

				@if(count($budgetLists) == 0)
				<div class="bg-status-display text-center" style="margin-top: 50px;">
					<h3>You have not set up any budgets yet</h3>
				</div>
				@endif
			</div>


		</div>
	</div>
@stop

@section('js')
    <script src="../js/budget.js"></script>
@stop
