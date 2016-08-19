
{!! Form::open(array('id' => "register", 'method' => "post")) !!}

{!! Form::label('bank',"Bank: ") !!}
{!! Form::select(
			'bank', array(
	                'amex' => 'American Express',
	                'bofa' => 'Bank Of America',
	                'capone360' => 'Capital One 360',
	                'schwab' => 'Charles Schwab',
	                'chase' => 'Chase',
	                'citi' => 'Citi Bank',
	                'fidelity' => 'Fidelity',
	                'nfcu' => 'Navy Federal Credit Union',
	                'pnc' => 'PNC',
	                'suntrust' => 'Sun Trust',
	                'td' => 'TD America',
	                'us' => 'US Bank',
	                'usaa' => 'USAA',
	                'wells' => 'Wells Fargo'
                    ),
            null,
            array('class' => 'classname')
            )
!!}
<br>
{!! Form::label('username',"Username: ") !!}
{!! Form::text('username',null) !!}
<br>
{!! Form::label('password',"Pin: ") !!}
{!! Form::password('password',null) !!}
<br>
{!! Form::submit('Add Account',['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}
