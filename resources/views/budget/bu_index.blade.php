@extends('main')

@section('css')
	<link rel="stylesheet" href="../css/budget.css">
@stop

@section('content')
	<div><button class="btn btn-primary" onclick="location.href='/update';">UPDATE</button></div>
	<div class="months">
		<div class="row">
			@foreach($monthShortName as $key => $monthCode)
			<div class="col-md-1 col-sm-1 custom {{$key == $CurrentMonth ? 'bg-green' : 'bg-lightgreen'}}">
				<a href="#{{$monthCode}}" data-toggle="tab" aria-expanded="true">
					{{$monthCode}} - '{{$key <= $CurrentMonth ? substr($CurrentYear,2) : substr($CurrentYear-1,2)}}
				</a>
			</div>
			@endforeach
		</div>
	</div>
	<div class="tab-content">
		@foreach($monthShortName as $key => $month)
			@if($key == $CurrentMonth)
				<div id="{{$monthShortName[$key]}}" class="panel panel-info tab-pane fade active in">
			@else
				<div id="{{$monthShortName[$key]}}" class="panel panel-info tab-pane fade">
			@endif
			<div class="panel-heading">
				<div class="panel-title"><h2>{{$monthFullName[$key]}} month's Budget</h2></div>
			</div>

			<div class="panel-body">
				<div style="float: left;">
					<button class="btn btn-success" data-toggle="modal" data-target="#newBudgetForm{{$monthShortName[$key]}}">New Budget</button>
				</div>
				<div id="newBudgetForm{{$monthShortName[$key]}}" class="modal fade" role="dialog" style="display: none;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<div class="modal-title">
									<button type="button" class="btn close" data-dismiss="modal">X</button>
									<h4>Create New Budget</h4>
								</div>
							</div>
							<div class="modal-body">
								<form class="form-inline" action="budget/create" method="post">
									<input type="hidden" name="budgetForMonth" value="{{$key}}">
									<input type="hidden" name="budgetForYear" value="{{$key <= $CurrentMonth ? $CurrentYear : $CurrentYear-1}}">
									<div class="form-group">
										<label for="cat">Category</label>
										<select id="cat" class="form-control" name="category">
											<option>Bank Fees</option>
											<option>Cash Advance</option>
											<option>Community</option>
											<option>Gas and Fuel</option>
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
				<div class="right">
					<label>sort by</label>
					<select id="sortBudget">
						<option value="Stat">Status</option>
						<option value="Category">Category</option>
						<option value="HtoL" selected="selected">Amount: High to Low</option>
						<option value="LtoH">Amount: Low to High</option>
					</select>
				</div>
				@if(!array_key_exists($key, $budgetLists))
					<div class="bg-status-display text-center" style="margin-top: 50px;">
						<h3>You have not set up any budgets yet</h3>
					</div>
				@else
					@foreach($budgetLists[$key] as $budget)
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
								<button class="btn btn-success left" data-toggle="modal" data-target="#listTrans{{$monthShortName[$key]}}" data-budgetname="{{$budget['Name']}}" data-month="{{$key}}">Transactions</button>
								<button class="btn btn-warning left" data-toggle="modal" data-target="#editBudget{{$monthShortName[$key]}}" data-budgetname="{{$budget['Name']}}" data-setvalue="{{$budget['SetValue']}}">EDIT</button>
								<button class="btn btn-danger right" data-toggle="modal" data-target="#deleteBudget{{$monthShortName[$key]}}" data-budgetname="{{$budget['Name']}}">DELETE</button>
							</div>
						</div>
					</div>
					<div id="editBudget{{$monthShortName[$key]}}" class="modal fade" role="dialog" style="display: none;">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<div class="modal-title">
										<button type="button" class="btn close" data-dismiss="modal">X</button>
										<h4>Edit Budget</h4>
									</div>
								</div>
								<div class="modal-body">
									<form class="form-inline" action="budget/update" method="post">
										<input type="hidden" name="budgetForMonth" value="{{$key}}">
										<input type="hidden" name="budgetForYear" value="{{$key <= $CurrentMonth ? $CurrentYear : $CurrentYear-1}}">
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

					<div id="deleteBudget{{$monthShortName[$key]}}" class="modal fade" role="dialog" style="display: none;">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<div class="modal-title">
										<button type="button" class="btn close" data-dismiss="modal">X</button>
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

					<div id="listTrans{{$monthShortName[$key]}}" class="modal fade switchModal" role="dialog" style="display: none;">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<div class="modal-title">
										<button type="button" class="btn close" data-dismiss="modal">X</button>
										<h4>List Of Transactions</h4>
									</div>
								</div>
								<div class="modal-body">
									<div id="table-container">
						                <table id="maintable" class="table table-hover table-responsive">
						                    <thead class="table__header-bg-color">
						                    </thead>
						                    <tbody>
						                    </tbody>
						                </table>
						                <div id="bottom_anchor"></div>
						            </div>
								</div>
							</div>
						</div>
					</div>
					@endforeach
				@endif
			</div>
		</div>
		@endforeach
	</div>
@stop

@section('js')
    <script src="../js/budget.js"></script>
@stop
